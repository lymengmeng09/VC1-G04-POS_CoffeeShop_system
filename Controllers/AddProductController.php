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
        $this->view('products/create');
        // $categorys = $this->model->getCategory();
        // $this->view('products/create', ['categories' => $categorys]);
    }
    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); 
            }

            $imageName = basename($_FILES['image_url']['name']);
            $uploadFile = $uploadDir . $imageName;

            
            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['image_url']['tmp_name'], $uploadFile)) {
                    $image_url = $uploadFile; 
                    $data = [
                        'product_name' => isset($_POST['product_name']) ? $_POST['product_name'] : null,
                        'price' => isset($_POST['price']) ? $_POST['price'] : null,
                        'category' => isset($_POST['category']) ? $_POST['category'] : null,
                        'image_url' => $image_url, 
                        'category_id' => isset($_POST['category_id']) ? $_POST['category_id'] : null
                    ];

                    // Validate that all required fields are present
                    if (empty($data['product_name']) || empty($data['price']) || empty($data['category']) || empty($data['category_id'])) {
                        $_SESSION['error'] = 'All fields except the image are required!';
                        $this->view('products/create', ['error' => $_SESSION['error']]);
                        return;
                    }

                    // Call model to create the product
                    if ($this->model->createProduct($data)) {
                        $_SESSION['success'] = 'Product created successfully!';
                        $this->redirect('/products');
                    } else {
                        $_SESSION['error'] = 'There was an issue creating the product.';
                        $this->view('products/create', ['error' => $_SESSION['error']]);
                    }
                } else {
                    $_SESSION['error'] = 'Sorry, there was an error uploading your file.';
                    $this->view('products/create', ['error' => $_SESSION['error']]);
                }
            } else {
                $_SESSION['error'] = 'Only image files are allowed (JPG, JPEG, PNG, GIF).';
                $this->view('products/create', ['error' => $_SESSION['error']]);
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
        $this->view('products/edit', ['product' => $product]);
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
                $_SESSION['success'] = 'Product updated successfully!';
                $this->redirect('/products');
            } else {
                $_SESSION['error'] = 'There was an issue updating the product.';
                $this->view('products/edit', ['error' => $_SESSION['error']]);
            }
        }
    }

    
}
