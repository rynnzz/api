<?php

include('dbconfig.php');



$sql = "SELECT * FROM posts WHERE urgent = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $posts = array();
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    echo json_encode($posts);
} else {
    echo "No urgent posts found.";
}

// Close connection
$conn->close();

?>