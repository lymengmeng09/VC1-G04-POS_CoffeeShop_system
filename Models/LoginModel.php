<?php
class LoginModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    // public function getUserByEmail($email) {
    //     $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
    //     $stmt->execute([$email]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("
            SELECT users.*, roles.role_name 
            FROM users 
            LEFT JOIN roles ON users.role_id = roles.id 
            WHERE users.email = :email
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    public function registerUser($name, $email, $hashedPassword) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$name, $email, $hashedPassword]);
    }
}
