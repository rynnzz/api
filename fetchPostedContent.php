<?php
include('dbconfig.php');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Get category from the query parameters
    $category = isset($_GET["category"]) ? $conn->real_escape_string($_GET["category"]) : "";

    // Validate category (add more validation as needed)
    if (empty($category)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid category"]);
        exit;
    }

    // Fetch posted content from the database based on the category
    $sql = "SELECT * FROM posts WHERE category = '$category'";
    $result = $conn->query($sql);

    if ($result) {
        $contentArray = [];

        while ($row = $result->fetch_assoc()) {
            $contentArray[] = $row;
        }

        echo json_encode($contentArray);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error fetching content: " . $conn->error]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}

// Close the database connection
$conn->close();
?>
