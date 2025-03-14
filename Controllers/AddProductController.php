<?php
require_once 'Models/AddProductModel.php';
require_once 'BaseController.php';

class AddProductController extends BaseController
{
    private $model;
    function __construct()
    {
        $this->model =  new AddProductModel();
    }
    function index()
    {
        $Product = $this->model->getProducts();
        $this->view('products/list-product.php',['products' => $Product]);
    }

    function create()
    {
        $this->view('products/create.php');
    }
}