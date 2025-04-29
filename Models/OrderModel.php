<?php
require_once 'Database/Database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllOrderItems() {
        try {
            $sql = "SELECT * FROM order_items";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching order items: " . $e->getMessage());
        }
    }

    public function getOrdersByDateRange($startDate, $endDate) {
        try {
            $query = 'SELECT order_id, total_amount, item_count, created_at, status 
                     FROM orders WHERE 1=1';
            $params = [];

            if ($startDate) {
                $query .= ' AND created_at >= :start_date';
                $params[':start_date'] = $startDate;
            }
            if ($endDate) {
                $query .= ' AND created_at <= :end_date';
                $params[':end_date'] = $endDate . ' 23:59:59';
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching orders: " . $e->getMessage());
        }
    }

    public function getFilteredOrders(array $filters, $limit = 50, $offset = 0) {
        try {
            $query = 'SELECT order_id, total_amount, item_count, created_at, status 
                     FROM orders WHERE 1=1';
            $params = [];
    
            // Validate status
            $validStatuses = ['pending', 'completed', 'cancelled']; // Example
            if (!empty($filters['status']) && in_array($filters['status'], $validStatuses)) {
                $query .= ' AND status = :status';
                $params[':status'] = $filters['status'];
            }
    
            // Add date range, amount filters, and search term as before
            // ...
    
            // Add pagination
            $query .= ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset';
            $params[':limit'] = (int)$limit;
            $params[':offset'] = (int)$offset;
    
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching filtered orders: " . $e->getMessage()); // Log detailed error
            throw new Exception("Unable to fetch orders. Please try again later.");
        }
    }
    public function getOrderDetails($orderId) {
        try {
            // Main order details
            $query = 'SELECT order_id, total_amount, item_count, created_at, status 
                     FROM orders WHERE order_id = :order_id';
            $stmt = $this->db->prepare($query);
            $stmt->execute([':order_id' => $orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                return false;
            }

            // Get order items
            $itemsQuery = 'SELECT oi.*, p.product_name 
                         FROM order_items oi 
                         LEFT JOIN products p ON oi.product_id = p.product_id 
                         WHERE oi.order_id = :order_id';
            $itemsStmt = $this->db->prepare($itemsQuery);
            $itemsStmt->execute([':order_id' => $orderId]);
            $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

            return $order;
        } catch (PDOException $e) {
            throw new Exception("Error fetching order details: " . $e->getMessage());
        }
    }
    public function getOrderByDateRange($startDate, $endDate) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    oi.id,
                    oi.order_id,
                    oi.product_id,
                    oi.quantity,
                    oi.unit_price AS price,
                    o.order_date,
                    oi.total_price AS total_cost,
                    o.status
                FROM 
                    order_items oi
                INNER JOIN 
                    orders o ON oi.order_id = o.id
                INNER JOIN
                    products p ON oi.product_id = p.id
                WHERE 
                    o.order_date BETWEEN :start_date AND :end_date
                ORDER BY 
                    o.order_date DESC, oi.id DESC
            ");
            $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching order history by date range: " . $e->getMessage());
            return [];
        }
    }
    
}