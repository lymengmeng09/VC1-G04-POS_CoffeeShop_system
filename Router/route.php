<?php
require_once "Router.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/LoginController.php";
require_once "Controllers/ViewStockController.php"; // Updated to use ProductController

$route = new Router();
///produts-editor
$route->get("/", [WelcomeController::class, 'index']);
$route->get("/viewStock", [ProductController::class, 'index']);
$route->get("/add-product", [ProductController::class, 'add']); // GET: show add product form
$route->post("/add-product", [ProductController::class, 'add']); // POST: handle add submission
$route->get("/edit-product/{id}", [ProductController::class, 'edit']); // GET: show edit product form
$route->post("/edit-product/{id}", [ProductController::class, 'edit']); // POST: handle edit submission
$route->post("/delete-product/{id}", [ProductController::class, 'delete']); // POST: handle delete
$route->route();

