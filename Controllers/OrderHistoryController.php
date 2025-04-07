<?php
require_once 'Models/OrderModel.php';
class OrderHistoryController extends BaseController {
    protected $orderModel;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
    }
    
    // Display order history page
    public function index() {
        // Get filter parameters from request
        $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        
        // Get orders
        $order = $this->orderModel->getOrders($searchQuery, $filter, $startDate, $endDate);
        
        // Load view with data
        $this->view('order/order-history', [
            'orders' => $order,
            'searchQuery' => $searchQuery,
            'filter' => $filter,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'title' => 'Order History'
        ]);
    }
    
    // Display order details page
    public function details($id = null) {
        if ($id === null) {
            $this->redirect('orders');
        }
        
        // Get order details
        $order = $this->orderModel->getOrderById($id);
        
        // Get order items
        $orderItems = $this->orderModel->getOrderItems($id);
        
        // Load view with data
        $this->view('orders/details', [
            'order' => $order,
            'orderItems' => $orderItems,
            'title' => 'Order Details: ' . $id
        ]);
    }
    
    // Handle AJAX search requests
    public function search() {
        // Get filter parameters from request
        $searchQuery = isset($_GET['term']) ? $_GET['term'] : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        
        // Get filtered orders
        $orders = $this->orderModel->getOrders($searchQuery, $filter, $startDate, $endDate);
        
        // Return JSON response
        $this->json($orders);
    }
    
    // Update order status
    public function updateStatus() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('orders');
        }
        
        // Get order ID and status from POST data
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        
        if ($id === null || $status === null) {
            $this->json(['success' => false, 'message' => 'Invalid request']);
        }
        
        // Update status
        $success = $this->orderModel->updateStatus($id, $status);
        
        // Return JSON response
        $this->json(['success' => $success]);
    }
}

