<?php
require_once "Router.php";
require_once "Controllers/DashboardController.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/LoginController.php";
require_once "Controllers/ViewStockController.php"; // Updated to use ProductController
require_once "Controllers/ListUserController.php";
require_once "Controllers/AddProductController.php";




$route = new Router();
// Product management routes
$route->get("/", [DashboardController::class, 'index']);
$route->get("/viewStock", [ProductController::class, 'index']);
$route->get("/add-product", [ProductController::class, 'add']); // GET: show add product form
$route->post("/add-product", [ProductController::class, 'add']); // POST: handle add submission
$route->get("/edit-product/{id}", [ProductController::class, 'edit']); // GET: show edit product form
$route->post("/edit-product/{id}", [ProductController::class, 'edit']); // POST: handle edit submission
$route->post("/delete-product/{id}", [ProductController::class, 'delete']); // POST: handle delete



// Dashboard route
$route->get("/dashboard", [ProductController::class, 'dashboard']);
$route->post("/dashboard", [ProductController::class, 'dashboard']); // Add POST for form submission



$route->route();



//login
$route->get("/login", [LoginController::class, 'index']);
$route->get("/login/logout", [LoginController::class, 'logout']);
$route->get("/login/register", [LoginController::class, 'register']);

//user
$route->get("/list-users", [ListUserController::class, 'index']);

$route->route();


// products
$route->get("products", [AddProductController::class, 'index']);
$route->get("products/create", [AddProductController::class, 'create']);
$route->route();