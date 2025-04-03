<?php
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
                    po.product_id,
                    pr.prod_name AS product_name,
                    po.quantity,
                    po.unit_price AS price,
                    p.purchase_date,
                    (po.quantity * po.unit_price) AS total_cost
                FROM 
                    purchase_orders po
                INNER JOIN 
                    purchases p ON po.purchase_id = p.id
                INNER JOIN 
                    products pr ON po.product_id = pr.product_id
                ORDER BY 
                    p.purchase_date DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            error_log("Error fetching purchase history: " . $e->getMessage());
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