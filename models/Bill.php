<?php
class Bill {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateBill($supply_id, $customer_id, $bill_date, $total_hours, $rate, $total_amount) {
        $stmt = $this->conn->prepare("INSERT INTO bills (supply_id, customer_id, bill_date, total_hours, rate, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisddd", $supply_id, $customer_id, $bill_date, $total_hours, $rate, $total_amount);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function getAllBills() {
        $query = "SELECT b.*, c.farmer_name, c.mobile 
                  FROM bills b 
                  JOIN customers c ON b.customer_id = c.customer_id 
                  ORDER BY b.bill_date DESC, b.bill_id DESC";
        return $this->conn->query($query);
    }
    
    public function getBillById($bill_id) {
        $query = "SELECT b.*, c.*, s.date as supply_date, s.start_time, s.end_time 
                  FROM bills b 
                  JOIN customers c ON b.customer_id = c.customer_id 
                  JOIN water_supply s ON b.supply_id = s.supply_id
                  WHERE b.bill_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $bill_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
