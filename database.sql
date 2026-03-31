CREATE DATABASE IF NOT EXISTS water_supply_db;
USE water_supply_db;

CREATE TABLE admins (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin',
    language VARCHAR(10) DEFAULT 'en',
    theme VARCHAR(20) DEFAULT 'light',
    ui_scale INT DEFAULT 100,
    font_weight VARCHAR(20) DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    farmer_name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    village VARCHAR(100) NOT NULL,
    farm_name VARCHAR(100),
    connection_no VARCHAR(50),
    pipe_size VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE motors (
    motor_id INT PRIMARY KEY AUTO_INCREMENT,
    motor_name VARCHAR(100) NOT NULL,
    horsepower FLOAT NOT NULL,
    location VARCHAR(200),
    status ENUM('active', 'inactive') DEFAULT 'active'
);

CREATE TABLE water_supply (
    supply_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    motor_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    total_hours DECIMAL(5,2) NOT NULL,
    rate DECIMAL(10,2) DEFAULT 125.00,
    total_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (motor_id) REFERENCES motors(motor_id) ON DELETE CASCADE
);

CREATE TABLE bills (
    bill_id INT PRIMARY KEY AUTO_INCREMENT,
    supply_id INT NOT NULL,
    customer_id INT NOT NULL,
    bill_date DATE NOT NULL,
    total_hours DECIMAL(5,2) NOT NULL,
    rate DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (supply_id) REFERENCES water_supply(supply_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    bill_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    method VARCHAR(50) NOT NULL,
    status ENUM('completed', 'failed', 'refunded') DEFAULT 'completed',
    FOREIGN KEY (bill_id) REFERENCES bills(bill_id) ON DELETE CASCADE
);

-- Default admin password is 'admin123'
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$nkhvtSY91KtAsvEJDiaules3aBthhjXx5vbFp2xWcnigsF3ct.iW6');
