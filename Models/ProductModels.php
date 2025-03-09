<?php
require_once "Database/Database.php";

class ProductModel {
    private $conn;
    private $table = "products";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllProducts() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $price, $quantity, $image) {
        $query = "INSERT INTO " . $this->table . " (name, price, quantity, image) VALUES (:name, :price, :quantity, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":image", $image);
        return $stmt->execute();
    }

    public function updateProduct($id, $name, $price, $quantity, $image) {
        $query = "UPDATE " . $this->table . " SET name = :name, price = :price, quantity = :quantity, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":image", $image);
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
 
    
}
