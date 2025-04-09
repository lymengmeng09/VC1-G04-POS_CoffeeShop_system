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

    // Function to get the total number of products// Existing methods (getProductsByCategory, getCategories, etc.) remain unchanged...

    public function insertOrder($data) {
        try {
            $sql = "INSERT INTO orders (customer_id, order_number, order_date, total_amount, payment_status, created_at, updated_at)
                    VALUES (:customer_id, :order_number, :order_date, :total_amount, :payment_status, :created_at, :updated_at)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':customer_id', $data['customer_id']);
            $stmt->bindParam(':order_number', $data['order_number']);
            $stmt->bindParam(':order_date', $data['order_date']);
            $stmt->bindParam(':total_amount', $data['total_amount']);
            $stmt->bindParam(':payment_status', $data['payment_status']);
            $stmt->bindParam(':created_at', $data['created_at']);
            $stmt->bindParam(':updated_at', $data['updated_at']);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            error_log("Insert Order Error: " . $e->getMessage());
            return false;
        }
    }

    public function insertOrderItem($data) {
        try {
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
                    VALUES (:order_id, :product_id, :quantity, :price, :subtotal)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':order_id', $data['order_id']);
            $stmt->bindParam(':product_id', $data['product_id']);
            $stmt->bindParam(':quantity', $data['quantity']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':subtotal', $data['subtotal']);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Insert Order Item Error: " . $e->getMessage());
        }
    }

    public function getOrderReceipt($order_id) {
        try {
            $sql = "SELECT 
                        o.order_id,
                        o.customer_id,
                        o.order_number,
                        o.order_date,
                        o.total_amount,
                        o.payment_status,
                        oi.order_item_id,
                        oi.product_id,
                        oi.quantity,
                        oi.price,
                        oi.subtotal,
                        p.product_name
                    FROM orders o
                    LEFT JOIN order_items oi ON o.order_id = oi.order_id
                    LEFT JOIN products p ON oi.product_id = p.product_id
                    WHERE o.order_id = :order_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get Order Receipt Error: " . $e->getMessage());
            return false;
        }
    }

    // Keep
}
