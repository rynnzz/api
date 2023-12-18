<?php
include('dbconfig.php');

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === "getAnnouncements") {
    getAnnouncements($conn);
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle other POST requests as needed
}

function getAnnouncements($conn) {
    $sql = "SELECT * FROM posts WHERE category = 'Announcement' ORDER BY id DESC";

    $result = $conn->query($sql);

    if ($result) {
        $announcements = [];
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
        echo json_encode($announcements);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error fetching announcements: " . $conn->error]);
    }
}
?>
