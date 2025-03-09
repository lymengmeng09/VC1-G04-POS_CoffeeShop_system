<?php
require_once "BaseController.php";
class DashboardController extends BaseController {
    public function index() {
        $this->view('dashboard/dashboard');
    }
}