<?php
require_once 'Database/Database.php';

class PurchaseModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getPurchaseHistory() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    pi.id,
                    pi.purchase_id,
                    pi.product_id,
                    s.name AS product_name,
                    pi.quantity,
                    pi.unit_price AS price,
                    p.purchase_date,
                    pi.total_price AS total_cost,
                    p.status
                FROM 
                    purchase_items pi
                INNER JOIN 
                    purchases p ON pi.purchase_id = p.id
                INNER JOIN
                    stocks s ON pi.product_id = s.id
                ORDER BY 
                    p.purchase_date DESC, pi.id DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching purchase history: " . $e->getMessage());
            return [];
        }
    }

    public function getPurchasesByDateRange($startDate, $endDate) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    pi.id,
                    pi.purchase_id,
                    pi.product_id,
                    s.name AS product_name,
                    pi.quantity,
                    pi.unit_price AS price,
                    p.purchase_date,
                    pi.total_price AS total_cost,
                    p.status
                FROM 
                    purchase_items pi
                INNER JOIN 
                    purchases p ON pi.purchase_id = p.id
                INNER JOIN
                    stocks s ON pi.product_id = s.id
                WHERE 
                    p.purchase_date BETWEEN :start_date AND :end_date
                ORDER BY 
                    p.purchase_date DESC, pi.id DESC
            ");
            $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching purchase history by date range: " . $e->getMessage());
            return [];
        }
    }

    public function calculateTotalRevenue($purchases) {
        $totalRevenue = 0;
        foreach ($purchases as $purchase) {
            $totalRevenue += $purchase['total_cost'];
        }
        return $totalRevenue;
    }
}
