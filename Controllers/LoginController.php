<?php
require_once 'Models/LoginModel.php';
require_once 'Helpers/LanguageHelper.php';

class LoginController extends BaseController {
    private $loginModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize language helper
        LanguageHelper::init();
        
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
                    $data['errors']['email'] = 'email_required';
                }
                if (empty($password)) {
                    $data['errors']['password'] = 'password_required';
                }
            } else {
                // Get user from model
                $user = $this->loginModel->getUserByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    // Save current language preference
                    $currentLang = $_SESSION['site_lang'] ?? 'en';
                    
                    // Set user session
                    $_SESSION['user'] = $user;
                    $_SESSION['role_id'] = $user['role_id'];
                    
                    // Restore language preference
                    $_SESSION['site_lang'] = $currentLang;
                    
                    header("Location: /");
                    exit;
                } else {
                    if (!$user) {
                        $data['errors']['email'] = 'email_not_registered';
                    } else {
                        $data['errors']['password'] = 'incorrect_password';
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
        
        // Save current language preference before destroying session
        $currentLang = $_SESSION['site_lang'] ?? 'en';
        
        // Store language in a cookie to ensure it persists
        setcookie('site_language', $currentLang, time() + (30 * 24 * 60 * 60), '/');
        
        // Clear session variables
        $_SESSION = array();
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        // Start a new session and restore language preference
        session_start();
        $_SESSION['site_lang'] = $currentLang;
        
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
                echo "<script>alert('" . __('all_fields_required') . "');</script>";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('" . __('valid_email_required') . "');</script>";
            } else {
                // Check if email already exists using the model
                if ($this->loginModel->emailExists($email)) {
                    echo "<script>alert('" . __('email_exists') . "');</script>";
                } else {
                    // Hash the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Register the user using the model
                    if ($this->loginModel->registerUser($name, $email, $hashedPassword)) {
                        echo "<script>alert('" . __('registration_successful') . "'); window.location.href = '/login';</script>";
                        exit;
                    } else {
                        echo "<script>alert('" . __('registration_failed') . "');</script>";
                    }
                }
            }
        }

        $this->view('login/register', [], 'auth_layout');
    }
}