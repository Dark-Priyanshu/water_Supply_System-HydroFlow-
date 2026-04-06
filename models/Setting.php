<?php
class Setting {
    private $conn;
    private $table = 'settings';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Set or update a setting value
     */
    public function set($key, $value) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("sss", $key, $value, $value);
        return $stmt->execute();
    }

    /**
     * Get a setting value by key
     */
    public function get($key, $default = null) {
        $stmt = $this->conn->prepare("SELECT setting_value FROM {$this->table} WHERE setting_key = ?");
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['setting_value'];
        }
        return $default;
    }

    /**
     * Get multiple settings by an array of keys
     */
    public function getMultiple($keys) {
        if (empty($keys)) return [];
        $placeholders = str_repeat('?,', count($keys) - 1) . '?';
        $stmt = $this->conn->prepare("SELECT setting_key, setting_value FROM {$this->table} WHERE setting_key IN ($placeholders)");
        $stmt->bind_param(str_repeat('s', count($keys)), ...$keys);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $settings = [];
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    /**
     * Get all settings
     */
    public function getAll() {
        $result = $this->conn->query("SELECT setting_key, setting_value FROM {$this->table}");
        $settings = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $settings;
    }
}
?>
