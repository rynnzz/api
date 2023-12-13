<?php
include('dbconfig.php');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($request->id_num) && isset($request->newStatus)) {
        $id_num = $request->id_num;
        $newStatus = $request->newStatus;

        $sql = "UPDATE users SET status = '$newStatus' WHERE id_num = '$id_num'";

        if ($conn->query($sql) === TRUE) {
            $response['success'] = true;
            $response['message'] = "Status updated successfully";
        } else {
            $response['success'] = false;
            $response['message'] = "Error updating status: " . $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Missing data in the request";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
