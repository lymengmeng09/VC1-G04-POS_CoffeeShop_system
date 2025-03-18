<?php
require_once 'Database/Database.php';

class AddProductModel
{
    private $conn;
    function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    function getProducts()
    {
        // Prepare the SQL query to get products
        $query = "SELECT * FROM users ORDER BY id DESC";
        
        // Execute the query and return the results
        $stmt = $this->conn->prepare($query);
         // Execute the statement
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all the results as an associative array
    }

    // Function to create a new product
    function createProduct($data)
{
    // Prepare the statement to insert both 'name' and 'description'
    $stmt = $this->conn->prepare("INSERT INTO products(product_name, descriptio,p rice, category,	stock_status, image_url	,created_at	,updated_at	,category_id) VALUES (:dpm_name, :description)",[
        'dpm_name' => $data['dpm_name'],
        'description' => $data['description']]);
}
}

