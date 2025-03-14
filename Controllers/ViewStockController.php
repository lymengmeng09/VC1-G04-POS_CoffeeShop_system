<?php
require "Models/StockModels.php";

require_once "BaseController.php";

class ProductController extends BaseController{

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

                $_SESSION['notification'] = 'Product added successfully';
                header("Location: /viewStock");
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

                // Expect arrays for product_id, price, and quantity
                $productIds = $_POST["product_id"] ?? [];
                $newPrices = $_POST["price"] ?? [];
                $newQuantities = $_POST["quantity"] ?? [];


                // Validate that arrays are not empty and have the same length
                if (empty($productIds) || count($productIds) !== count($newPrices) || count($productIds) !== count($newQuantities)) {
                    throw new Exception('Invalid form data: All fields are required for each product');
                }

                $successMessages = [];
                $outOfStockMessages = [];

                // Process each product
                foreach ($productIds as $index => $productId) {
                    $newPrice = $newPrices[$index];
                    $newQuantity = $newQuantities[$index];

                    // Validate inputs for each product
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

                    // Sum the existing quantity with the new quantity
                    $existingQuantity = (int)$product['quantity'];
                    $updatedQuantity = $existingQuantity + (int)$newQuantity;

                    // Validate updated quantity
                    if ($updatedQuantity < 0) {
                        throw new Exception("Cannot reduce quantity below 0 for product '{$product['name']}'. Current quantity is $existingQuantity.");
                    }

                    // Update the product with new price and summed quantity
                    $result = $this->productModel->updateProduct($productId, $product['name'], (float)$newPrice, $updatedQuantity);
                    if (!$result) {
                        throw new Exception("Failed to update product '{$product['name']}' in database");
                    }

                    // Check for out of stock
                    if ($updatedQuantity == 0) {
                        $outOfStockMessages[] = "Product '{$product['name']}' is now Out of Stock.";
                    } else {
                        $successMessages[] = "Product '{$product['name']}' updated successfully. New quantity: $updatedQuantity.";
                    }
                }

                // Combine all messages into a single notification
                $notification = [];
                if (!empty($successMessages)) {
                    $notification[] = implode(' ', $successMessages);
                }
                if (!empty($outOfStockMessages)) {
                    $notification[] = implode(' ', $outOfStockMessages);
                }
                $_SESSION['notification'] = implode(' ', $notification);

                header("Location: /viewStock");
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
}
?>
