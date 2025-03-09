<?php
class LoginController extends BaseController {
    private $conn;

    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Create a new instance of the Database class and get connection
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function index() {
        // Redirect if user is already logged in
        if (isset($_SESSION["user"])) {
            header('Location: /');
            exit();
        }

        // Handle the POST request for login
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                echo "<script>alert('Email and password are required!');</script>";
            } else {
                // Prepare and execute the query using PDO
                $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    // Store user in session
                    $_SESSION['user'] = $user;
                    
                    // Redirect to dashboard
                    header("Location: /");
                    exit;
                } else {
                    echo "<script>alert('Invalid Email or Password!');</script>";
                }
            }
        }

        $this->view('login/login');
    }

    public function logout() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Unset all session variables
        $_SESSION = array();
        
        // If a session cookie is used, destroy it
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to login page
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
                // Check if email already exists
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result['count'] > 0) {
                    echo "<script>alert('Email already exists!');</script>";
                } else {
                    // Hash the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert the new user
                    $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
                    if ($stmt->execute([$name, $email, $hashedPassword])) {
                        echo "<script>alert('Registration successful! You can now login.'); window.location.href = '/login';</script>";
                        exit;
                    } else {
                        echo "<script>alert('Registration failed. Please try again.');</script>";
                    }
                }
            }
        }

        $this->view('login/register');
    }
}