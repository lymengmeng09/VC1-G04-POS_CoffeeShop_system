<?php
require_once 'Database/Database.php';

class AddProductModel
{
    private $conn;

    // Constructor that initializes the Database connection
    function __construct()
    {
        // Get the database connection from the Database class
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Function to get all products
    function getProducts()
    {
        // Prepare the SQL query to get products
        $query = "SELECT * FROM users ORDER BY id DESC";
        
        // Execute the query and return the results
        $stmt = $this->conn->prepare($query);
        $stmt->execute();  // Execute the statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all the results as an associative array
    }

    // Function to create a new product
    function createProduct($data)
    {
        // Prepare the SQL query for inserting a new product
        $query = "INSERT INTO users (name, image) VALUES (:name, :image)";
        
        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        
        // Bind the parameters to prevent SQL injection
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':image', $data['image']);
        
        // Execute the query
        if ($stmt->execute()) {
            return true; // Return true if the insert was successful
        } else {
            return false; // Return false if the insert failed
        }
    }
}
?>
