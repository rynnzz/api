<?php
include('dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $checkUsernameQuery = "SELECT * FROM teachers WHERE username = '$username'";
    $checkUsernameResult = $conn->query($checkUsernameQuery);

    if ($checkUsernameResult->num_rows > 0) {
        echo json_encode(['success' => false, 'errorCode' => 'USERNAME_EXISTS', 'message' => 'Username already exists. Please choose a different username.']);
        $conn->close();
        exit;
    }

    $sql = "INSERT INTO teachers (username, password) 
            VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error during registration: ' . $conn->error]);
    }
}

$conn->close();
?>
