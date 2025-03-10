<?php
require_once "./Models/UsersModel.php";
class ListUserController extends BaseController {
    private $model;
    public function __construct() {
        // Check if user is logged in
        $this->checkAuth();
        $this -> model = new UserModel();
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
        $users = $this->model->getUsers();
        $this->view('users/lists', ['users'=>$users]);
    }
}
