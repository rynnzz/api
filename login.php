<?php

include('dbconfig.php');

$id_num = $_POST['id_num'];
$password = $_POST['password'];

$sqlUser = "SELECT * FROM users WHERE id_num = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $id_num);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

$sqlTeacher = "SELECT * FROM teachers WHERE username = ?";
$stmtTeacher = $conn->prepare($sqlTeacher);
$stmtTeacher->bind_param("s", $id_num);
$stmtTeacher->execute();
$resultTeacher = $stmtTeacher->get_result();

$sqlAdmin = "SELECT * FROM admins WHERE username = ?";
$stmtAdmin = $conn->prepare($sqlAdmin);
$stmtAdmin->bind_param("s", $id_num);
$stmtAdmin->execute();
$resultAdmin = $stmtAdmin->get_result();

$response = array("success" => false, "message" => "Invalid ID or Password");

if ($resultUser->num_rows > 0) {
    $row = $resultUser->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $response = array("success" => true, "message" => "User login successful", "userData" => $row, "userType" => "user");
    } else {
        $response = array("success" => false, "message" => "Invalid ID or Password");
    }
}

if (!$response['success'] && $resultTeacher->num_rows > 0) {
    $rowTeacher = $resultTeacher->fetch_assoc();

    if (password_verify($password, $rowTeacher['password'])) {
        $response = array("success" => true, "message" => "Teacher login successful", "userData" => $rowTeacher, "userType" => "teacher");
    } else {
        $response = array("success" => false, "message" => "Invalid ID or Password for Teacher");
    }
}

if (!$response['success'] && $resultAdmin->num_rows > 0) {
    $rowAdmin = $resultAdmin->fetch_assoc();

    if (password_verify($password, $rowAdmin['password'])) {
        $response = array("success" => true, "message" => "Admin login successful", "userData" => $rowAdmin, "userType" => "admin");
    } else {
        $response = array("success" => false, "message" => "Invalid ID or Password for Admin");
    }
}

echo json_encode($response);

$stmtUser->close();
$stmtAdmin->close();
$conn->close();
?>
