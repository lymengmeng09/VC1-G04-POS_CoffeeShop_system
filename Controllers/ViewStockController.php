<?php
require "Models/StockModels.php";

class ViewStockController extends BaseController {
    private $productModel;
    private $uploadDir = "uploads/";

    public function __construct() {
        $this->productModel = new ProductModel();
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index() {
        $this->view('stock-products/viewStock', ['products' => $this->productModel->getAllProducts()]);
    }

    public function add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
    
                // Log incoming data for debugging
                error_log("Received POST request to /add-product");
                error_log("POST data: " . json_encode($_POST));
                error_log("FILES data: " . json_encode($_FILES));
    
                // Collect form data
                $names = $_POST['name'] ?? [];
                $prices = $_POST['price'] ?? [];
                $quantities = $_POST['quantity'] ?? [];
                $files = $_FILES['image'] ?? [];
    
                if (empty($names) || empty($prices) || empty($quantities) || empty($files['name'])) {
                    throw new Exception('All fields (name, price, quantity, image) are required for at least one product');
                }
    
                if (count($names) !== count($prices) || count($names) !== count($quantities) || count($names) !== count($files['name'])) {
                    throw new Exception('Mismatch in number of fields submitted');
                }
    
                $receiptItems = [];
                $timestamp = date('Y-m-d H:i:s');
    
                // Process each product
                for ($i = 0; $i < count($names); $i++) {
                    $name = trim($names[$i] ?? '');
                    $price = $prices[$i] ?? '';
                    $quantity = $quantities[$i] ?? '';
    
                    if (empty($name) || $price === '' || $quantity === '') {
                        throw new Exception("All fields are required for product " . ($i + 1));
                    }
    
                    if (!is_numeric($price) || $price <= 0) {
                        throw new Exception("Price must be a positive number for product " . ($i + 1));
                    }
    
                    if (!is_numeric($quantity) || $quantity < 0 || floor($quantity) != $quantity) {
                        throw new Exception("Quantity must be a non-negative integer for product " . ($i + 1));
                    }
    
                    // Handle image upload
                    $image = null;
                    if (isset($files['name'][$i]) && $files['error'][$i] == UPLOAD_ERR_OK) {
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        $fileType = mime_content_type($files['tmp_name'][$i]);
                        if (!in_array($fileType, $allowedTypes)) {
                            throw new Exception("Only JPEG, PNG, and GIF images are allowed for product " . ($i + 1));
                        }
    
                        $maxFileSize = 5 * 1024 * 1024;  // 5MB
                        if ($files['size'][$i] > $maxFileSize) {
                            throw new Exception("Image size must not exceed 5MB for product " . ($i + 1));
                        }
    
                        $imageName = basename($files['name'][$i]);
                        $targetFile = $this->uploadDir . uniqid() . '_' . $imageName;
                        if (!move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                            throw new Exception("Failed to upload image for product " . ($i + 1));
                        }
                        $image = $targetFile;
                    }
    
                    // Add product to the database
                    $result = $this->productModel->addProduct($name, (float)$price, (int)$quantity, $image);
                    if (!$result) {
                        throw new Exception("Failed to add product " . ($i + 1) . " to database");
                    }
    
                    // Send the product data to the chatbot with icon
                    $productDetails = "📦 New product added:\n" .
                                      "Name: $name\n" .
                                      "Price: $$price\n" .
                                      "Quantity: $quantity\n" .
                                      "Image: " . ($image ? $image : 'No image');
                    $this->sendToTelegram($productDetails);
    
                    // Store receipt data
                    $receiptItems[] = [
                        'name' => $name,
                        'change_quantity' => $quantity,
                        'price' => $price,
                        'timestamp' => $timestamp
                    ];
                }
    
                // Store receipt data in session
                $_SESSION['receipt'] = [
                    'items' => $receiptItems,
                    'action' => 'added'
                ];
                $_SESSION['notification'] = 'Products added successfully';
                header("Location: /viewStock?showReceipt=true");
                exit;
    
            } catch (Exception $e) {
                error_log("Error in add(): " . $e->getMessage());
                $_SESSION['notification'] = 'Error adding products: ' . $e->getMessage();
                header("Location: /viewStock");
                exit;
            }
        }
    
        // Session start for GET requests
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['notification'] = 'Invalid request method';
        header("Location: /viewStock");
        exit;
    }
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                error_log("Received POST request to /update_product");
                error_log("POST data: " . json_encode($_POST));

                $id = htmlspecialchars($_POST['id']);
                $data = [
                    'name' => htmlspecialchars($_POST['name']),
                    'price' => floatval($_POST['price']),
                    'quantity' => intval($_POST['quantity'])
                ];

                $product = $this->productModel->getProductById($id);
                if (!$product) {
                    throw new Exception("Product not found.");
                }

                $this->productModel->updateProducts($id, $data);
                error_log("Product updated with ID: $id");
                $this->notifyStockChange($id, "Updated");

                $_SESSION['notification'] = "Product '{$data['name']}' updated successfully.";
                $this->redirect('/viewStock');
            } catch (Exception $e) {
                error_log("Error in update(): " . $e->getMessage());
                $_SESSION['notification'] = "Error updating product: " . $e->getMessage();
                $this->redirect('/viewStock');
            }
        }
    }

    // Keeping updateStock() as is since it’s not in your routes but might be used elsewhere
    public function updateStock() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                error_log("Received POST request to /update-stock");
                error_log("POST data: " . json_encode($_POST));

                $productIds = $_POST["product_id"] ?? [];
                $newPrices = $_POST["price"] ?? [];
                $newQuantities = $_POST["quantity"] ?? [];

                if (empty($productIds) || count($productIds) !== count($newPrices) || count($productIds) !== count($newQuantities)) {
                    throw new Exception('Invalid form data: All fields are required for each product');
                }

                $successMessages = [];
                $outOfStockMessages = [];
                $receiptItems = [];

                foreach ($productIds as $index => $productId) {
                    $newPrice = $newPrices[$index];
                    $newQuantity = $newQuantities[$index];

                    error_log("Processing update for product ID $productId: price=$newPrice, quantity=$newQuantity");

                    if (empty($productId) || $newPrice === '' || $newQuantity === '') {
                        throw new Exception("All fields are required for product entry #" . ($index + 1));
                    }

                    if (!is_numeric($productId) || $productId <= 0 || floor($productId) != $productId) {
                        throw new Exception("Invalid product ID for product entry #" . ($index + 1));
                    }

                    if (!is_numeric($newPrice) || $newPrice <= 0) {
                        throw new Exception("Price must be a positive number for product entry #" . ($index + 1));
                    }

                    if (!is_numeric($newQuantity)) {
                        throw new Exception("Quantity must be an integer for product entry #" . ($index + 1));
                    }

                    $product = $this->productModel->getProductById($productId);
                    if (!$product) {
                        throw new Exception("Product not found for product entry #" . ($index + 1));
                    }

                    $existingQuantity = (int)$product['quantity'];
                    $updatedQuantity = $existingQuantity + (int)$newQuantity;

                    if ($updatedQuantity < 0) {
                        throw new Exception("Cannot reduce quantity below 0 for product '{$product['name']}'. Current quantity is $existingQuantity.");
                    }

                    $result = $this->productModel->updateProduct($productId, $product['name'], (float)$newPrice, $updatedQuantity);
                    if (!$result) {
                        throw new Exception("Failed to update product '{$product['name']}' in database");
                    }

                    error_log("Product updated with ID: $productId");
                    $this->notifyStockChange($productId, "Updated");

                    $changeQuantity = $newQuantity > 0 ? "+$newQuantity" : $newQuantity;
                    $receiptItems[] = [
                        'name' => $product['name'],
                        'change_quantity' => $changeQuantity,
                        'price' => $newPrice,
                        'timestamp' => date('Y-m-d H:i:s')
                    ];

                    if ($updatedQuantity == 0) {
                        $outOfStockMessages[] = "Product '{$product['name']}' is now Out of Stock.";
                    } else {
                        $successMessages[] = "Product '{$product['name']}' updated successfully. New quantity: $updatedQuantity.";
                    }
                }

                $_SESSION['receipt'] = [
                    'items' => $receiptItems,
                    'action' => 'updated'
                ];

                $notification = [];
                if (!empty($successMessages)) {
                    $notification[] = implode(' ', $successMessages);
                }
                if (!empty($outOfStockMessages)) {
                    $notification[] = implode(' ', $outOfStockMessages);
                }
                $_SESSION['notification'] = implode(' ', $notification);

                header("Location: /viewStock?showReceipt=true");
                exit;
            } catch (Exception $e) {
                error_log("Error in updateStock(): " . $e->getMessage());
                $_SESSION['notification'] = 'Error updating products: ' . $e->getMessage();
                header("Location: /viewStock");
                exit;
            }
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        error_log("Invalid request method for /update-stock");
        $_SESSION['notification'] = 'Invalid request method';
        header("Location: /viewStock");
        exit;
    }

    public function edit() {
        $id = $_GET['id'];
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            die("Product not found.");
        }
        $this->view('stock-products/edit_product', ['product' => $product]);
    }

    public function destroy($id) {
        $this->productModel->deleteProduct($id);
        $this->redirect('/viewStock');
    }

    public function dashboard() {
        $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $topProducts = $this->productModel->getTopSellingProducts($startDate, $endDate);
        include "views/dashboard.php";
    }

    public function clearReceipt() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['receipt']);
        header("Location: /viewStock");
        exit;
    }

    public function viewReceipts() {
        $stmt = $this->productModel->conn->query("SELECT * FROM receipts ORDER BY timestamp DESC");
        $receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('stock-products/receipts', ['receipts' => $receipts]);
    }

    private function sendToTelegram($message) {
        $telegramBotToken = '7898878636:AAFtwwPFcVSIi256SkNUaKitGDS5eaOhq1o'; // Replace with your bot token
        $chatId = '6461561884'; // Replace with your chat ID

        error_log("Attempting to send Telegram message: " . substr($message, 0, 50));
        
        $url = "https://api.telegram.org/bot{$telegramBotToken}/sendMessage";
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($response === false || $httpCode !== 200) {
            error_log("Telegram API Error: " . ($error ?: "HTTP $httpCode") . " - Response: " . $response);
            return false;
        }
        
        $result = json_decode($response, true);
        if (!$result['ok']) {
            error_log("Telegram API Failed: " . $result['description'] . " - Response: " . json_encode($result));
            return false;
        }
        
        error_log("Telegram message sent successfully");
        return true;
    }

    private function notifyStockChange($productId, $action) {
        try {
            error_log("Notifying stock change for product ID: $productId, Action: $action");
            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                error_log("Product not found for ID: $productId");
                return;
            }
            
            $timestamp = date('m/d/Y h:i A', time());
            $message = "📦 <b>Stock $action</b>\n" .
                      "Time: {$timestamp}\n" .
                      "Name: " . htmlspecialchars($product['name']) . "\n" .
                      "Price: $" . number_format($product['price'], 2) . "\n" .
                      "Quantity: " . $product['quantity'];
            
            $success = $this->sendToTelegram($message);
            if (!$success) {
                error_log("Failed to send Telegram message for product: " . $product['name']);
            } else {
                error_log("Telegram notification sent for product: " . $product['name']);
            }
        } catch (Exception $e) {
            error_log("Error in notifyStockChange: " . $e->getMessage());
        }
    }
}
?>
