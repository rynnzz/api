<?php
include('dbconfig.php');

$sql = "SELECT * FROM admins";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $plainPassword = $row['password'];

        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);

        $updateSql = "UPDATE admins SET password = '$hashedPassword' WHERE username = '$username'";
        if ($conn->query($updateSql) === TRUE) {
            echo "Password for user with ID Number: $username updated successfully<br>";
        } else {
            echo "Error updating password for admin with username $username: " . $conn->error . "<br>";
        }
    }
} else {
    echo "No admins found in the 'admins' table";
}

$conn->close();
?>
