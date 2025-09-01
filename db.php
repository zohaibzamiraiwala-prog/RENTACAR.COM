<?php
// db.php
$host = 'localhost'; // Assuming localhost; update if different host is needed
$dbname = 'dbxjzphucrwcmu';
$username = 'unkuodtm3putf';
$password = 'htk2glkxl4n4';
 
try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
