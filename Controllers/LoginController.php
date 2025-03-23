<?php
require_once 'Models/LoginModel.php';

class LoginController extends BaseController {
    private $loginModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->loginModel = new LoginModel();
    }

    public function index() {
        // Redirect if user is already logged in
        if (isset($_SESSION["user"])) {
            header('Location: /');
            exit();
        }
        
        $data = [
            'errors' => []
        ];

        // Handle the POST request for login
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                if (empty($email)) {
                    $data['errors']['email'] = 'Email is required.';
                }
                if (empty($password)) {
                    $data['errors']['password'] = 'Password is required.';
                }
            } else {
                // Get user from model
                $user = $this->loginModel->getUserByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    $_SESSION['role_id'] = $user['role_id'];
                    header("Location: /");
                    exit;
                } else {
                    if (!$user) {
                        $data['errors']['email'] = 'This email is not registered.';
                    } else {
                        $data['errors']['password'] = 'Incorrect password.';
                    }
                }
            }
        }

        $this->view('login/login', $data, 'auth_layout');
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        header('Location: /login');
        exit();
    }


    public function register() {
        // Redirect if user is already logged in
        if (isset($_SESSION["user"])) {
            header('Location: /');
            exit();
        }

        // Handle the POST request for registration
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validate inputs
            if (empty($name) || empty($email) || empty($password)) {
                echo "<script>alert('All fields are required!');</script>";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Please enter a valid email address!');</script>";
            } else {
                // Check if email already exists using the model
                if ($this->loginModel->emailExists($email)) {
                    echo "<script>alert('Email already exists!');</script>";
                } else {
                    // Hash the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Register the user using the model
                    if ($this->loginModel->registerUser($name, $email, $hashedPassword)) {
                        echo "<script>alert('Registration successful! You can now login.'); window.location.href = '/login';</script>";
                        exit;
                    } else {
                        echo "<script>alert('Registration failed. Please try again.');</script>";
                    }
                }
            }
        }

        $this->view('login/register', [], 'auth_layout');
    }
}