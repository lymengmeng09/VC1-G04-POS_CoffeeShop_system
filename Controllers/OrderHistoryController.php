<?php
require_once 'BaseController.php';
require_once 'Models/AddProductModel.php';

class OrderHistoryController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new AddProductModel();
        $this->checkAuth();
    }

    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }

    public function index()
    {
        $orders = $this->model->getOrderHistory();
        $this->view('order-history/index', ['orders' => $orders]);
    }

    public function details($orderId)
    {
        $order = $this->model->getOrderDetails($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Order not found!';
            $this->redirect('/order-history');
            return;
        }
        $this->view('order-history/details', ['order' => $order]);
    }
}
