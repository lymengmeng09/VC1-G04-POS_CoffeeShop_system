<?php
class UserModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function getRoles() {
        $role = $this->db->prepare("SELECT * FROM roles");
        $role->execute();
        $result = $role->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    
    public function getUsers() {
        $stmt = $this->db->prepare("
        SELECT users.id, users.profile, users.name, users.email, roles.role_name 
        FROM users 
        LEFT JOIN roles ON users.role_id = roles.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if an email already exists in the database
     * 
     * @param string $email The email to check
     * @return bool True if the email exists, false otherwise
     */
    public function emailExists($email) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking email existence: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create a new user with error handling for duplicate emails
     * 
     * @param array $data User data
     * @return array Result with status and error message if applicable
     */
    function createUser($data) {
        try {
            // First check if email exists to avoid the exception
            if ($this->emailExists($data['email'])) {
                return [
                    'success' => false,
                    'error' => 'email_exists',
                    'message' => 'This email address is already in use.'
                ];
            }
            
            // Hash the password before storing
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
            $stmt = $this->db->prepare("INSERT INTO users (profile, name, email, password, role_id) VALUES (:profile, :name, :email, :password, :role_id)");
            $result = $stmt->execute([
                'profile'  => $data['profile'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'role_id' => $data['role_id']
            ]);
            
            if ($result) {
                return ['success' => true];
            } else {
                return [
                    'success' => false,
                    'error' => 'insert_failed',
                    'message' => 'Failed to create user.'
                ];
            }
        } catch (PDOException $e) {
            // Check if this is a duplicate entry error
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'email') !== false) {
                return [
                    'success' => false,
                    'error' => 'email_exists',
                    'message' => 'This email address is already in use.'
                ];
            }
            
            // Log other errors
            // error_log("Error creating user: " . $e->getMessage());
            // return [
            //     'success' => false,
            //     'error' => 'database_error',
            //     'message' => 'An error occurred while creating the user.'
            // ];
        }
    }
    
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function updateUser($id, $data) {

        $stmt = $this->db->prepare("UPDATE users SET profile = :profile, name = :name, email = :email, role_id = :role_id WHERE id = :id");
        $result = $stmt->execute([
            'id' => $id,
            'profile'  => $data['profile'],
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id']
        ]);
    }
    
    public function resetPassword($id, $data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $result = $stmt->execute([
            'id' => $id,
            'password' => $hashedPassword,
        ]);
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
