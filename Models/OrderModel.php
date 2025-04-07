<?php
require_once 'Database/Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Save a new order and return its ID
    public function saveOrder($customerId, $totalAmount) {
        $orderNumber = 'ORD-' . time(); // Generate a unique order number
        $sql = "INSERT INTO orders (customer_id, order_number, order_date, total_amount, payment_status, created_at, updated_at) 
                VALUES (:customer_id, :order_number, NOW(), :total_amount, 'completed', NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customerId,
            ':order_number' => $orderNumber,
            ':total_amount' => $totalAmount
        ]);
        return $this->db->lastInsertId();
    }

    // Save order items
    public function saveOrderItems($orderId, $items) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
                VALUES (:order_id, :product_id, :quantity, :price, :subtotal)";
        $stmt = $this->db->prepare($sql);

        foreach ($items as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $stmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price'],
                ':subtotal' => $subtotal
            ]);
        }
        return true;
    }

    // Get all orders for a customer (for history page)
    public function getOrdersByCustomer($customerId) {
        $sql = "SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY order_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':customer_id', $customerId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get order items for a specific order
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.product_name 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}