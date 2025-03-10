<?php
require "Models/ProductModels.php";

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
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
            $image = $_POST["image"];
            $this->productModel->addProduct($name, $price, $quantity, $image);
            header("Location: /viewStock");
            exit;
        }
        include "views/stock-products/add-product.php";
    }

    public function edit($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            die("Product not found");
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $price = $_POST["price"];
            $quantity = $_POST["quantity"];
            $image = $_POST["image"];
            $this->productModel->updateProduct($id, $name, $price, $quantity, $image);
            header("Location: /viewStock");
            exit;
        }
        include "views/stock-products/edit-product.php";
    }

    public function delete($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->productModel->deleteProduct($id);
            header("Location: /viewStock");
            exit;
        }
        include "views/stock-products/delete-product.php";
    }




    // New method for top-selling products dashboard
    public function dashboard() {
        // Check if a time range filter is submitted via form
        $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;

        // Fetch top-selling products from the model
        $topProducts = $this->productModel->getTopSellingProducts($startDate, $endDate);

        // Load the dashboard view and pass the data
        include "views/dashboard.php"; // Use your existing dashboard.php
    }


}



?>
