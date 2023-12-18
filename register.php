<?php
include('dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_num = $data['id_num'];
    $fname = $data['fname'];
    $mname = $data['mname'];
    $lname = $data['lname'];
    $address = $data['address'];
    $age = $data['age'];
    $b_date = $data['b_date'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
    $gname = $data['gname'];
    $g_cnum = $data['g_cnum'];
    $s_cnum = $data['s_cnum'];
    $course = $data['course'];
    $yr_and_sec = $data['yr_and_sec'];
    $status = $data['status'];


    $sql = "INSERT INTO users (id_num, fname, mname, lname, address, age, b_date, password, gname, g_cnum, s_cnum, course, yr_and_sec, status) 
            VALUES ('$id_num', '$fname', '$mname', '$lname', '$address', '$age', '$b_date', '$password', '$gname', '$g_cnum', '$s_cnum', '$course', '$yr_and_sec', '$status')";


    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error during registration: ' . $conn->error]);
    }
}

$conn->close();
?>
