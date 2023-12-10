<?php
header("Access-Control-Allow-Origin: http://localhost:9000");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "userdb";

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>