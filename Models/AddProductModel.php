<?php
require_once 'Database/Database.php';
class AddProductModel {
    private $conn;

    function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

   
    function getProducts() {
        $query = "SELECT * FROM products ORDER BY product_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to create a new product
    function createProduct($data) {
        $query = "INSERT INTO products (product_name, price, image_url, category, category_id)
                  VALUES (:product_name, :price, :image_url, :category, :category_id)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':product_name', $data['product_name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':category_id', $data['category_id']);

        // Execute the statement
        return $stmt->execute();
    }
}

