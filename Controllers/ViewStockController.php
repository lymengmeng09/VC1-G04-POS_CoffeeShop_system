<?php
require "Models/StockModels.php";

class ViewStockController extends BaseController {
    private $productModel;
    private $uploadDir = "uploads/";
    private $telegramBotToken = '7898878636:AAFtwwPFcVSIi256SkNUaKitGDS5eaOhq1o';

    public function __construct() {
        $this->productModel = new ProductModel();
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index() {
        $this->view('stock-products/viewStock', ['products' => $this->productModel->getAllProducts()]);
    }

    private function getAllChatIds() {
        return $this->productModel->getAllTelegramUsers();
    }

    public function registerTelegramUser($chatId) {
        try {
            $success = $this->productModel->addTelegramUser($chatId);
            if ($success) {
                error_log("Registered new Telegram user: $chatId");
            }
            return $success;
        } catch (Exception $e) {
            error_log("Error registering Telegram user: " . $e->getMessage());
            return false;
        }
    }

    public function telegramWebhook() {
        try {
            $update = json_decode(file_get_contents('php://input'), true);
            error_log("Webhook received: " . json_encode($update));
            if (isset($update['message']['chat']['id'])) {
                $chatId = $update['message']['chat']['id'];
                if ($this->registerTelegramUser($chatId)) {
                    $this->sendToTelegram("Welcome to the stock update bot! You will now receive stock updates.", [$chatId]);
                } else {
                    error_log("Failed to register chat ID: $chatId");
                }
            } else {
                error_log("Webhook: No chat ID found in update");
            }
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
        } catch (Exception $e) {
            error_log("Webhook error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    private function sendToTelegram($message, $specificChatIds = null) {
        error_log("sendToTelegram: Attempting to send message: " . substr($message, 0, 50));
        
        $chatIds = $specificChatIds ?? $this->getAllChatIds();
        if (empty($chatIds)) {
            error_log("sendToTelegram: No Telegram users found to broadcast to");
            return false;
        }

        error_log("sendToTelegram: Broadcasting to " . count($chatIds) . " chat IDs: " . implode(', ', $chatIds));
        
        $successCount = 0;
        $url = "https://api.telegram.org/bot{$this->telegramBotToken}/sendMessage";
        
        foreach ($chatIds as $chatId) {
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
            curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
            curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w')); // Log curl details
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($response === false || $httpCode !== 200) {
                error_log("sendToTelegram: Telegram API Error for chat $chatId: " . ($error ?: "HTTP $httpCode") . " - Response: " . $response);
                continue;
            }
            
            $result = json_decode($response, true);
            if ($result['ok']) {
                $successCount++;
                error_log("sendToTelegram: Successfully sent to chat $chatId");
            } else {
                error_log("sendToTelegram: Telegram API Failed for chat $chatId: " . ($result['description'] ?? 'Unknown error') . " - Response: " . json_encode($result));
            }
        }
        
        error_log("sendToTelegram: Broadcast completed. Sent to $successCount out of " . count($chatIds) . " users");
        return $successCount > 0;
    }

    // New method to test Telegram sending
    public function testTelegram() {
        try {
            $message = "📦 Test message from CoffeeShop system\nTime: " . date('m/d/Y h:i A', time());
            $success = $this->sendToTelegram($message);
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['notification'] = $success ? "Test message sent successfully" : "Failed to send test message";
            $this->redirect('/viewStock');
        } catch (Exception $e) {
            error_log("Error in testTelegram: " . $e->getMessage());
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['notification'] = "Error sending test message: " . $e->getMessage();
            $this->redirect('/viewStock');
        }
    }

    public function add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
    
                error_log("Received POST request to /add-product");
                error_log("POST data: " . json_encode($_POST));
                error_log("FILES data: " . json_encode($_FILES));
    
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
    
                    $image = null;
                    if (isset($files['name'][$i]) && $files['error'][$i] == UPLOAD_ERR_OK) {
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        $fileType = mime_content_type($files['tmp_name'][$i]);
                        if (!in_array($fileType, $allowedTypes)) {
                            throw new Exception("Only JPEG, PNG, and GIF images are allowed for product " . ($i + 1));
                        }
    
                        $maxFileSize = 5 * 1024 * 1024;
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
    
                    $product_id = $this->productModel->addProduct($name, (float)$price, (int)$quantity, $image);
                    if (!$product_id) {
                        throw new Exception("Failed to add product " . ($i + 1) . " to database");
                    }
                    $productDetails = "📦 <b>New Product Added!</b> 🎉\n" .
                    "➖➖➖➖➖➖➖➖➖\n" .
                    "📛 <b>Name:</b> $name\n" .
                    "💰 <b>Price:</b> $" . number_format($price, 2) . "\n" .
                    "📦 <b>Quantity:</b> $quantity\n" .
                    "🖼️ <b>Image:</b> " . ($image ? "<i>$image</i>" : "No image uploaded") . "\n" .
                    "➖➖➖➖➖➖➖➖➖\n" .
                    "Added on: " . date('m/d/Y h:i A');
  $this->sendToTelegram($productDetails);
    
                    error_log("Adding product $i to database");
                    $receiptItems[] = [
                        'name' => $name,
                        'change_quantity' => $quantity,
                        'price' => $price,
                        'timestamp' => $timestamp
                    ];
                }
    
                $this->productModel->storeReceiptData($receiptItems, 'added');
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
    
                // Update the product and get the result
                $result = $this->productModel->updateProduct($id, $data['name'], $data['price'], $data['quantity']);
                if (!$result['success']) {
                    throw new Exception("Failed to update product.");
                }
    
                error_log("Product updated with ID: $id");
    
                // Notify via Telegram for the update
                $this->notifyStockChange($id, "Updated");
    
                // Prepare notifications
                $notifications = ["Product '{$data['name']}' updated successfully."];
                if (!empty($result['notifications'])) {
                    $notifications = array_merge($notifications, $result['notifications']);
                    // Send low stock or out of stock notifications to Telegram
                    foreach ($result['notifications'] as $notification) {
                        $this->sendToTelegram($notification);
                    }
                }
    
                $_SESSION['notification'] = implode(' ', $notifications);
                $this->redirect('/viewStock');
            } catch (Exception $e) {
                error_log("Error in update(): " . $e->getMessage());
                $_SESSION['notification'] = "Error updating product: " . $e->getMessage();
                $this->redirect('/viewStock');
            }
        }
    }
    
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
                    $quantityChange = (int)$newQuantity;
                    $updatedQuantity = $existingQuantity + $quantityChange;

