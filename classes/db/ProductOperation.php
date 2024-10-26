<?php
require_once '../conection/config.php';
class ProductOperations {
    private $config;
    private $conn;

    public function __construct() {
        $this->config = new config();
        $this->conn = $this->config->conn;
    }

    public function getProducts() {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        return $data;
    }

    public function getProductByName($productName) {
        $query = "SELECT * FROM products WHERE product_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt -> bind_param("s", $productName);
        $stmt -> execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getCustomers(){
        $query = "SELECT * FROM customers";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];

        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        return $data;
    }

    public function insertProduct($productName, $price, $stockQuantity) {
        $query = "INSERT INTO Products (product_name, price, stock_quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sdi", $productName, $price, $stockQuantity);
        return $stmt->execute();
    }

    public function updateStockQuantity($productId, $newQuantity) {
        $query = "UPDATE Products SET stock_quantity = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $newQuantity, $productId);
        return $stmt->execute();
    }

    public function deleteOrder($orderId) {
        $queryItems = "DELETE FROM OrderItems WHERE order_id = ?";
        $stmtItems = $this->conn->prepare($queryItems);
        $stmtItems->bind_param("i", $orderId);
        $stmtItems->execute();

        $queryOrder = "DELETE FROM Orders WHERE order_id = ?";
        $stmtOrder = $this->conn->prepare($queryOrder);
        $stmtOrder->bind_param("i", $orderId);
        return $stmtOrder->execute();
    }

    public function getCustomerNamesByOrderId($orderId) {
        $query = "SELECT c.first_name, c.last_name FROM Customers c 
                  JOIN Orders o ON c.customer_id = o.customer_id 
                  WHERE o.order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    public function calculateTotalRevenueByProduct() {
        $query = "SELECT oi.product_id, SUM(oi.subtotal) AS total_revenue 
                  FROM OrderItems oi 
                  GROUP BY oi.product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    public function createOrder($customerId, $orderDate) {
        $query = "INSERT INTO orders (customer_id, order_date) VALUES (?, ?)";   
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $customerId, $orderDate);
        $stmt->execute();
        $orderId = $this->conn->insert_id; 
        $stmt->close();
        
        return $orderId;
    }
    
    public function addOrderItem($orderId, $productId, $quantity, $subtotal) {
        $query = "INSERT INTO orderitems (order_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiid", $orderId, $productId, $quantity, $subtotal); 
        $stmt->execute();
        $stmt->close(); 
    }
    
    
    public function updateStock($productId, $quantity) {
        $query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $productId); 
        $stmt->execute();
        $stmt->close(); 
    }
    
    
    public function updateOrderTotal($orderId, $total) {
        $query = "UPDATE orders SET total = ? WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("di", $total, $orderId); 
        $stmt->execute();
        $stmt->close(); 
    }
    
    
}
?>
