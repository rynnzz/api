<?php
include('dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id_num, fname, mname, lname, gname, g_cnum, s_cnum, course, yr_and_sec FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userList = [];
        while ($row = $result->fetch_assoc()) {
            $userList[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $userList]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No users found']);
    }
}

$conn->close();
?>
