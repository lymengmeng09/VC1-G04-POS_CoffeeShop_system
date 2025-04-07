<?php
require_once 'Database/Database.php';

class OrderModel {
    // Get all orders with optional filtering
    public function getOrders($search = '', $filter = 'all', $startDate = '', $endDate = '') {
        // Build query
        $sql = "SELECT o.*, c.name as customer_name, c.avatar 
                FROM orders o 
                JOIN customers c ON o.customer_id = c.id 
                WHERE 1=1";
        $params = [];
        
        // Add search condition
        if (!empty($search)) {
            $sql .= " AND (o.id LIKE ? OR c.name LIKE ? OR o.total LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Add filter condition
        if ($filter !== 'all') {
            $sql .= " AND o.status = ?";
            $params[] = $filter;
        }
        
        // Add date range condition
        if (!empty($startDate) && !empty($endDate)) {
            $sql .= " AND o.date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate . ' 23:59:59';
        }
        
        // Order by date
        $sql .= " ORDER BY o.date DESC";
        
        // For demo purposes, return mock data instead of actual query
        return $this->getMockOrders($search, $filter, $startDate, $endDate);
    }
    
    // Get single order by ID
    public function getOrderById($id) {
        $sql = "SELECT o.*, c.name as customer_name, c.avatar, c.email, c.phone, c.address 
                FROM orders o 
                JOIN customers c ON o.customer_id = c.id 
                WHERE o.id = ?";
        
        // For demo purposes, return mock data instead of actual query
        return $this->getMockOrderById($id);
    }
    
    // Get order items
    public function getOrderItems($orderId) {
        $sql = "SELECT * FROM order_items WHERE order_id = ?";
        
        // For demo purposes, return mock data instead of actual query
        return $this->getMockOrderItems($orderId);
    }
    
    // Update order status
    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $params = [$status, $id];
        
        // For demo purposes, just return true
        return true;
    }
    
    // Mock data for demonstration
    private function getMockOrders($search = '', $filter = 'all', $startDate = '', $endDate = '') {
        $orders = [
            [
                'id' => '#7532',
                'customer_id' => 1,
                'customer_name' => 'Brooklyn Cox',
                'avatar' => 'assets/avatars/avatar1.jpg',
                'payment' => 'Cash',
                'time' => '10 min',
                'type' => 'Delivery',
                'status' => 'Delivered',
                'total' => '£12.50',
                'date' => '2023-01-15'
            ],
            [
                'id' => '#7533',
                'customer_id' => 2,
                'customer_name' => 'Alice Kingston',
                'avatar' => 'assets/avatars/avatar2.jpg',
                'payment' => 'Paid',
                'time' => '45 min',
                'type' => 'Collection',
                'status' => 'Collected',
                'total' => '£14.99',
                'date' => '2023-01-14'
            ],
            [
                'id' => '#7534',
                'customer_id' => 3,
                'customer_name' => 'Jamison van',
                'avatar' => 'assets/avatars/avatar3.jpg',
                'payment' => 'Cash',
                'time' => '37 min',
                'type' => 'Delivery',
                'status' => 'Cancelled',
                'total' => '£18.50',
                'date' => '2023-01-13'
            ],
            [
                'id' => '#7535',
                'customer_id' => 4,
                'customer_name' => 'Xia Chin-Ho',
                'avatar' => 'assets/avatars/avatar4.jpg',
                'payment' => 'Paid',
                'time' => '45 min',
                'type' => 'Collection',
                'status' => 'Collected',
                'total' => '£29.00',
                'date' => '2023-01-12'
            ],
            [
                'id' => '#7536',
                'customer_id' => 5,
                'customer_name' => 'Shaunette Al',
                'avatar' => 'assets/avatars/avatar5.jpg',
                'payment' => 'Cash',
                'time' => '14 min',
                'type' => 'Delivery',
                'status' => 'Delivered',
                'total' => '£38.00',
                'date' => '2023-01-11'
            ],
            [
                'id' => '#7537',
                'customer_id' => 6,
                'customer_name' => 'Mark Bove',
                'avatar' => 'assets/avatars/avatar6.jpg',
                'payment' => 'Paid',
                'time' => '50 min',
                'type' => 'Collection',
                'status' => 'Cancelled',
                'total' => '£15.60',
                'date' => '2023-01-10'
            ],
            [
                'id' => '#7538',
                'customer_id' => 7,
                'customer_name' => 'Gregoire Himona',
                'avatar' => 'assets/avatars/avatar7.jpg',
                'payment' => 'Cash',
                'time' => '16 min',
                'type' => 'Delivery',
                'status' => 'Delivered',
                'total' => '£19.00',
                'date' => '2023-01-09'
            ]
        ];
        
        // Apply filters
        $filtered = [];
        foreach ($orders as $order) {
            $matchesSearch = empty($search) || 
                            stripos($order['customer_name'], $search) !== false || 
                            stripos($order['id'], $search) !== false ||
                            stripos($order['total'], $search) !== false;
            
            $matchesFilter = $filter === 'all' || 
                             strtolower($order['status']) === strtolower($filter);
            
            $matchesDateRange = true;
            if (!empty($startDate) && !empty($endDate)) {
                $orderDate = strtotime($order['date']);
                $start = strtotime($startDate);
                $end = strtotime($endDate);
                $matchesDateRange = $orderDate >= $start && $orderDate <= $end;
            }
            
            if ($matchesSearch && $matchesFilter && $matchesDateRange) {
                $filtered[] = $order;
            }
        }
        
        return $filtered;
    }
    
    // Mock data for single order
    private function getMockOrderById($id) {
        return [
            'id' => $id,
            'customer_id' => 1,
            'customer_name' => 'Brooklyn Cox',
            'email' => 'brooklyn@example.com',
            'phone' => '+44 123 456 7890',
            'address' => '123 Main St, London, UK',
            'avatar' => 'assets/avatars/avatar1.jpg',
            'payment' => 'Cash',
            'time' => '10 min',
            'type' => 'Delivery',
            'status' => 'Delivered',
            'total' => '£12.50',
            'subtotal' => '£10.50',
            'delivery_fee' => '£2.00',
            'tax' => '£0.00',
            'date' => '2023-01-15 14:30:00'
        ];
    }
    
    // Mock data for order items
    private function getMockOrderItems($orderId) {
        return [
            [
                'id' => 1,
                'order_id' => $orderId,
                'name' => 'Chicken Burger',
                'quantity' => 1,
                'price' => '£6.50'
            ],
            [
                'id' => 2,
                'order_id' => $orderId,
                'name' => 'French Fries',
                'quantity' => 1,
                'price' => '£2.50'
            ],
            [
                'id' => 3,
                'order_id' => $orderId,
                'name' => 'Coca Cola',
                'quantity' => 1,
                'price' => '£1.50'
            ]
        ];
    }
}

