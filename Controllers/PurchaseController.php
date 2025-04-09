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
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $isAjax = isset($_GET['ajax']) && $_GET['ajax'] == '1';

        // Get purchases based on filters
        if ($startDate && $endDate) {
            $purchases = $this->purchaseModel->getPurchasesByDateRange($startDate, $endDate);
        } else {
            $purchases = $this->purchaseModel->getPurchaseHistory();
        }

        // Filter by product name if search term is provided
        if (!empty($search)) {
            $purchases = array_filter($purchases, function($purchase) use ($search) {
                return stripos($purchase['product_name'], $search) !== false;
            });
        }

        if ($isAjax) {
            // Return JSON for AJAX requests
            header('Content-Type: application/json');
            echo json_encode(array_values($purchases)); // Re-index array after filtering
            exit;
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
            'showStatus' => true
        ]);
    }
}