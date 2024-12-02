<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_management";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        fullname VARCHAR(50) NOT NULL UNIQUE,
        blood VARCHAR(50) NOT NULL,
        allergies VARCHAR(50),
        phone_number VARCHAR(20) NOT NULL,
        date_of_birth DATE NOT NULL,
        address TEXT NOT NULL,
        nic_number VARCHAR(20) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        civil_status ENUM('Single', 'Married', 'Divorced', 'Widowed') NOT NULL,
        photo VARCHAR(255)
    )",

    "CREATE TABLE IF NOT EXISTS patient_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        entry_date DATETIME NOT NULL,
        exit_date DATETIME NOT NULL,
        treatment_notes TEXT NOT NULL,
        prescribed_pills TEXT NOT NULL,
        dosage VARCHAR(100) NOT NULL,
        UNIQUE (user_id, entry_date),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS hospitals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        address TEXT,
        phone VARCHAR(20),
        photo BLOB,  -- Column to store the photo
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS answers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        question_id INT,
        answer TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (question_id) REFERENCES questions(id)
    )",

    "CREATE TABLE IF NOT EXISTS password_resets (
        email VARCHAR(100) NOT NULL,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (email, token)
    )"
];

// Execute table creation queries
foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Insert default admin user
$admin_username = 'user';
$admin_password = password_hash('password', PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password) VALUES ('$admin_username', '$admin_password')";

if ($conn->query($sql) === TRUE) {
    echo "Default admin user added successfully.<br>";
} else {
    echo "Error adding admin user: " . $conn->error . "<br>";
}

// Close connection
$conn->close();
?>
