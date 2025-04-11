<?php
require_once 'Models/AddProductModel.php';
require_once 'BaseController.php';

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

    // Display product list
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

    // Display the product creation form
    function create()
    {
        $categories = $this->model->getCategories();
        $this->view('products/create', ['categories' => $categories]);
    }

    // Store a new product
    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle file upload
            $targetDir = "uploads/"; // Create this directory if it doesn't exist
            $fileName = basename($_FILES["image_url"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            
            // Check if file was actually uploaded
            if (!empty($_FILES["image_url"]["name"])) {
                // Allow certain file formats
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    // Upload file to server
                    if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $targetFilePath)) {
                        // File uploaded successfully, now save product data
                        $data = [
                            'product_name' => $_POST['product_name'],
                            'price' => $_POST['price'],
                            'image_url' => $targetFilePath,
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

    // Display the product edit form
    public function edit($id)
    {
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

    // Update a product
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

            if (empty($data['product_name']) || empty($data['price']) || empty($data['category_id'])) {
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

    // Delete a product
    public function destroy($id)
    {
        $this->model->deleteProduct($id);
        $this->redirect('/products');
    }

    // Save an order
    public function saveOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderData = json_decode(file_get_contents('php://input'), true);
            
            try {
                $orderId = $this->model->createOrder($orderData['total']);
                $this->model->createOrderItems($orderId, $orderData['items']);
                
                echo json_encode(['success' => true, 'order_id' => $orderId]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    // Display order history
    public function history()
    {
        $orders = $this->model->getOrderHistory();
        $this->view('order-history/index', ['orders' => $orders]);
    }
}