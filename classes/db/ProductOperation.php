<?php
class ProductOperations {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->conn; 
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
}
?>
