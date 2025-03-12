<?php
require_once "Database/Database.php";

class ProductModel {
    private $conn;
    private $table = "stocks";

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
        $stmt->bindParam(":image", $image, PDO::PARAM_STR | PDO::PARAM_NULL); // Allow NULL if no image
        return $stmt->execute();
    }

    public function updateProduct($id, $name, $price, $quantity) {
        $query = "UPDATE " . $this->table . " SET name = :name, price = :price, quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        return $stmt->execute();
    }

    public function getTopSellingProducts($start_date = null, $end_date = null) {
        $query = "
            SELECT s.name AS product_name, SUM(oi.quantity) AS total_sold
            FROM " . $this->table . " s
            JOIN order_items oi ON s.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
        ";
        if ($start_date && $end_date) {
            $query .= " WHERE o.order_date BETWEEN :start_date AND :end_date";
        }
        $query .= " GROUP BY s.id, s.name ORDER BY total_sold DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        if ($start_date && $end_date) {
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>