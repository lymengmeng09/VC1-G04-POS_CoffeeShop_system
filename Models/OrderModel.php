<?php
require_once 'Database/Database.php';

class OrderModel {
    private $conn;

    function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all orders with optional filtering
    public function getOrders($search = '', $filter = 'all', $startDate = '', $endDate = '') {
        $sql = "SELECT * FROM orders WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (order_number LIKE ? OR total_amount LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if ($filter !== 'all') {
            $sql .= " AND payment_status = ?";
            $params[] = $filter;
        }

        if (!empty($startDate) && !empty($endDate)) {
            $sql .= " AND order_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate . ' 23:59:59';
        }

        $sql .= " ORDER BY order_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single order by ID
    public function getOrderById($id) {
        $sql = "SELECT * FROM orders WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get order items for a specific order
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.order_item_id, oi.order_id, oi.product_id, oi.quantity, oi.price, oi.subtotal, p.product_name 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update order status
    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
}