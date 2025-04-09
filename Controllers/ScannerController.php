<?php
require_once 'models/ScannerModel.php';
require_once 'controllers/BaseController.php';

class ScannerController extends BaseController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ScannerModel();
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
            
            // Get product details
            $product = $this->productModel->getProductByBarcode($barcode);
            
            if ($product) {
                // Update stock (e.g., add 1 to quantity)
                $this->productModel->updateStock($barcode, 1); // You can adjust the quantity change as needed
                
                // Fetch updated product details
                $updatedProduct = $this->productModel->getProductByBarcode($barcode);
                
                $this->jsonResponse([
                    'success' => true,
                    'product' => $updatedProduct
                ]);
            } else {
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