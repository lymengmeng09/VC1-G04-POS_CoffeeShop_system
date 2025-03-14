<?php
require_once 'Models/AddProductModel.php';
require_once 'BaseController.php';

class AddProductController extends BaseController
{   
    function __construct()
    {
        $this->model =  new AddProductModel();
        $this->checkAuth();
    }
    // Authentication check method
    private function checkAuth() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // If user is not logged in, redirect to login page
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }
    private $model;
 
    function index()
    {
        $Product = $this->model->getProducts();
        $this->view('products/list-product',['products' => $Product]);
    }

    function create()
    {
        $this->view('products/create.php');
    }
}