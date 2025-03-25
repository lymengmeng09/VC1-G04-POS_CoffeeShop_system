<?php
require_once "./Models/UsersModel.php";
class ListUserController extends BaseController
{
    private $model;
    public function __construct()
    {
        // Check if user is logged in
        $this->checkAuth();
        $this->model = new UserModel();
    }
    // Authentication check method
    private function checkAuth()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If user is not logged in, redirect to login page
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }
    public function index()
    {
        $users = $this->model->getUsers();
        $this->view('users/lists', ['users' => $users]);
    }
    
    function create()
    {
        $roles = $this->model->getRoles();
        
        // Initialize form data and errors
        $formData = [
            'name' => '',
            'email' => '',
            'role_id' => ''
        ];
        
        $errors = [];
        
        // Check if there are form data and errors in the session
        if (isset($_SESSION['form_data'])) {
            $formData = $_SESSION['form_data'];
            unset($_SESSION['form_data']);
        }
        
        if (isset($_SESSION['form_errors'])) {
            $errors = $_SESSION['form_errors'];
            unset($_SESSION['form_errors']);
        }
        
        $this->view('users/create', [
            'roles' => $roles,
            'formData' => $formData,
            'errors' => $errors
        ]);
        
        $this->checkPermission('create_users');
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkPermission('create_users');
            
            // Set default image path
        $defaultImagePath = 'views/assets/images/profile.png'; // Adjust this path
        
        // Handle file upload - if no file uploaded, use default
        $profileImagePath = $defaultImagePath;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $profileImageName = basename($_FILES['profile_image']['name']);
                $profileImagePath = $uploadDir . $profileImageName;
                move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImagePath);
            }
            
            // Get form data
            $data = [
                'profile' => $profileImagePath,
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role_id' => $_POST['role_id'] ?? ''
            ];
            
            // Store form data in session in case we need to redisplay the form
            $_SESSION['form_data'] = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id']
            ];
            
            // Initialize errors array
            $errors = [];
            
            // If there are validation errors, redisplay the form
            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                $this->redirect('/users/create');
                return;
            }
            
            // Try to create the user
            $result = $this->model->createUser($data);
            
            if ($result['success']) {
                // Clear form data from session
                unset($_SESSION['form_data']);
                
                // Success - redirect to list
                $this->redirect('/list-users');
            } else {
                // Failed - add error to the appropriate field
                if ($result['error'] === 'email_exists') {
                    $errors['email'] = $result['message'];
                } else {
                    // General error
                    $errors['general'] = $result['message'];
                }
                
                $_SESSION['form_errors'] = $errors;
                $this->redirect('/users/create');
            }
        }
    }

    public function destroy()
    {
        // Check permission before allowing deletion
        if (!AccessControl::hasPermission('delete_users')) {
            header('Location: /list-users?error=unauthorized');
            exit();
        }
    
        // Get ID from URL and delete the user
        $id = $_GET['id'] ?? null; // Ensure ID exists
        if ($id) {
            $this->model->deleteUser($id);
        }
    
        $this->redirect('/list-users');
    }

    public function edit()
    {
        // Get the ID from the URL
        $id = $_GET['id'];
        // Get the user from the model
        $user = $this->model->getUserById($id);
        // Get the roles from the model
        $roles = $this->model->getRoles();
        // Display the edit form
        $this->view('users/edit', ['user' => $user, 'roles' => $roles]);
    }
    
    public function update()
{
    $id = $_GET['id'];
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle file upload
        $profileImagePath = '';
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $profileImageName = basename($_FILES['profile_image']['name']);
            $profileImagePath = $uploadDir . $profileImageName;
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImagePath);
        }
        
        // Get the data from the form
        $data = [
            'profile' => $profileImagePath,
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'role_id' => $_POST['role_id']
        ];
        
        // Update the user using the model
        $this->model->updateUser($id, $data);
        
        // Redirect to the list of users
        $this->redirect('/list-users');
    }
}
public function viewProfile()
{
    $users = $this->model->getUsers();
    $this->view('users/view', ['users' => $users]);
}
public function reset()
    {
        $id = $_GET['id'];
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the ID from the URL
            
            // Get the data from the form
            $data = [
                'password' => $_POST['password']
            ];
            
            // Update the user using the model
            $this->model->resetPassword($id, $data);
            
            // Redirect to the list of users
            $this->redirect('/list-users');
        }
    }
}

