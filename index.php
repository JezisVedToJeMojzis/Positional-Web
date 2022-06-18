<?php
$servername = "localhost";
$username = "samo";
$password = "mojiS-2000";

//db connection test
try {
    $conn = new PDO("mysql:host=$servername;dbname=PositionalWeb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>