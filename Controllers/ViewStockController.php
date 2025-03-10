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
}
?>