                    if ($updatedQuantity < 0) {
                        throw new Exception("Cannot reduce quantity below 0 for product '{$product['name']}'. Current quantity is $existingQuantity.");
                    }

                    $result = $this->productModel->updateProduct($productId, $product['name'], (float)$newPrice, $updatedQuantity);
                    if (!$result) {
                        throw new Exception("Failed to update product '{$product['name']}' in database");
                    }

                    error_log("Product updated with ID: $productId");
                    $this->notifyStockChange($productId, "Updated");

                    $changeQuantity = $quantityChange > 0 ? "+$quantityChange" : $quantityChange;
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

                $this->productModel->storeReceiptData($receiptItems, 'updated');
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
        try {
            $this->productModel->deleteProduct($id);
            $this->notifyStockChange($id, "Deleted");
            $this->redirect('/viewStock');
        } catch (Exception $e) {
            error_log("Error in destroy(): " . $e->getMessage());
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['notification'] = 'Error deleting product: ' . $e->getMessage();
            $this->redirect('/viewStock');
        }
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
        try {
            $receipts = $this->productModel->getAllReceipts();
            $this->view('stock-products/receipts', ['receipts' => $receipts]);
        } catch (Exception $e) {
            error_log("Error in viewReceipts: " . $e->getMessage());
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['notification'] = 'Error fetching receipts: ' . $e->getMessage();
            $this->redirect('/viewStock');
        }
    }

    private function notifyStockChange($productId, $action) {
        try {
            error_log("notifyStockChange: Broadcasting stock change for product ID: $productId, Action: $action");
            $product = $this->productModel->getProductById($productId);
            if (!$product && $action !== "Deleted") {
                error_log("notifyStockChange: Product not found for ID: $productId");
                return;
            }
            
            $timestamp = date('m/d/Y h:i A', time());
            $message = "📦 <b>Stock $action!</b> " . ($action === "Deleted" ? "🗑️" : "✨") . "\n" .
            "➖➖➖➖➖➖➖➖➖\n" .
            "🕒 <b>Time:</b> <i>{$timestamp}</i>\n";
 
 if ($action !== "Deleted") {
     $message .= "📛 <b>Name:</b> " . htmlspecialchars($product['name']) . "\n" .
                 "💰 <b>Price:</b> $" . number_format($product['price'], 2) . "\n" .
                 "📦 <b>Quantity:</b> " . $product['quantity'] . "\n" .
                 ($action === "Updated" ? "🔄 <b>Change:</b> " . ($product['quantity'] > 0 ? "Stock updated" : "Out of stock!") . "\n" : "");
 } else {
     $message .= "🆔 <b>Product ID:</b> $productId\n" .
                 "❌ <b>Status:</b> Removed from stock\n";
 }
 
 $message .= "➖➖➖➖➖➖➖➖➖";
 $this->sendToTelegram($message);
            
        } catch (Exception $e) {
            error_log("Error in notifyStockChange: " . $e->getMessage());
        }

    }
}

