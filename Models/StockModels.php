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

            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to update product: ' . $e->getMessage());
        }
    }

    public function updateProducts($id, $data) {
        try {
            $query = "UPDATE " . $this->table . " SET name = :name, price = :price, quantity = :quantity WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(":price", $data['price'], PDO::PARAM_STR);
            $stmt->bindParam(":quantity", $data['quantity'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating product: " . $e->getMessage());
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
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            throw new Exception("Failed to delete product: " . $e->getMessage());
        }
    }

    public function recordPurchase($status, $items) {
        try {
            $this->conn->beginTransaction();

            $total_amount = 0;
            foreach ($items as $item) {
                $total_amount += $item['quantity'] * $item['price'];
            }

            $query = "INSERT INTO purchases (purchase_date, total_amount, status) VALUES (:purchase_date, :total_amount, :status)";
            $stmt = $this->conn->prepare($query);
            $purchase_date = date('Y-m-d');
            $stmt->bindParam(':purchase_date', $purchase_date, PDO::PARAM_STR);
            $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            $purchase_id = $this->conn->lastInsertId();

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

    public function storeReceiptData($items, $action) {
        try {
            $this->conn->beginTransaction();
    
            $stmt = $this->conn->prepare("INSERT INTO receipts (action, timestamp) VALUES (?, ?)");
            $timestamp = date('Y-m-d H:i:s');
            $stmt->execute([$action, $timestamp]);
            $receipt_id = $this->conn->lastInsertId();
    
            foreach ($items as $item) {
                $stmtPurchase = $this->conn->prepare("INSERT INTO purchases (receipt_id, product_name, price, change_quantity, timestamp) VALUES (?, ?, ?, ?, ?)");
                $stmtPurchase->execute([
                    $receipt_id,
                    $item['name'],
                    $item['price'],
                    $item['change_quantity'],
                    $item['timestamp']
                ]);
    
                $stmtItem = $this->conn->prepare("INSERT INTO purchase_items (purchase_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
                $stmtItem->execute([
                    $receipt_id,
                    $item['name'],
                    $item['price'],
                    $item['change_quantity']
                ]);
            }
    
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Failed to store receipt: " . $e->getMessage());
            return false;
        }
    }
    public function addTelegramUser($chatId) {
        try {
            $query = "INSERT IGNORE INTO telegram_users (chat_id) VALUES (:chat_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':chat_id', $chatId, PDO::PARAM_STR);
            $success = $stmt->execute();
            error_log("addTelegramUser: Chat ID $chatId " . ($success ? "added successfully" : "failed to add"));
            return $success;
        } catch (Exception $e) {
            error_log("Error adding Telegram user: " . $e->getMessage());
            throw new Exception('Failed to add Telegram user: ' . $e->getMessage());
        }
    }

    public function getAllTelegramUsers() {
        try {
            $query = "SELECT chat_id FROM telegram_users WHERE is_active = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
            error_log("getAllTelegramUsers: Retrieved " . count($users) . " active users: " . implode(', ', $users));
            return $users;
        } catch (Exception $e) {
            error_log("Error fetching Telegram users: " . $e->getMessage());
            throw new Exception('Failed to fetch Telegram users: ' . $e->getMessage());
        }
    }

    public function getAllReceipts() {
        try {
            $query = "SELECT * FROM receipts ORDER BY timestamp DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching receipts: " . $e->getMessage());
            throw new Exception('Failed to fetch receipts: ' . $e->getMessage());
        }
    }
}