<?php
require_once "Router.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/WelcomeController.php";
require_once "Controllers/StockController.php";



$route = new Router();
$route->get("/", [WelcomeController::class, 'welcome']);
$route->get("/stock-products", [StockController::class, 'stock']);
$route->route();
