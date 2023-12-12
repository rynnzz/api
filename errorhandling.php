<?php

include_once('dbconfig.php');

// Assuming you are using POST method to send data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve data from the request
    $requestData = json_decode(file_get_contents("php://input"), true);

    // Check if the 'id_num' key exists in the request data
    if (isset($requestData['id_num'])) {
        $idNum = $requestData['id_num'];

        // Check if the ID number exists in the database (replace this with your actual database check)
        $idExists = checkIdExistsInDatabase($idNum, $conn, 'users'); // Pass the missing arguments

        // Return the result as JSON
        echo json_encode(["exists" => $idExists]);
    } else {
        // Return an error if 'id_num' key is not provided
        http_response_code(400);
        echo json_encode(["error" => "ID number not provided"]);
    }
} else {
    // Return an error if the request method is not POST
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}

// Function to check if ID number exists in the database (replace this with your actual database check)
function checkIdExistsInDatabase($idNum, $conn, $tableName)
{
    // Ensure $tableName is properly sanitized to prevent SQL injection

    $sql = "SELECT COUNT(*) as count FROM $tableName WHERE id_num = '$idNum'";
    $result = $conn->query($sql);

    if ($result === false) {
        echo json_encode(['error' => 'Error executing query']);
    } else {
        $row = $result->fetch_assoc();
        $count = $row['count'];

        return $count > 0; // Return the result instead of echoing
    }
}

?>

