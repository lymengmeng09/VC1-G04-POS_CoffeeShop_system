<?php
require_once 'Database/Database.php';

class ScannerModel {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function getProductByBarcode($barcode) {
        $query = "SELECT * FROM stocks WHERE barcode = :barcode";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStock($barcode, $quantityChange) {
        $query = "UPDATE products 
                  SET quantity = quantity + :quantityChange 
                  WHERE barcode = :barcode";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':quantityChange', $quantityChange, PDO::PARAM_INT);
        return $stmt->execute();
    }
}