<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'brainware_faculty');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("<div style='font-family:sans-serif;padding:40px;color:red;'>
        <h2>Database Connection Failed</h2>
        <p>" . $conn->connect_error . "</p>
        <p>Please make sure XAMPP MySQL is running.</p>
    </div>");
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
$conn->select_db(DB_NAME);

// Create table if not exists (no demo data seeded)
$conn->query("
    CREATE TABLE IF NOT EXISTS faculty_submissions (
        id              INT AUTO_INCREMENT PRIMARY KEY,
        faculty_name    VARCHAR(150) NOT NULL,
        subject_1       VARCHAR(100) NOT NULL,
        subject_2       VARCHAR(100) NOT NULL,
        subject_3       VARCHAR(100) NOT NULL,
        subject_4       VARCHAR(100) NOT NULL,
        submitted_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");
?>