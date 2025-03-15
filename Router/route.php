<?php
require_once "Router.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/LoginController.php";
require_once "Controllers/ListUserController.php";
require_once "Controllers/AddProductController.php";
require_once "Controllers/ViewStockController.php";
require_once "Controllers/SettingController.php";
require_once "Controllers/UserRoleController.php";
require_once "Middleware/AuthMiddleware.php";
require_once "Controllers/NotificationController.php";

$route = new Router();

// Product management routes
$route->get("/viewStock", [ViewStockController::class, 'index'])
      ->middleware("/viewStock", AuthMiddleware::class, 'view_products');
      $route->post("/add-product", [ViewStockController::class, 'add']);
$route->post("/update-stock", [ViewStockController::class, 'updateStock']);

// Login routes
$route->get("/login", [LoginController::class, 'index']);
$route->get("/login/logout", [LoginController::class, 'logout']);

 
$route->get("/", [DashboardController::class, 'index'])
      ->middleware("/", AuthMiddleware::class, 'view_dashboard');
 

//setting
 

// User management routes (admin only for create)
$route->get("/list-users", [ListUserController::class, 'index'])
      ->middleware("/list-users", AuthMiddleware::class, 'view_users');

// Only admins can create users
$route->get("/users/create", [ListUserController::class, 'create'])
      ->middleware("/users/create", AuthMiddleware::class, 'create_users');
$route->post("/users/store", [ListUserController::class, 'store'])
      ->middleware("/users/store", AuthMiddleware::class, 'create_users');

// Settings routes (admin only)
$route->get("/setting", [SettingController::class, 'index'])
      ->middleware("/setting", AuthMiddleware::class, 'access_settings');
$route->get("/setting/UserRole", [UserRoleController::class, 'index'])
      ->middleware("/setting/UserRole", AuthMiddleware::class, 'access_settings');


// products
$route->get("/products", [AddProductController::class, 'index']);
$route->get("/products/create", [AddProductController::class, 'create']);
$route->route();