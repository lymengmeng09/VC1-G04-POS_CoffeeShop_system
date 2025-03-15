<?php
require_once "Database/Database.php";

class ProductModel {
    private $conn;
    private $table = "stocks";
    private const DEFAULT_LIMIT = 5;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Failed to connect to database");
        }
    }

    public function getAllProducts() {
        try {
            $query = "SELECT * FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching products: " . $e->getMessage());
            throw new Exception("Failed to fetch products: " . $e->getMessage());
        }
    }

    public function getProductById($id) {
        try {
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                throw new Exception("Invalid product ID");
            }

            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$product) {
                throw new Exception("Product not found");
            }
            
            return $product;
        } catch (PDOException $e) {
            error_log("Error fetching product: " . $e->getMessage());
            throw new Exception("Failed to fetch product: " . $e->getMessage());
        }
    }

    public function addProduct($name, $price, $quantity, $image) {
        try {
            // Validate inputs
            if (empty($name)) {
                throw new Exception("Product name is required");
            }
            if (!is_numeric($price) || $price < 0) {
                throw new Exception("Invalid price");
            }
            if (!is_numeric($quantity) || $quantity < 0) {
                throw new Exception("Invalid quantity");
            }

            $query = "INSERT INTO " . $this->table . " (name, price, quantity, image) 
                     VALUES (:name, :price, :quantity, :image)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
            
            if ($image === null) {
                $stmt->bindValue(":image", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(":image", $image, PDO::PARAM_STR);
            }

            if (!$stmt->execute()) {
                throw new Exception("Failed to add product");
            }

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error adding product: " . $e->getMessage());
            throw new Exception("Failed to add product: " . $e->getMessage());
        }
    }
    
    public function updateProduct($id, $name, $price, $quantity) {
        try {
            // Validate inputs
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                throw new Exception("Invalid product ID");
            }
            if (empty($name)) {
                throw new Exception("Product name is required");
            }
            if (!is_numeric($price) || $price < 0) {
                throw new Exception("Invalid price");
            }
            if (!is_numeric($quantity) || $quantity < 0) {
                throw new Exception("Invalid quantity");
            }

            $query = "UPDATE " . $this->table . " 
                     SET name = :name, price = :price, quantity = :quantity 
                     WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":price", $price, PDO::PARAM_STR);
            $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception("Failed to update product");
            }

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            throw new Exception("Failed to update product: " . $e->getMessage());
        }
    }

    public function deleteProduct($id) {
        try {
            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                throw new Exception("Invalid product ID");
            }

            // First, get the product to retrieve the image path
            $product = $this->getProductById($id);

            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception("Failed to delete product");
            }

            // If product had an image, delete it from the server
            if ($product['image'] && file_exists($product['image'])) {
                unlink($product['image']);
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            throw new Exception("Failed to delete product: " . $e->getMessage());
        }
    }

    public function getTopSellingProducts($start_date = null, $end_date = null) {
        try {
            $query = "
                SELECT s.id, s.name AS product_name, SUM(oi.quantity) AS total_sold
                FROM " . $this->table . " s
                LEFT JOIN order_items oi ON s.id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.id
            ";

            $params = [];
            if ($start_date && $end_date) {
                // Validate dates
                if (!$this->isValidDate($start_date) || !$this->isValidDate($end_date)) {
                    throw new Exception("Invalid date format");
                }
                $query .= " WHERE o.order_date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $start_date;
                $params[':end_date'] = $end_date;
            }

            $query .= " GROUP BY s.id, s.name 
                       ORDER BY total_sold DESC 
                       LIMIT " . self::DEFAULT_LIMIT;

            $stmt = $this->conn->prepare($query);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching top products: " . $e->getMessage());
            throw new Exception("Failed to fetch top products: " . $e->getMessage());
        }
    }

    private function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
?>
