<?php
class Customer {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createCustomer($name, $mobile, $village, $farm_name, $connection_no, $pipe_size) {
        $stmt = $this->conn->prepare("INSERT INTO customers (farmer_name, mobile, village, farm_name, connection_no, pipe_size) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $mobile, $village, $farm_name, $connection_no, $pipe_size);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAllCustomers() {
        $query = "SELECT * FROM customers ORDER BY created_at DESC";
        return $this->conn->query($query);
    }
}
?>
