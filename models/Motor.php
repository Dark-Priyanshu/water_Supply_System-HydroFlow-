<?php
class Motor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createMotor($name, $hp, $location) {
        $stmt = $this->conn->prepare("INSERT INTO motors (motor_name, horsepower, location) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $hp, $location);
        return $stmt->execute();
    }

    public function getAllMotors() {
        return $this->conn->query("SELECT * FROM motors ORDER BY motor_id DESC");
    }

    public function getMotorById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM motors WHERE motor_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateMotor($id, $name, $hp, $location) {
        $stmt = $this->conn->prepare("UPDATE motors SET motor_name = ?, horsepower = ?, location = ? WHERE motor_id = ?");
        $stmt->bind_param("sdsi", $name, $hp, $location, $id);
        return $stmt->execute();
    }

    public function toggleStatus($id) {
        $motor = $this->getMotorById($id);
        if ($motor) {
            $new_status = ($motor['status'] === 'active') ? 'inactive' : 'active';
            $stmt = $this->conn->prepare("UPDATE motors SET status = ? WHERE motor_id = ?");
            $stmt->bind_param("si", $new_status, $id);
            return $stmt->execute();
        }
        return false;
    }
}
?>
