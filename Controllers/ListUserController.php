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
            // Get the ID from the URL
            
            // Get the data from the form
            $data = [
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

    
 

