<?php
require_once "Router.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/LoginController.php";
require_once "Controllers/ListUserController.php";
require_once "Controllers/AddProductController.php";
require_once "Controllers/ViewStockController.php";
require_once "Middleware/AuthMiddleware.php";
require_once "Controllers/PurchaseController.php";
require_once "Controllers/OrderHistoryController.php";

 
require_once "Middleware/AuthMiddleware.php";
require_once "Helpers/LanguageHelper.php";

// Initialize language system
LanguageHelper::init();

$route = new Router();

// Stock Product management routes
$route->get("/viewStock", [ViewStockController::class, 'index'])
      ->middleware("/viewStock", AuthMiddleware::class, 'view_products');
$route->post("/add-product", [ViewStockController::class, 'add']);
$route->get("/edit_product", [ViewStockController::class, 'edit']);
$route->put("/update_product", [ViewStockController::class, 'update']);
$route->delete("/delete_product/{id}", [ViewStockController::class, 'destroy']);


$route->post("/update-stock", [ViewStockController::class, 'updateStock']);
$route->get("/clearReceipt", [ViewStockController::class, 'clearReceipt']);



// Public routes (no middleware)
$route->post("/login", [LoginController::class, 'index']);
$route->post("/login/register", [LoginController::class, 'register']);
$route->get("/login/logout", [LoginController::class, 'logout']);

//Dashboard
$route->get("/", [DashboardController::class, 'index'])
      ->middleware("/", AuthMiddleware::class, 'view_dashboard');

// User management routes (admin only for create)
$route->get("/list-users", [ListUserController::class, 'index'])
      ->middleware("/list-users", AuthMiddleware::class, 'view_users');
$route->get('/edit-user', [ListUserController::class, 'edit']);
$route->post('/update-user', [ListUserController::class, 'update']);
$route->post('/resetpassword', [ListUserController::class, 'reset']);

// Only admins can create users
$route->get("/users/create", [ListUserController::class, 'create'])
      ->middleware("/users/create", AuthMiddleware::class, 'create_users');
$route->post("/users/store", [ListUserController::class, 'store'])
      ->middleware("/users/store", AuthMiddleware::class, 'create_users');
$route->delete("/users/delete", [ListUserController::class, 'destroy'])
      ->middleware("/users/delete}", AuthMiddleware::class, 'delete_users');
// Display the edit form
$route->get("/users/edit/{id}", [ListUserController::class, 'edit'])
      ->middleware("/users/edit/{id}", AuthMiddleware::class, 'edit_users');
// Update the user
$route->post("/users/update/{id}", [ListUserController::class, 'update'])
      ->middleware("/users/update/{id}", AuthMiddleware::class, 'update_users');
      $route->get("/view-profile", [ListUserController::class, 'viewProfile']);

 
// products
$route->get("/products", [AddProductController::class, 'index']);
$route->get("/products/create", [AddProductController::class, 'create']);
$route->post("/products/store", [AddProductController::class, 'store']);
$route->get("/products/edit/{id}", [AddProductController::class, 'edit']);
$route->post("/products/update/{id}", [AddProductController::class, 'update']);
$route->post("/products/delete/{id}", [AddProductController::class, 'destroy']);
$route->post("/products/save-order", [AddProductController::class, 'saveOrder']);

// Order history
$route->get("/order-history", [OrderHistoryController::class, 'index'])
      ->middleware("/order-history", AuthMiddleware::class, 'view_orders');
$route->get("/order-history/details/{id}", [OrderHistoryController::class, 'details'])
      ->middleware("/order-history/details/{id}", AuthMiddleware::class, 'view_orders');
// order-history
// $route->get("/order-history", [OrderHistoryController::class, 'index']);
$route->get("/order-history", [AddProductController::class, 'history']);


// Purchase history routes
$route->get("/purchase-history", [PurchaseController::class, 'index']);
$route->get("/purchase-history/export", [PurchaseController::class, 'exportCsv']);





  

 
      


$route->route();