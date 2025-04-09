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
    // Get the selected category from query parameters
    $category_id = $_GET['category'] ?? 'all';
    
    // Get products (filtered by category if specified)
    $products = $this->model->getProductsByCategory($category_id);
    
    // Get all categories for the dropdown
    $categories = $this->model->getCategories();
    
    // Pass both products and categories to the view
    $this->view('products/list-product', [
        'products' => $products,
        'categories' => $categories,
        'selected_category' => $category_id
    ]);
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


public function edit($id){
    $product = $this->model->getProductById($id);
    if (!$product) {
        $_SESSION['error'] = 'Product not found!';
        $this->redirect('/products');
        return;
    }
    
    // Get categories from the database
    $categories = $this->model->getCategories();
    
    $this->view('products/edit', [
        'product' => $product,
        'categories' => $categories
    ]);
}


    public function update($id)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $uploadDir = 'uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $image_url = null;
        if (!empty($_FILES['image_url']['name'])) {
            $imageName = basename($_FILES['image_url']['name']);
            $uploadFile = $uploadDir . $imageName;
            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['image_url']['tmp_name'], $uploadFile)) {
                    $image_url = $uploadFile;
                } else {
                    $_SESSION['error'] = 'Sorry, there was an error uploading your file.';
                    $this->view('products/edit', ['error' => $_SESSION['error']]);
                    return;
                }
            } else {
                $_SESSION['error'] = 'Only image files are allowed (JPG, JPEG, PNG, GIF).';
                $this->view('products/edit', ['error' => $_SESSION['error']]);
                return;
            }
        }

        $data = [
            'product_name' => isset($_POST['product_name']) ? $_POST['product_name'] : null,
            'price' => isset($_POST['price']) ? $_POST['price'] : null,
            'category' => isset($_POST['category']) ? $_POST['category'] : null,
            'image_url' => $image_url,
            'category_id' => isset($_POST['category_id']) ? $_POST['category_id'] : null,
            'product_id' => $id
        ];

        if (empty($data['product_name']) || empty($data['price']) || empty($data['category']) || empty($data['category_id'])) {
            $_SESSION['error'] = 'All fields except the image are required!';
            $this->view('products/edit', ['error' => $_SESSION['error']]);
            return;
        }

        if ($this->model->updateProduct($data)) {
            $this->redirect('/products');
        } else {
            $_SESSION['error'] = 'There was an issue updating the product.';
            $this->view('products/edit', ['error' => $_SESSION['error']]);
        }
    }
}
    


public function destroy($id)
{
    $this->model->deleteProduct($id);
    $this->redirect('/products');
}
// Update generateReceipt to include customer_id
// Update the generateReceipt method in your AddProductController.php

public function generateReceipt()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get customer_id from session (assuming user is logged in)
        $customer_id = $_SESSION['user']['customer_id'] ?? null;
        if (!$customer_id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Customer not logged in']);
            exit;
        }

        try {
            // Begin transaction
            $this->model->conn->beginTransaction();
            
            // Create the order
            $total = $data['total'];
            $order_id = $this->model->createOrder($customer_id, $total);
            
            if (!$order_id) {
                throw new Exception("Failed to create order");
            }
            
            // Create order items
            foreach ($data['items'] as $item) {
                $success = $this->model->createOrderItem(
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                );
                
                if (!$success) {
                    throw new Exception("Failed to create order item");
                }
            }
            
            // Commit transaction
            $this->model->conn->commit();
            
            // Return success response with order_id
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'order_id' => $order_id]);
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->model->conn->rollBack();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}

// Update the history method to fetch customer-specific orders
public function history()
{
    // Get customer_id from session
    $customer_id = $_SESSION['user']['customer_id'] ?? null;
    if (!$customer_id) {
        $_SESSION['error'] = 'Please log in to view your order history.';
        $this->redirect('/login');
        return;
    }

    // Get order history for this customer
    $orders = $this->model->getOrderHistory($customer_id);
    
    // Load the order history view
    $this->view('order/order-history', ['orders' => $orders]);
}
}