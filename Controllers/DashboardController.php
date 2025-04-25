<?php
require_once "BaseController.php";
require_once "Models/AddProductModel.php";

class DashboardController extends BaseController
{
    private $model;

    public function __construct()
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
        // Get filter and date range from query parameters, default to today
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        // Set date range based on filter
        switch ($filter) {
            case 'this_week':
                $startDate = date('Y-m-d', strtotime('monday this week'));
                $endDate = date('Y-m-d', strtotime('sunday this week'));
                break;
            case 'this_month':
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-t');
                break;
            case 'custom':
                // Validate custom dates
                if (!$startDate || !$endDate || !strtotime($startDate) || !strtotime($endDate)) {
                    $filter = 'today';
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d');
                } elseif (strtotime($startDate) > strtotime($endDate)) {
                    $startDate = $endDate;
                }
                break;
            case 'today':
            default:
                $filter = 'today';
                $startDate = date('Y-m-d');
                $endDate = date('Y-m-d');
                break;
        }

        // Fetch data
        $topProducts = $this->model->getTopSellingProducts($startDate, $endDate);
        $monthlySales = $this->model->getMonthlySales(date('Y'));
        $income = $this->model->getTotalIncome($startDate, $endDate);
        $expenses = $this->model->getTotalPurchaseExpenses($startDate, $endDate);
        $profits = $income - $expenses;

        $this->view('dashboard/dashboard', [
            'topProducts' => $topProducts,
            'monthlySales' => $monthlySales,
            'income' => $income,
            'expenses' => $expenses,
            'profits' => $profits,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'filter' => $filter
        ]);
    }
}