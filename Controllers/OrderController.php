<?php
require_once 'Models/AddProductModel.php';
require_once 'Models/OrderModel.php';
require_once 'BaseController.php';

class OrderController extends BaseController {
    private $model;

    function __construct() {
        $this->model = new OrderModel();
        $this->checkAuth();
    }

    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }

    function index() {
        $orders = $this->model->getOrders();
        $orderDetails = [];
    
        foreach ($orders as $order) {
            $items = $this->model->getOrderItems($order['order_id']);
            $orderDetails[] = [
                'order' => $order,
                'items' => $items
            ];
        }
    
        $this->view('orders/history', ['orderDetails' => $orderDetails]);
    }
}