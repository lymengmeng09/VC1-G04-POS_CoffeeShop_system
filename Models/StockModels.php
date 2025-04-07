<?php
require_once "Database/Database.php";

class ProductModel {
    private $conn;
    private $table = "stocks";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllProducts() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $price, $quantity, $image) {
        try {
            $query = "INSERT INTO " . $this->table . " (name, price, quantity, image, created_at) VALUES (:name, :price, :quantity, :image, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindParam(":image", $image, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception('Database error: ' . implode(', ', $stmt->errorInfo()));
            }

            $product_id = $this->conn->lastInsertId();

            // Log the purchase
            $this->recordPurchase('new_product', [
                [
                    'product_id' => $product_id,
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price
                ]
            ]);

            return $product_id;
        } catch (Exception $e) {
            error_log("Error adding product: " . $e->getMessage());
            throw new Exception('Failed to add product: ' . $e->getMessage());
        }
    }

    public function updateProduct($id, $name, $price, $quantity) {
        try {
            $product = $this->getProductById($id);
            if (!$product) {
                throw new Exception("Product not found");
            }
    
            $quantityChange = $quantity - $product['quantity'];
    
            $query = "UPDATE " . $this->table . " SET name = :name, price = :price, quantity = :quantity WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
    
            if (!$stmt->execute()) {
                throw new Exception('Database error: ' . implode(', ', $stmt->errorInfo()));
            }
    
            // Log the purchase if quantity increased
            if ($quantityChange > 0) {
                $this->recordPurchase('update_product', [
                    [
                        'product_id' => $id,
                        'name' => $name,
                        'quantity' => $quantityChange,
                        'price' => $price
                    ]
                ]);
            }
    
            // Check for low stock or out of stock
            $notifications = [];
            if ($quantity == 0) {
                $notifications[] = "Product '$name' is now Out of Stock.";
            } elseif ($quantity > 0 && $quantity < 5) {
                $notifications[] = "Product '$name' is Low on Stock (Quantity: $quantity).";
            }
    
            return [
                'success' => true,
                'notifications' => $notifications
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to update product: ' . $e->getMessage());
        }
    }

    public function getTopSellingProducts($start_date = null, $end_date = null) {
        $query = "
            SELECT s.name AS product_name, SUM(oi.quantity) AS total_sold
            FROM " . $this->table . " s
            JOIN order_items oi ON s.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
        ";
        if ($start_date && $end_date) {
            $query .= " WHERE o.order_date BETWEEN :start_date AND :end_date";
        }
        $query .= " GROUP BY s.id, s.name ORDER BY total_sold DESC LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        if ($start_date && $end_date) {
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteProduct($id) {
        try {
            // Start a transaction
            $this->conn->beginTransaction();
    
            // Delete related records from purchase_items
            $query = "DELETE FROM purchase_items WHERE product_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Optionally, delete from other dependent tables (e.g., order_items)
            $query = "DELETE FROM order_items WHERE product_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Now delete the product from stocks
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Commit the transaction
            $this->conn->commit();
        } catch (PDOException $e) {
            // Roll back the transaction on error
            $this->conn->rollBack();
            error_log("Error deleting product: " . $e->getMessage());
            throw new Exception("Failed to delete product: " . $e->getMessage());
        }
    }

    public function recordPurchase($status, $items) {
        try {
            $this->conn->beginTransaction();

            // Calculate total amount
            $total_amount = 0;
            foreach ($items as $item) {
                $total_amount += $item['quantity'] * $item['price'];
            }

            // Insert into purchases table
            $query = "INSERT INTO purchases (purchase_date, total_amount, status) VALUES (:purchase_date, :total_amount, :status)";
            $stmt = $this->conn->prepare($query);
            $purchase_date = date('Y-m-d');
            $stmt->bindParam(':purchase_date', $purchase_date, PDO::PARAM_STR);
            $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            $purchase_id = $this->conn->lastInsertId();

            // Insert into purchase_items table (without product_name)
            $query = "INSERT INTO purchase_items (purchase_id, product_id, quantity, unit_price, total_price) 
                      VALUES (:purchase_id, :product_id, :quantity, :unit_price, :total_price)";
            $stmt = $this->conn->prepare($query);

            foreach ($items as $item) {
                $total_price = $item['quantity'] * $item['price'];
                $stmt->bindParam(':purchase_id', $purchase_id, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindParam(':unit_price', $item['price'], PDO::PARAM_STR);
                $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
                $stmt->execute();
            }

            $this->conn->commit();
            return $purchase_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error recording purchase: " . $e->getMessage());
            throw new Exception('Failed to record purchase: ' . $e->getMessage());
        }
    }

    public function getPurchaseDetails($purchase_id) {
        $query = "SELECT p.status, p.total_amount, p.purchase_date, s.name AS product_name, pi.quantity, pi.unit_price, pi.total_price 
                  FROM purchases p 
                  JOIN purchase_items pi ON p.id = pi.purchase_id 
                  JOIN stocks s ON pi.product_id = s.id
                  WHERE p.id = :purchase_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':purchase_id', $purchase_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    
}
