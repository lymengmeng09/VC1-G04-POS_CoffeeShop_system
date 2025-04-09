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

    
   // Create a new order
public function createOrder($customer_id, $total_amount) {
    try {
        $sql = "INSERT INTO orders (customer_id, total_amount, order_date) 
                VALUES (:customer_id, :total_amount, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating order: " . $e->getMessage());
        return false;
    }
}

// Create order items
public function createOrderItem($order_id, $product_id, $quantity, $price) {
    try {
        // Get product category
        $stmt = $this->conn->prepare("SELECT category_id FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $category_id = $product['category_id'];
        
        // Insert order item
        $sql = "INSERT INTO order_items (order_id, product_id, category_id, quantity, price) 
                VALUES (:order_id, :product_id, :category_id, :quantity, :price)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        error_log("Error creating order item: " . $e->getMessage());
        return false;
    }
}

// Get order history for a specific customer
public function getOrderHistory($customer_id) {
    try {
        // Get all orders for this customer
        $sql = "SELECT o.order_id, o.total_amount, o.order_date 
                FROM orders o 
                WHERE o.customer_id = :customer_id 
                ORDER BY o.order_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // For each order, get the items
        foreach ($orders as &$order) {
            $sql = "SELECT oi.*, p.product_name, c.category_name 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.product_id 
                    JOIN categories c ON oi.category_id = c.category_id 
                    WHERE oi.order_id = :order_id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':order_id', $order['order_id'], PDO::PARAM_INT);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate subtotal for each item
            foreach ($items as &$item) {
                $item['subtotal'] = $item['quantity'] * $item['price'];
            }
            
            $order['items'] = $items;
        }
        
        return $orders;
    } catch (PDOException $e) {
        error_log("Error getting order history: " . $e->getMessage());
        return [];
    }}
}
