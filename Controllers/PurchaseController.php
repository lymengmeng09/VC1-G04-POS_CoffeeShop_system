<?php
require_once 'models/PurchaseModel.php';
class PurchaseController extends BaseController {
    private $purchaseModel;

    public function __construct() {
        $this->purchaseModel = new PurchaseModel();
    }

    public function index() {
        $purchases = $this->purchaseModel->getPurchaseHistory();
        $totalRevenue = $this->purchaseModel->calculateTotalRevenue($purchases);
        $totalTransactions = count($purchases);
        $averagePurchase = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Load the view and pass the data
        $this->view('purchase-report/purchase-history', [
            'purchases' => $purchases,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'averagePurchase' => $averagePurchase
        ]);
    }
}