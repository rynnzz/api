<?php
include_once('dbconfig.php');

// Handle GET request to fetch announcements
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === "getAnnouncements") {
    getAnnouncements($conn);
    exit;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action'])) {
        $action = $data['action'];
        if ($action === 'deleteAnnouncement') {
            handleDeleteAnnouncement($data, $conn);
        } elseif ($action === 'updateAnnouncement') {
            handleUpdateAnnouncement($data, $conn);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid action"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Invalid request"]);
    }

    $conn->close();
    exit;
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

    $conn->close();
}

function handleDeleteAnnouncement($data, $conn) {
    error_log(json_encode($data));
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid data"]);
        exit;
    }

    $id = $conn->real_escape_string($data['id']);
    $sql = "DELETE FROM posts WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Announcement deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error deleting announcement: " . $conn->error]);
    }
}


function handleUpdateAnnouncement($data, $conn) {
    if (!isset($data['id']) || !isset($data['content']) || !isset($data['date']) || !isset($data['category'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid data"]);
        exit;
    }

    $id = $conn->real_escape_string($data['id']);
    $content = $conn->real_escape_string($data['content']);
    $date = $conn->real_escape_string($data['date']);
    $category = $conn->real_escape_string($data['category']);

    $sql = "UPDATE posts SET content = '$content', date = '$date' WHERE id = '$id' AND category = '$category'";

    if ($conn->query($sql) === TRUE) {
        $updatedData = ["id" => $id, "content" => $content, "date" => $date];
        echo json_encode(["message" => "Announcement updated successfully", "data" => $updatedData]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error updating announcement"]);
    }
}
?>