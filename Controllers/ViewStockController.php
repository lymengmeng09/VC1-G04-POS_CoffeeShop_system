<?php
require "Models/StockModels.php";

class ViewStockController {
    private $productModel;
    private $uploadDir = "uploads/";

    public function __construct() {
        $this->productModel = new ProductModel();
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index() {
        $products = $this->productModel->getAllProducts();
        include "views/stock-products/viewStock.php";
    }

    public function add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $name = trim($_POST['name'] ?? '');
                $price = $_POST['price'] ?? '';
                $quantity = $_POST['quantity'] ?? '';

                if (empty($name) || empty($price) || empty($quantity)) {
                    throw new Exception('All fields are required');
                }

                if (!is_numeric($price) || $price <= 0) {
                    throw new Exception('Price must be a positive number');
                }

                if (!is_numeric($quantity) || $quantity < 0 || floor($quantity) != $quantity) {
                    throw new Exception('Quantity must be a non-negative integer');
                }

                $image = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $fileType = mime_content_type($_FILES['image']['tmp_name']);
                    if (!in_array($fileType, $allowedTypes)) {
                        throw new Exception('Only JPEG, PNG, and GIF images are allowed');
                    }

                    $maxFileSize = 5 * 1024 * 1024;
                    if ($_FILES['image']['size'] > $maxFileSize) {
                        throw new Exception('Image size must not exceed 5MB');
                    }

                    $imageName = basename($_FILES['image']['name']);
                    $targetFile = $this->uploadDir . uniqid() . '_' . $imageName;
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        throw new Exception('Failed to upload image');
                    }
                    $image = $targetFile;
                } else {
                    throw new Exception('Image upload is required');
                }

                $result = $this->productModel->addProduct($name, (float)$price, (int)$quantity, $image);
                if (!$result) {
                    throw new Exception('Failed to add product to database');
                }

                // Store receipt details with specific timestamp
                $timestamp = date('Y-m-d H:i:s'); // Ensures specific time (e.g., 2025-03-15 14:30:45)
                $_SESSION['receipt'] = [
                    'items' => [['name' => $name, 'change_quantity' => $quantity, 'price' => $price, 'timestamp' => $timestamp]],
                    'action' => 'added'
                ];
                $_SESSION['notification'] = 'Product added successfully';
                header("Location: /viewStock?showReceipt=true");
                exit;
            } catch (Exception $e) {
                $_SESSION['notification'] = 'Error adding product: ' . $e->getMessage();
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

    public function updateStock() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

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

                    if (empty($productId) || empty($newPrice) || empty($newQuantity)) {
                        throw new Exception("All fields are required for product entry #" . ($index + 1));
                    }

                    if (!is_numeric($productId) || $productId <= 0 || floor($productId) != $productId) {
                        throw new Exception("Invalid product ID for product entry #" . ($index + 1));
                    }

                    if (!is_numeric($newPrice) || $newPrice <= 0) {
                        throw new Exception("Price must be a positive number for product entry #" . ($index + 1));
                    }

                    if (!is_numeric($newQuantity) || floor($newQuantity) != $newQuantity) {
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

                    // Store receipt details
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

                // Store receipt details in session
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
                $_SESSION['notification'] = 'Error updating products: ' . $e->getMessage();
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
}
?>