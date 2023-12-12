
<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:9000");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Max-Age: 86400"); // Cache preflight response for 24 hours
    exit();
}

header("Access-Control-Allow-Origin: http://localhost:9000");
header("Access-Control-Allow-Methods: POST, GET"); // Allow both GET and POST methods
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