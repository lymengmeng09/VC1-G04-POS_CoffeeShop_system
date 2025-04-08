<?php
require_once 'Database/Database.php';

class AddProductModel {
    private $conn;

    function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    function getProductsByCategory($category_id = null) {
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

    function getCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getProducts() {
        $query = "SELECT * FROM products ORDER BY product_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function createProduct($data) {
        $stmt = $this->conn->prepare("INSERT INTO products (product_name, price, image_url, category_id)
                  VALUES (:product_name, :price, :image_url, :category_id)");
        return $stmt->execute([
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'image_url' => $data['image_url'],
            'category_id' => $data['category_id']
        ]);
    }

    function getProductById($id) {
        $query = "SELECT * FROM products WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateProduct($data) {
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
        $stmt->bindParam(':product_name', $data['product_name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':product_id', $data['product_id']);
        if (!empty($data['image_url'])) {
            $stmt->bindParam(':image_url', $data['image_url']);
        }
        return $stmt->execute();
    }

    function deleteProduct($id) {
        $query = "DELETE FROM products WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Updated storeOrder method (removed customer_id)
    public function storeOrder($orderNumber, $orderDate, $totalAmount, $paymentStatus, $items) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO orders (order_number, order_date, total_amount, payment_status, created_at, updated_at) 
                      VALUES (:order_number, :order_date, :total_amount, :payment_status, NOW(), NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'order_number' => $orderNumber,
                'order_date' => $orderDate,
                'total_amount' => $totalAmount,
                'payment_status' => $paymentStatus
            ]);
            $orderId = $this->conn->lastInsertId();
            error_log("Order inserted, ID: " . $orderId); // Debug order ID

            $query = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
                      VALUES (:order_id, :product_id, :quantity, :price, :subtotal)";
            $stmt = $this->conn->prepare($query);

            foreach ($items as $item) {
                $stmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Store Order Error: " . $e->getMessage());
            throw $e;
        }

    // Updated getOrderHistory method (removed customer_id)
    function getOrderHistory() {
        $query = "
            SELECT o.order_id, o.order_number, o.order_date, o.total_amount, o.payment_status, 
                   oi.order_item_id, oi.product_id, oi.quantity, oi.price, oi.subtotal, 
                   p.product_name
            FROM orders o
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN products p ON oi.product_id = p.product_id
            ORDER BY o.order_date DESC
        ";
    
        $stmt = $this->conn->prepare($query);
        error_log("Executing getOrderHistory query: " . $query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Order history results: " . print_r($results, true));
        return $results;
    }
}}