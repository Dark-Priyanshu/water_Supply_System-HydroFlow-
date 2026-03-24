<?php
class Supply {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function recordSupply($customer_id, $motor_id, $date, $start_time, $end_time, $total_hours, $rate, $total_amount) {
        $stmt = $this->conn->prepare("INSERT INTO water_supply (customer_id, motor_id, date, start_time, end_time, total_hours, rate, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssddd", $customer_id, $motor_id, $date, $start_time, $end_time, $total_hours, $rate, $total_amount);
        return $stmt->execute();
    }

    public function getAllSupply() {
        $query = "SELECT s.*, c.farmer_name, m.motor_name 
                  FROM water_supply s 
                  JOIN customers c ON s.customer_id = c.customer_id 
                  JOIN motors m ON s.motor_id = m.motor_id 
                  ORDER BY s.date DESC, s.start_time DESC";
        return $this->conn->query($query);
    }
}
?>
