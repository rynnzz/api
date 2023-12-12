<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "userdb";

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM admins";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $plainPassword = $row['password'];

        // Hash the plain text password using password_hash
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        // Update the 'admins' table with the hashed password
        $updateSql = "UPDATE admins SET password = '$hashedPassword' WHERE username = '$username'";
        if ($conn->query($updateSql) === TRUE) {
            echo "Password for admin with username $username updated successfully<br>";
        } else {
            echo "Error updating password for admin with username $username: " . $conn->error . "<br>";
        }
    }
} else {
    echo "No admins found in the 'admins' table";
}

// Close the database connection
$conn->close();
?>
