<?php
include('dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        
        $sql = "SELECT * FROM users WHERE id_num = '$userId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $userDetails = $result->fetch_assoc();
            echo json_encode(['success' => true, 'data' => $userDetails]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing user ID parameter']);
    }
}

$conn->close();
?>
