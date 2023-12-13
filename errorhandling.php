<?php

include_once('dbconfig.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $requestData = json_decode(file_get_contents("php://input"), true);

    if (isset($requestData['id_num'])) {
        $idNum = $requestData['id_num'];

        $idExists = checkIdExistsInDatabase($idNum, $conn, 'users');

        echo json_encode(["exists" => $idExists]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID number not provided"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}

function checkIdExistsInDatabase($idNum, $conn, $tableName)
{
    $sql = "SELECT COUNT(*) as count FROM $tableName WHERE id_num = '$idNum'";
    $result = $conn->query($sql);

    if ($result === false) {
        echo json_encode(['error' => 'Error executing query']);
    } else {
        $row = $result->fetch_assoc();
        $count = $row['count'];

        return $count > 0;
    }
}

?>

