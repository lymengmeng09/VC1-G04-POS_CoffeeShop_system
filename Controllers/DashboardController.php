<?php
require_once "BaseController.php";
require_once 'Models/AddProductModel.php';
class DashboardController extends BaseController
{
    private $model;

    // Constructor to initialize the model and check authentication
    public function __construct()
    {
        $this->model = new AddProductModel(); // Initialize the model
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
    public function index()
    {
        $topProducts = $this->model->getTopSellingProducts();
        $monthlySales = $this->model->getMonthlySales(date('Y')); // Current year
        $income = $this->model->getTotalIncome();
        $expenses = 1245.00; // Replace with dynamic calculation
        $profits = $income - $expenses;
    
        $this->view('dashboard/dashboard', [
            'topProducts' => $topProducts,
            'monthlySales' => $monthlySales,
            'income' => $income,
            'expenses' => $expenses,
            'profits' => $profits
        ]);
    }
}
