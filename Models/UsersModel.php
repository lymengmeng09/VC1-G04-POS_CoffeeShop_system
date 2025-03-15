<?php
class UserModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection(); // Get the connection instead of assigning the class
    }
    public function getRoles() {
        $role = $this->db->prepare("SELECT * FROM roles");
        $role->execute();
        $result = $role->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    public function getUsers() {
        $stmt = $this->db->prepare("
        SELECT users.id, users.name, users.email, roles.role_name 
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
    function createUser($data) {
        // Hash the password before storing
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role_id) VALUES (:name, :email, :password, :role_id)");
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role_id' => $data['role_id']
        ]);
    }

    
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    public function updateUser($id, $name, $email, $role_id) {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, role_id = :role_id WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id
        ]);
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
