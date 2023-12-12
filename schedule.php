<?php
include('dbconfig.php');

// Handle GET request to fetch Schedules
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action']) && $_GET['action'] === "getSchedules") {
    getSchedules($conn);
    exit;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action'])) {
        $action = $data['action'];
        if ($action === 'deleteSchedule') {
            handleDeleteSchedule($data, $conn);
        } elseif ($action === 'updateSchedule') {
            handleUpdateSchedule($data, $conn);
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

function getSchedules($conn) {
    // Adjust the SQL query to filter by the "schedule" category
    $sql = "SELECT * FROM posts WHERE category = 'Schedule' ORDER BY id DESC";

    $result = $conn->query($sql);

    if ($result) {
        $schedules = [];
        while ($row = $result->fetch_assoc()) {
            $schedules[] = $row;
        }
        echo json_encode($schedules);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error fetching Schedules: " . $conn->error]);
    }

    $conn->close();
}


function handleDeleteSchedule($data, $conn) {
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid data"]);
        exit;
    }

    $id = $conn->real_escape_string($data['id']);
    $category = $conn->real_escape_string($data['category']);
    $sql = "DELETE FROM posts WHERE id = '$id' AND category = '$category'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Schedule deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error deleting Schedule: " . $conn->error]);
    }
}

function handleUpdateSchedule($data, $conn) {
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
        // If the update is successful, you can send back the updated data
        $updatedData = ["id" => $id, "content" => $content, "date" => $date];
        echo json_encode(["message" => "Schedule updated successfully", "data" => $updatedData]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error updating Schedule"]);
    }
}
?>
