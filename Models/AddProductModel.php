<?php
require_once 'Database/Database.php';

class AddProductModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getProductsByCategory($category_id = null)
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

    public function getCategories()
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProducts()
    {
        $query = "SELECT * FROM products ORDER BY product_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProduct($data)
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

    public function getProductById($id)
    {
        $query = "SELECT * FROM products WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct($data)
    {
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

    public function deleteProduct($id)
    {
        $query = "DELETE FROM products WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function createOrder($totalAmount)
    {
        $stmt = $this->conn->prepare("INSERT INTO orders (total_amount, created_at) VALUES (:total, NOW())");
        $stmt->execute(['total' => $totalAmount]);
        return $this->conn->lastInsertId();
    }

    public function createOrderItems($orderId, $items)
    {
        foreach ($items as $item) {
            $stmt = $this->conn->prepare("INSERT INTO order_items 
                (order_id, product_id, product_name, price, quantity, category_id) 
                VALUES (:order_id, :product_id, :name, :price, :quantity, :category_id)");
            
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'category_id' => $item['category_id']
            ]);
        }
        return true;
    }

    public function getOrderHistory()
    {
        $query = "SELECT o.*, 
                  (SELECT COUNT(*) FROM order_items WHERE order_id = o.order_id) as item_count
                  FROM orders o 
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($orderId)
    {
        $query = "SELECT o.*, 
                  (SELECT COUNT(*) FROM order_items WHERE order_id = o.order_id) as item_count
                  FROM orders o 
                  WHERE o.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['order_id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) return null;
        
        $query = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['order_id' => $orderId]);
        $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $order;
    }

    public function getTopSellingProducts($startDate, $endDate, $limit = 5)
    {
        $query = "SELECT 
                    oi.product_id,
                    oi.product_name,
                    SUM(oi.quantity) as total_quantity,
                    SUM(oi.price * oi.quantity) as total_revenue
                  FROM order_items oi
                  JOIN orders o ON oi.order_id = o.order_id
                  WHERE o.created_at BETWEEN :start_date AND :end_date
                  GROUP BY oi.product_id, oi.product_name
                  ORDER BY total_quantity DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':start_date', $startDate . ' 00:00:00');
        $stmt->bindValue(':end_date', $endDate . ' 23:59:59');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalIncome($startDate, $endDate)
    {
        $query = "SELECT COALESCE(SUM(total_amount), 0) as total_income 
                  FROM orders
                  WHERE created_at BETWEEN :start_date AND :end_date";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':start_date', $startDate . ' 00:00:00');
        $stmt->bindValue(':end_date', $endDate . ' 23:59:59');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($result['total_income'] ?? 0);
    }

    public function getMonthlySales($year)
    {
        $query = "SELECT 
                    MONTH(created_at) as month,
                    COALESCE(SUM(total_amount), 0) as total_sales
                  FROM orders
                  WHERE YEAR(created_at) = :year
                  GROUP BY MONTH(created_at)
                  ORDER BY month";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalPurchaseExpenses($startDate, $endDate)
    {
        $query = "SELECT COALESCE(SUM(total_amount), 0) as total_expenses 
                  FROM purchases
                  WHERE purchase_date BETWEEN :start_date AND :end_date";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':start_date', $startDate . ' 00:00:00');
        $stmt->bindValue(':end_date', $endDate . ' 23:59:59');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($result['total_expenses'] ?? 0);
    }
}