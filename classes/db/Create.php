<?php 
    require_once '../conection/config.php';
    class Create{
        private $conn;

        public function __construct($dbConnection) {
            $this->conn = $dbConnection;
        }
    
        public function createTables() {
            $tables = [
                "CREATE TABLE IF NOT EXISTS Customers (
                    customer_id INT AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(50) NOT NULL,
                    last_name VARCHAR(50) NOT NULL,
                    email VARCHAR(100) NOT NULL UNIQUE,
                    phone VARCHAR(15)
                )",
                "CREATE TABLE IF NOT EXISTS Products (
                    product_id INT AUTO_INCREMENT PRIMARY KEY,
                    product_name VARCHAR(100) NOT NULL,
                    price DECIMAL(10, 2) NOT NULL,
                    stock_quantity INT NOT NULL
                )",
                "CREATE TABLE IF NOT EXISTS Orders (
                    order_id INT AUTO_INCREMENT PRIMARY KEY,
                    customer_id INT NOT NULL,
                    order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
                )",
                "CREATE TABLE IF NOT EXISTS OrderItems (
                    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
                    order_id INT NOT NULL,
                    product_id INT NOT NULL,
                    quantity INT NOT NULL,
                    subtotal DECIMAL(10, 2) NOT NULL,
                    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
                    FOREIGN KEY (product_id) REFERENCES Products(product_id)
                )"
            ];
    
            foreach ($tables as $table) {
                if ($this->conn->query($table) === TRUE) {
                    echo "Tabla creada correctamente.<br>";
                } else {
                    echo "Error al crear la tabla: " . $this->conn->error . "<br>";
                }
            }
        }
    }
?>