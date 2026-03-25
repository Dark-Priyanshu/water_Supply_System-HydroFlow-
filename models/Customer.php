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

    public function getCustomerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateCustomer($id, $name, $mobile, $village, $farm_name, $connection_no, $pipe_size) {
        $stmt = $this->conn->prepare("UPDATE customers SET farmer_name = ?, mobile = ?, village = ?, farm_name = ?, connection_no = ?, pipe_size = ? WHERE customer_id = ?");
        $stmt->bind_param("ssssssi", $name, $mobile, $village, $farm_name, $connection_no, $pipe_size, $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
