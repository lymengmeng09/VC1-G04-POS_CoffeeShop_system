<?php
require "Models/ProductModels.php";

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
            $name = $_POST["name"];
            $price = $_POST["price"];
            $quantity = $_POST["quantity"];

            // Handle file upload
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $imageName = basename($_FILES['image']['name']);
                $targetFile = $this->uploadDir . uniqid() . '_' . $imageName; // Unique filename to avoid conflicts
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = $targetFile;
                } else {
                    die("Failed to upload image.");
                }
            }

            $this->productModel->addProduct($name, $price, $quantity, $image);
            header("Location: /viewStock");
            exit;
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