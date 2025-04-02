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
    // Add this method to your model
    function getProductsByCategory($category_id = null)
    {
        $query = "SELECT p.*, c.category_name 
              FROM products p 
              JOIN categories c ON p.category_id = c.category_id";

        if ($category_id && $category_id !== 'all') {
            $query .= " WHERE p.category_id = :category_id";
        }

        $query .= " ORDER BY p.product_id DESC";

        $stmt = $this->conn->prepare($query);

        if ($category_id && $category_id !== 'all') {
            $stmt->bindParam(':category_id', $category_id);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getCategories()
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getProducts()
    {
        $query = "SELECT * FROM products ORDER BY product_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to create a new product
    function createProduct($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO products (product_name, price, image_url, category_id)
                  VALUES (:product_name, :price, :image_url, :category_id)");
        return $stmt->execute([
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'image_url' => $data['image_url'],
            'category_id' => $data['category_id']
        ]);
    }

    // Get a product by ID
    function getProductById($id)
    {
        $query = "SELECT * FROM products WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a product
    function updateProduct($data)
    {
        // Check if we need to update the image
        if (!empty($data['image_url'])) {
            $query = "UPDATE products 
                  SET product_name = :product_name, 
                      price = :price, 
                      image_url = :image_url, 
                      category_id = :category_id 
                  WHERE product_id = :product_id";
        } else {
            $query = "UPDATE products 
                  SET product_name = :product_name, 
                      price = :price, 
                      category_id = :category_id 
                  WHERE product_id = :product_id";
        }

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':product_name', $data['product_name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':product_id', $data['product_id']);
        // Bind image_url only if it's included in the query
        if (!empty($data['image_url'])) {
            $stmt->bindParam(':image_url', $data['image_url']);
        }

        // Execute the statement
        return $stmt->execute();
    }

    // Delete a product
    function deleteProduct($id)
    {
        $query = "DELETE FROM products WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
