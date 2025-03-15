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
        try {
            $query = "INSERT INTO " . $this->table . " (name, price, quantity, image) VALUES (:name, :price, :quantity, :image)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindParam(":image", $image, PDO::PARAM_STR | PDO::PARAM_NULL);
            if (!$stmt->execute()) {
                throw new Exception('Database error: ' . implode(', ', $stmt->errorInfo()));
            }
            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to add product: ' . $e->getMessage());
        }
    }
    
    public function updateProduct($id, $name, $price, $quantity) {
        try {
            $query = "UPDATE " . $this->table . " SET name = :name, price = :price, quantity = :quantity WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new Exception('Database error: ' . implode(', ', $stmt->errorInfo()));
            }
            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to update product: ' . $e->getMessage());
        }
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