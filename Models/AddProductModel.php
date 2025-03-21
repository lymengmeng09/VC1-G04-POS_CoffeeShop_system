<?php
require_once 'Database/Database.php';
class AddProductModel {
    private $conn;

    function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

   
    function getCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getProducts() {
        $query = "SELECT * FROM products ORDER BY product_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to create a new product
    function createProduct($data) {
        $stmt = $this->conn->prepare("INSERT INTO products (product_name, price, image_url, category_id)
                  VALUES (:product_name, :price, :image_url, :category_id)");
        return $stmt->execute([
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'image_url' => $data['image_url'],
            'category_id' => $data['category_id']
        ]);
    }
}

