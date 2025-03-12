<?php
require "Models/StockModels.php";

class ProductController {
    private $productModel;
    private $uploadDir = "uploads/"; // Directory to store uploaded images

    public function __construct() {
        $this->productModel = new ProductModel();
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true); // Create directory if it doesn't exist
        }
    }

    public function index() {
        $products = $this->productModel->getAllProducts();
        include "views/stock-products/viewStock.php";
    }

    public function add() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Check if products array exists
                $products = $_POST['products'] ?? [];
                $files = $_FILES['products'] ?? [];

                if (empty($products)) {
                    throw new Exception('No products submitted');
                }

                foreach ($products as $index => $product) {
                    // Get product data with fallback to prevent undefined key errors
                    $name = $product['name'] ?? '';
                    $price = $product['price'] ?? '';
                    $quantity = $product['quantity'] ?? '';

                    // Validate inputs
                    if (empty($name) || empty($price) || empty($quantity)) {
                        throw new Exception('All fields are required for product #' . ($index + 1));
                    }

                    // Handle file upload
                    $image = null;
                    if (isset($files['image']) && isset($files['image']['error'][$index]) && $files['image']['error'][$index] == UPLOAD_ERR_OK) {
                        $imageName = basename($files['image']['name'][$index]);
                        $targetFile = $this->uploadDir . uniqid() . '_' . $imageName; // Unique filename to avoid conflicts
                        if (move_uploaded_file($files['image']['tmp_name'][$index], $targetFile)) {
                            $image = $targetFile;
                        } else {
                            throw new Exception('Failed to upload image for product #' . ($index + 1));
                        }
                    }

                    // Add product to database
                    $this->productModel->addProduct($name, $price, $quantity, $image);
                }

                $_SESSION['notification'] = 'Products added successfully';
                header("Location: /viewStock");
                exit;
            } catch (Exception $e) {
                $_SESSION['notification'] = 'Error adding products: ' . $e->getMessage();
                header("Location: /viewStock");
                exit;
            }
        }
        header("Location: /viewStock");
        exit;
    }

    public function updateStock() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $productId = $_POST["product_id"];
            $newPrice = $_POST["price"];
            $newQuantity = $_POST["quantity"];

            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                die("Product not found");
            }

            // Update the product with new price and quantity
            $this->productModel->updateProduct($productId, $product['name'], $newPrice, $newQuantity);

            // Check for out of stock
            if ($newQuantity == 0) {
                session_start();
                $_SESSION['notification'] = "Product '{$product['name']}' is now Out of Stock.";
            }

            header("Location: /viewStock");
            exit;
        }
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