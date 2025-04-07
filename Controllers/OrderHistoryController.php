<?php
require_once 'Models/OrderModel.php';

class OrderHistoryController extends BaseController {
    protected $orderModel;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
    }
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $customerId = $_SESSION['user']['id'];
        $orders = $this->orderModel->getOrdersByCustomer($customerId);
        
        foreach ($orders as &$order) {
            $order['items'] = $this->orderModel->getOrderItems($order['order_id']);
        }

        $this->view('orders/history', [
            'orders' => $orders,
            'title' => 'Order History'
        ]);
    }
    
    public function saveOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            $this->json(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $customerId = $_SESSION['user']['id'];
        $items = $data['items'] ?? [];
        $total = $data['total'] ?? 0;

        if (empty($items)) {
            $this->json(['success' => false, 'message' => 'No items in order']);
            return;
        }

        try {
            $orderId = $this->orderModel->saveOrder($customerId, $total);
            $this->orderModel->saveOrderItems($orderId, $items);
            
            $this->json([
                'success' => true,
                'order_id' => $orderId,
                'message' => 'Order saved successfully'
            ]);
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Error saving order: ' . $e->getMessage()
            ]);
        }
    }
    
    public function show($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $customerId = $_SESSION['user']['id'];
        $order = $this->orderModel->getOrderById($id);
        
        if (!$order || $order['customer_id'] != $customerId) {
            $this->redirect('/order-history');
            return;
        }

        $orderItems = $this->orderModel->getOrderItems($id);
        
        $this->view('orders/details', [
            'order' => $order,
            'orderItems' => $orderItems,
            'title' => 'Order Details'
        ]);
    }
    
    public function exportCsv() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $customerId = $_SESSION['user']['id'];
        $orders = $this->orderModel->getOrdersByCustomer($customerId);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="order_history_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Order Number', 'Date', 'Total Amount', 'Status']);
        
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['order_number'],
                $order['order_date'],
                $order['total_amount'],
                $order['payment_status']
            ]);
        }
        
        fclose($output);
        exit();
    }
}