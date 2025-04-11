<?php
require_once 'Models/AddProductModel.php';
require_once 'BaseController.php';

class HistoryController extends BaseController
{
    private $model;

    function __construct()
    {
        $this->model = new AddProductModel();
        // No authentication check needed
    }

    public function index()
    {
        $orders = $this->model->getOrderHistory();
        $this->view('order/order-history', ['orders' => $orders]);
    }
}