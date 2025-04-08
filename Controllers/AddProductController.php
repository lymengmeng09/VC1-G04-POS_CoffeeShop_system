<?php
require_once 'Models/AddProductModel.php';
require_once 'BaseController.php';

class AddProductController extends BaseController {
    private $model;

    function __construct() {
        $this->model = new AddProductModel();
        $this->checkAuth();
    }

    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }

    function index() {
        $category_id = $_GET['category'] ?? 'all';
        $products = $this->model->getProductsByCategory($category_id);
        $categories = $this->model->getCategories();
        $this->view('products/list-product', [
            'products' => $products,
            'categories' => $categories,
            'selected_category' => $category_id
        ]);
    }

    function create() {
        $categories = $this->model->getCategories();
        $this->view('products/create', ['categories' => $categories]);
    }

    function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targetDir = "uploads/";
            $fileName = basename($_FILES["image_url"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            if (!empty($_FILES["image_url"]["name"])) {
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
                if (in_array($fileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $targetFilePath)) {
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

    public function edit($id) {
        $product = $this->model->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'Product not found!';
            $this->redirect('/products');
            return;
        }
        $categories = $this->model->getCategories();
        $this->view('products/edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update($id) {
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

    public function destroy($id) {
        $this->model->deleteProduct($id);
        $this->redirect('/products');
    }

    // Updated submitOrder method (removed customer_id)
    public function submitOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = json_decode(file_get_contents('php://input'), true);
                error_log("Received data: " . print_r($data, true)); // Debug input

                $cartItems = $data['cartItems'] ?? [];
                $totalAmount = $data['totalAmount'] ?? 0;

                if (empty($cartItems) || !is_numeric($totalAmount)) {
                    throw new Exception('Invalid order data. Cart empty or total invalid.');
                }

                $orderNumber = 'POS-' . date('YmdHis') . '-' . rand(1000, 9999);
                $orderDate = date('Y-m-d H:i:s');
                $paymentStatus = 'completed';

                $items = array_map(function ($item) {
                    return [
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ];
                }, $cartItems);

                error_log("Prepared items: " . print_r($items, true)); // Debug items

                $orderId = $this->model->storeOrder($orderNumber, $orderDate, $totalAmount, $paymentStatus, $items);

                echo json_encode(['success' => true, 'order_id' => $orderId, 'message' => 'Order placed successfully']);
                exit;
            } catch (Exception $e) {
                error_log("Submit Order Error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit;
            }
        }
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        exit;
    }
}