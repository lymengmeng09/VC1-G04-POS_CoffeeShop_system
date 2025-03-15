<?php
require "Models/ProductModels.php";

class ViewStockController {
    private $productModel;
    private $uploadDir = "uploads/";
    private const ALLOWED_FILE_TYPES = ['jpg', 'jpeg', 'png', 'gif'];
    private const MAX_FILE_SIZE = 5000000; // 5MB

    public function __construct() {
        try {
            $this->startSession();
            $this->productModel = new ProductModel();
            // Ensure upload directory is relative to public directory
            $this->uploadDir = __DIR__ . '/../../public/uploads/';
            if (!file_exists($this->uploadDir)) {
                if (!mkdir($this->uploadDir, 0755, true)) {
                    throw new Exception("Failed to create upload directory");
                }
            }
        } catch (Exception $e) {
            error_log("Error in ViewStockController constructor: " . $e->getMessage());
            throw $e;
        }
    }

    public function index() {
        try {
            $products = $this->productModel->getAllProducts();
            include __DIR__ . "/../Views/stock-products/viewStock.php";
        } catch (Exception $e) {
            error_log("Error in index: " . $e->getMessage());
            $this->startSession();
            $_SESSION['error'] = "Failed to load products: " . $e->getMessage();
            include __DIR__ . "/../Views/stock-products/viewStock.php";
        }
    }

    public function add() {
        try {
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                header("Location: /viewStock");
                exit;
            }

            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("Invalid CSRF token");
            }

            // Validate inputs
            if (empty($_POST["name"]) || !isset($_POST["price"]) || !isset($_POST["quantity"])) {
                throw new Exception("All fields are required");
            }

            $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
            $quantity = filter_var($_POST["quantity"], FILTER_VALIDATE_INT);

            if ($price === false || $quantity === false) {
                throw new Exception("Invalid price or quantity format");
            }

            $image = $this->handleFileUpload();

            $this->productModel->addProduct($name, $price, $quantity, $image);
            $this->startSession();
            $_SESSION['success'] = "Product added successfully";
            header("Location: /viewStock");
            exit;

        } catch (Exception $e) {
            $this->startSession();
            $_SESSION['error'] = $e->getMessage();
            header("Location: /viewStock");
            exit;
        }
    }

    public function updateStock() {
        try {
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                header("Location: /viewStock");
                exit;
            }

            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("Invalid CSRF token");
            }

            if (!isset($_POST["product_id"]) || !isset($_POST["price"]) || !isset($_POST["quantity"])) {
                throw new Exception("Missing required fields");
            }

            $productId = filter_var($_POST["product_id"], FILTER_VALIDATE_INT);
            $newPrice = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
            $newQuantity = filter_var($_POST["quantity"], FILTER_VALIDATE_INT);

            if ($productId === false || $newPrice === false || $newQuantity === false) {
                throw new Exception("Invalid input format");
            }

            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                throw new Exception("Product not found");
            }

            $this->productModel->updateProduct($productId, $product['name'], $newPrice, $newQuantity);

            $this->startSession();
            if ($newQuantity == 0) {
                $_SESSION['notification'] = "Product '{$product['name']}' is now Out of Stock.";
            } else {
                $_SESSION['success'] = "Product updated successfully";
            }

            header("Location: /viewStock");
            exit;

        } catch (Exception $e) {
            $this->startSession();
            $_SESSION['error'] = $e->getMessage();
            header("Location: /viewStock");
            exit;
        }
    }

    public function deleteProduct() {
        try {
            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                throw new Exception("Invalid request method");
            }

            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("Invalid CSRF token");
            }

            $productId = filter_var($_POST["id"], FILTER_VALIDATE_INT);
            if ($productId === false) {
                throw new Exception("Invalid product ID");
            }

            $this->productModel->deleteProduct($productId);
            $this->startSession();
            $_SESSION['success'] = "Product deleted successfully";
            header("Location: /viewStock");
            exit;

        } catch (Exception $e) {
            $this->startSession();
            $_SESSION['error'] = $e->getMessage();
            header("Location: /viewStock");
            exit;
        }
    }

    public function dashboard() {
        try {
            $startDate = filter_var($_POST['start_date'] ?? null, FILTER_SANITIZE_STRING);
            $endDate = filter_var($_POST['end_date'] ?? null, FILTER_SANITIZE_STRING);
            
            // Validate dates if provided
            if ($startDate && !$this->isValidDate($startDate)) {
                throw new Exception("Invalid start date format");
            }
            if ($endDate && !$this->isValidDate($endDate)) {
                throw new Exception("Invalid end date format");
            }

            $topProducts = $this->productModel->getTopSellingProducts($startDate, $endDate);
            include __DIR__ . "/../Views/dashboard.php";
        } catch (Exception $e) {
            error_log("Error in dashboard: " . $e->getMessage());
            $this->startSession();
            $_SESSION['error'] = "Failed to load dashboard: " . $e->getMessage();
            include __DIR__ . "/../Views/dashboard.php";
        }
    }

    private function handleFileUpload() {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $_FILES['image']['error']);
        }

        // Validate file size
        if ($_FILES['image']['size'] > self::MAX_FILE_SIZE) {
            throw new Exception("File size exceeds maximum limit of " . (self::MAX_FILE_SIZE / 1000000) . "MB");
        }

        // Validate file type
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExt, self::ALLOWED_FILE_TYPES)) {
            throw new Exception("Invalid file type. Allowed types: " . implode(', ', self::ALLOWED_FILE_TYPES));
        }

        // Sanitize filename
        $imageName = preg_replace('/[^A-Za-z0-9\.\-_]/', '', basename($_FILES['image']['name']));
        $targetFile = $this->uploadDir . uniqid() . '_' . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            throw new Exception("Failed to upload image");
        }

        // Return relative path from public directory
        return '/uploads/' . basename($targetFile);
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
?>