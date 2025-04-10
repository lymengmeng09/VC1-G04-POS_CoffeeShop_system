<?php
require_once 'Database/Database.php';

class ScannerModel {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function getProductByBarcode($barcode) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barcode'])) {
            $barcode = $_POST['barcode'];
        
            // Fetch the current product's details
            $sql = "SELECT quantity FROM stocks WHERE barcode = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $barcode);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($row = $result->fetch_assoc()) {
                $newQuantity = $row['quantity'] + 1; // Increase by 1
        
                // Update the product quantity in the database
                $updateSql = "UPDATE products SET quantity = ? WHERE barcode = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("is", $newQuantity, $barcode);
                $updateStmt->execute();
        
                echo json_encode(['success' => true, 'message' => 'Quantity updated']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    }
//     public function updateStock($barcode, $quantityChange) {
//         // Update based on your actual table structure
//         // Note: Replace 'products' with your actual table name if different
//         $query = "UPDATE products 
//                   SET quantity = quantity + :quantityChange 
//                   WHERE barcode = :barcode";
        
//         $stmt = $this->db->prepare($query);
//         $stmt->bindParam(':barcode', $barcode);
//         $stmt->bindParam(':quantityChange', $quantityChange, PDO::PARAM_INT);
//         return $stmt->execute();
//     }
}