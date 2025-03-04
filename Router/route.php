<?php
require_once "Router.php";
require_once "Controllers/BaseController.php";
require_once "Database/Database.php";
require_once "Controllers/WelcomeController.php";


$route = new Router();
$route->get("/", [WelcomeController::class, 'welcome']);

$route->route();