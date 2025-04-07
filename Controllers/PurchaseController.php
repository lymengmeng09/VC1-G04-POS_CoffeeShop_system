<?php
require_once 'models/PurchaseModel.php';

class PurchaseController extends BaseController {
    private $purchaseModel;

    public function __construct() {
        $this->purchaseModel = new PurchaseModel();
    }

    public function index() {
        // Get filter parameters
        $startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : null;
        $endDate = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : null;
        
        // Get purchases based on filters
        if ($startDate && $endDate) {
            $purchases = $this->purchaseModel->getPurchasesByDateRange($startDate, $endDate);
        } else {
            $purchases = $this->purchaseModel->getPurchaseHistory();
        }
        
        $totalRevenue = $this->purchaseModel->calculateTotalRevenue($purchases);
        $totalTransactions = count($purchases);
        $averagePurchase = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Load the view and pass the data
        $this->view('purchase-report/purchase-history', [
            'purchases' => $purchases,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'averagePurchase' => $averagePurchase,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'showStatus' => true // Set to true to show status column
        ]);
    }
    
    public function exportCsv() {
        // Get filter parameters
        $startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : null;
        $endDate = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : null;
        
        // Get purchases based on filters
        if ($startDate && $endDate) {
            $purchases = $this->purchaseModel->getPurchasesByDateRange($startDate, $endDate);
        } else {
            $purchases = $this->purchaseModel->getPurchaseHistory();
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="purchase_history.csv"');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['ID', 'Product Name', 'Quantity', 'Price', 'Date', 'Total Cost', 'Status']);
        
        // Add data rows
        foreach ($purchases as $purchase) {
            fputcsv($output, [
                $purchase['product_id'],
                $purchase['product_name'],
                $purchase['quantity'],
                $purchase['price'],
                $purchase['purchase_date'],
                $purchase['total_cost'],
                $purchase['status']
            ]);
        }
        
        // Close output stream
        fclose($output);
        exit;
    }
}

