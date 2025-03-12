<?php
require_once "Router.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/LoginController.php";
require_once "Controllers/ListUserController.php";
require_once "Controllers/ViewStockController.php";
require_once "Controllers/SettingController.php";
require_once "Controllers/UserRoleController.php";
require_once "Controllers/NotificationController.php";




$route = new Router();

// Product management routes
$route->get("/", [DashboardController::class, 'index']);
$route->get("/viewStock", [ProductController::class, 'index']);
$route->get("/add-product", [ProductController::class, 'add']); // GET: redirect back to viewStock
$route->post("/add-product", [ProductController::class, 'add']); // POST: handle add submission
$route->get("/update-stock", [ProductController::class, 'updateStock']); // GET: redirect back to viewStock
$route->post("/update-stock", [ProductController::class, 'updateStock']); // POST: handle stock update

// Dashboard route
$route->get("/dashboard", [ProductController::class, 'dashboard']);
$route->post("/dashboard", [ProductController::class, 'dashboard']); // Add POST for form submission

// Login routes
$route->get("/login", [LoginController::class, 'index']);
$route->get("/login/logout", [LoginController::class, 'logout']);
$route->get("/login/register", [LoginController::class, 'register']);

// User routes
$route->get("/list-users", [ListUserController::class, 'index']);


//setting
$route->get("/setting", [SettingController::class, 'index']);
$route->get("/setting/UserRole", [UserRoleController::class, 'index']);
$route->get("/setting/notification", [NotificationController::class, 'notification']);

$route->route();
