<?php
include('dbconfig.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data["content"]) || empty($data["date"]) || empty($data["category"])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid data"]);
        exit;
    }

    $content = $conn->real_escape_string($data["content"]);
    $date = $conn->real_escape_string($data["date"]);
    $category = $conn->real_escape_string($data["category"]);
    
    // Check if 'urgent' key exists in $data and assign the value, or default to 0 (not urgent)
    $urgent = isset($data["urgent"]) ? (int)$data["urgent"] : 0;

    $sql = "INSERT INTO posts (content, date, category, urgent) VALUES ('$content', '$date', '$category', '$urgent')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Content posted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error posting content: " . $conn->error]);
    }
}

$conn->close();
?>
