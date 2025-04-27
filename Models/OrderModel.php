<?php
require_once 'Database/Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        // Assuming Database is your connection class
        $this->db = new Database();
    }

    // Add this method to the OrderModel class
    public function getAllOrderItems() {
        try {
            $sql = "SELECT * FROM order_items";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Returns data as an associative array
        } catch (PDOException $e) {
            // Handle error
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
