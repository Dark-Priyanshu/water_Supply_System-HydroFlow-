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
}
?>
