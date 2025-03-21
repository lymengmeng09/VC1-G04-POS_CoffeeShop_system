<?php
require_once 'Models/AddProductModel.php';
require_once 'BaseController.php';

// 
class AddProductController extends BaseController
{
    private $model;

    // Constructor to initialize model and check for user authentication
    function __construct()
    {
        $this->model = new AddProductModel();
        $this->checkAuth();
    }

    // Authentication check for logged-in users
    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If user is not logged in, redirect to login page
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }
    function index()
    {
        $products = $this->model->getProducts();
        $this->view('products/list-product', ['products' => $products]);
    }

    // Function to display the product creation form
    function create()
    {
        $categories = $this->model->getCategories();
        $this->view('products/create', ['categories' => $categories]);
    }
    function store()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle file upload
        $targetDir = "uploads/"; // Create this directory if it doesn't exist
        $fileName = basename($_FILES["image_url"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        // Check if file was actually uploaded
        if(!empty($_FILES["image_url"]["name"])) {
            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if(in_array($fileType, $allowTypes)) {
                // Upload file to server
                if(move_uploaded_file($_FILES["image_url"]["tmp_name"], $targetFilePath)) {
                    // File uploaded successfully, now save product data
                    $data = [
                        'product_name' => $_POST['product_name'],
                        'price' => $_POST['price'],
                        'image_url' => $targetFilePath, // Save the file path, not the file itself
                        'category_id' => $_POST['category_id']
                    ];
                    $this->model->createProduct($data);
                    $this->redirect('/products');
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            echo "Please select a file to upload.";
        }
    }
}
}
