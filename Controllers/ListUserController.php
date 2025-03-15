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
        $this->view('users/create', ['roles' => $roles]);
        $this->checkPermission('create_users');
    }


    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'role_id' => $_POST['role_id']
            ];
            $this->model->createUser($data);
            $this->checkPermission('create_users');
            $this->redirect('/list-users');
        }
    }


    public function destroy()
    {
        // Check permission before allowing deletion
        if (!AccessControl::hasPermission('delete_users')) {
            $id = $_GET['id']; // Get ID from URL
            $this->model->deleteUser($id);
            $this->redirect('/list-users');
        }

        // Continue with delete logic
    }

    
    public function edit()
{
    // Get ID from URL parameter
    $id = $_GET['id'];
    
    // Fetch user data using your model
    $user = $this->model->getUserById($id);
    $roles = $this->model->getRoles();

    if (!$user) {
        // Handle the case where the user is not found
        // You might want to add a flash message system
        $this->redirect('/list-users');
        exit();
    }

    // Pass the data to the view
    $this->view('users/edit', ['user' => $user, 'roles' => $roles]);
}

public function update()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id']; // Make sure you have a hidden input with the user ID
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $role_id = (int)$_POST['role_id'];

        // Basic validation
        if (empty($name) || empty($email) || empty($role_id)) {
            // Add error handling here
            $this->redirect('/edit-user?id=' . $id);
            exit();
        }

        // Update the user using your model
        $result = $this->model->updateUser($id, $name, $email, $role_id);

        if ($result) {
            // Success - redirect to the users list
            $this->redirect('/list-users');
        } else {
            // Failed - redirect back to edit form
            $this->redirect('/edit-user?id=' . $id);
        }
    }
}
}

    
 

