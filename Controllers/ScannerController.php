<?php
require_once 'models/ScannerModel.php';
require_once 'controllers/BaseController.php';

class ScannerController extends BaseController {
    private $scannerModel;

    public function __construct() {
        $this->scannerModel = new ScannerModel();
    }

    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function index() {
        $data = [
            'title' => 'Barcode Scanner'
        ];
        $this->view('stock-products/scanner', $data);
    }

    public function processBarcode() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['barcode'])) {
            $barcode = $_GET['barcode'];
            
            // For debugging - log the barcode being searched
            error_log("Searching for barcode: " . $barcode);
            
            // Get product details from the database
            $product = $this->scannerModel->getProductByBarcode($barcode);
            
            if ($product) {
                // For debugging - log the product found
                error_log("Product found: " . json_encode($product));
                
                $this->jsonResponse([
                    'success' => true,
                    'product' => $product
                ]);
            } else {
                // For debugging - log that no product was found
                error_log("No product found for barcode: " . $barcode);
                
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Product not found for barcode: ' . $barcode
                ]);
            }
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    // Method to update stock quantity (optional)
    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barcode']) && isset($_POST['quantity'])) {
            $barcode = $_POST['barcode'];
            $quantityChange = (int)$_POST['quantity'];
            
            $success = $this->scannerModel->updateStock($barcode, $quantityChange);
            
            if ($success) {
                // Get updated product details
                $updatedProduct = $this->scannerModel->getProductByBarcode($barcode);
                
                $this->jsonResponse([
                    'success' => true,
                    'product' => $updatedProduct,
                    'message' => 'Stock updated successfully'
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Failed to update stock'
                ]);
            }
        } else {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    // Restore the checkScanner method to fix the error
    public function checkScanner() {
        $data = [
            'title' => 'Barcode Scanner',
            'message' => 'Barcode scanner page loaded successfully'
        ];
        
        $this->view('stock-products/scanner', $data);
    }

    // If Scanner() is being used as a default action, redirect it to index()
    public function Scanner() {
        $this->index();
    }
}