<?php
include('dbconfig.php');

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === "getEvents") {
    getEvents($conn);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action'])) {
        $action = $data['action'];
        if ($action === 'deleteEvents') {
            handleDeleteEvent($data, $conn);
        } elseif ($action === 'updateEvent') {
            handleUpdateEvent($data, $conn);
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

function getEvents($conn) {
    $sql = "SELECT * FROM posts WHERE category = 'Event' ORDER BY id DESC";

    $result = $conn->query($sql);

    if ($result) {
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        echo json_encode($events);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error fetching events: " . $conn->error]);
    }

    $conn->close();
}

function handleDeleteEvent($data, $conn) {
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid data"]);
        exit;
    }

    $id = $conn->real_escape_string($data['id']);
    $category = $conn->real_escape_string($data['category']);
    $sql = "DELETE FROM posts WHERE id = '$id' AND category = '$category'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Event deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error deleting event: " . $conn->error]);
    }
}

function handleUpdateEvent($data, $conn) {
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
        echo json_encode(["message" => "Event updated successfully", "data" => $updatedData]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error updating event"]);
    }
}
?>