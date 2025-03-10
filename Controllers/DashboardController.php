<?php
require_once "BaseController.php";
class DashboardController extends BaseController {
    public function __construct() {
        // Check if user is logged in
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
    public function index() {
        $this->view('dashboard/dashboard');
    }
}