<?php
$servername = "localhost";
$username = "root";
$password = "";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "CREATE DATABASE IF NOT EXISTS cardekho";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

$conn->select_db("cardekho");


$sql = "CREATE TABLE IF NOT EXISTS car_inquiries (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(50),
    address TEXT,
    selected_cars TEXT,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table car_inquiries created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}


$sql = "CREATE TABLE IF NOT EXISTS admin (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table admin created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}


$hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO admin (id, username, password) VALUES (1, 'admin', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "Default admin inserted\n";
} else {
    echo "Error inserting admin: " . $conn->error . "\n";
}


$conn->query("DROP TABLE IF EXISTS header");
$sql = "CREATE TABLE header (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    logo_path VARCHAR(255),
    nav_items TEXT,
    site_name VARCHAR(255) DEFAULT 'CarDekho'
)";

if ($conn->query($sql) === TRUE) {
    echo "Table header created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}


$sql = "INSERT IGNORE INTO header (id, logo_path, nav_items, site_name) VALUES (1, '', '[\"New Cars\",\"Used Cars\",\"Compare\",\"News\"]', 'CarDekho')";
if ($conn->query($sql) === TRUE) {
    echo "Default header inserted\n";
} else {
    echo "Error inserting header: " . $conn->error . "\n";
}


$sql = "ALTER TABLE header ADD COLUMN IF NOT EXISTS site_name VARCHAR(255) DEFAULT 'CarDekho'";
if ($conn->query($sql) === TRUE) {
    echo "Column site_name added successfully\n";
} else {
    echo "Error adding column: " . $conn->error . "\n";
}


$sql = "CREATE TABLE IF NOT EXISTS banner (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table banner created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}


$sql = "CREATE TABLE IF NOT EXISTS cars (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price VARCHAR(100),
    image_path VARCHAR(255),
    mileage VARCHAR(50),
    year VARCHAR(10),
    fuel_type VARCHAR(50),
    body_type VARCHAR(50),
    badge VARCHAR(50),
    category ENUM('most_searched', 'latest') NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table cars created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}


$sql = "CREATE TABLE IF NOT EXISTS footer (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_description TEXT,
    contact_items TEXT,
    footer_columns TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table footer created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$conn->close();
?>