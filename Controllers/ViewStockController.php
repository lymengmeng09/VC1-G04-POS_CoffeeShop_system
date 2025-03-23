<?php
require "Models/StockModels.php";

class ViewStockController extends BaseController{
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

                error_log("Received POST request to /add-product");
                error_log("POST data: " . json_encode($_POST));
                error_log("FILES data: " . json_encode($_FILES));

                $names = $_POST['name'] ?? [];
                $prices = $_POST['price'] ?? [];
                $quantities = $_POST['quantity'] ?? [];
                $files = $_FILES['image'] ?? [];

                if (empty($names) || empty($prices) || empty($quantities)) {
                    throw new Exception('All fields are required for at least one product');
                }

                $receiptItems = [];
                $timestamp = date('Y-m-d H:i:s');

                for ($i = 0; $i < count($names); $i++) {
                    $name = trim($names[$i] ?? '');
                    $price = $prices[$i] ?? '';
                    $quantity = $quantities[$i] ?? '';

                    error_log("Processing product $i: name=$name, price=$price, quantity=$quantity");

                    if (empty($name) || empty($price) || empty($quantity)) {
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
                    } else {
                        throw new Exception("Image upload is required for product " . ($i + 1));
                    }

                    error_log("Adding product $i to database");
                    $result = $this->productModel->addProduct($name, (float)$price, (int)$quantity, $image);
                    if (!$result) {
                        throw new Exception("Failed to add product " . ($i + 1) . " to database");
                    }

                    $receiptItems[] = [
                        'name' => $name,
                        'change_quantity' => $quantity,
                        'price' => $price,
                        'timestamp' => $timestamp
                    ];
                }

                error_log("All products added successfully");

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
        error_log("Invalid request method for /add-product");
        $_SESSION['notification'] = 'Invalid request method';
        header("Location: /viewStock");
        exit;
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
                    $updatedQuantity = $existingQuantity + (int)$newQuantity;

                    if ($updatedQuantity < 0) {
                        throw new Exception("Cannot reduce quantity below 0 for product '{$product['name']}'. Current quantity is $existingQuantity.");
                    }

                    $result = $this->productModel->updateProduct($productId, $product['name'], (float)$newPrice, $updatedQuantity);
                    if (!$result) {
                        throw new Exception("Failed to update product '{$product['name']}' in database");
                    }

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

    public function dashboard() {
        $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $topProducts = $this->productModel->getTopSellingProducts($startDate, $endDate);
        include "views/dashboard.php";
    }

    public function edit() {
        $id = $_GET['id'];
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            die("Product not found.");
        }
        $this->view('stock-products/edit_product', ['product' => $product]);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = htmlspecialchars($_POST['id']);
            $data = [
                'name' => htmlspecialchars($_POST['name']),
                'price' => floatval($_POST['price']),
                'quantity' => intval($_POST['quantity'])
            ];
            
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                die("Product not found.");
            }
            
            $this->productModel->updateProducts($id, $data);
            $this->redirect('/viewStock');
        }
    }
    public function clearReceipt() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['receipt']);
        header("Location: /viewStock");
        exit;
    }

    public function destroy($id) {
        $this->productModel->deleteProduct($id);
        $this->redirect('/viewStock');
    }
}
?>

    
