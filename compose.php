<?php
include('dbconfig.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate data (add more validation as needed)
    if (empty($data["content"]) || empty($data["date"]) || empty($data["category"])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid data"]);
        exit;
    }

    // Insert data into the database
    $content = $conn->real_escape_string($data["content"]);
    $date = $conn->real_escape_string($data["date"]);
    $category = $conn->real_escape_string($data["category"]);

    $sql = "INSERT INTO posts (content, date, category) VALUES ('$content', '$date', '$category')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Content posted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error posting content: " . $conn->error]);
    }
}

// Close the database connection
$conn->close();
?>
