<?php
include("dbconn.php");
$con = dbconn();

// Check if the username and password are provided
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to check if the username exists
    $query = "SELECT `password` FROM `users_data` WHERE `username` = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Login successful
            echo json_encode([
                "success" => true,
                "message" => "Login successful"
            ]);
        } else {
            // Wrong password
            echo json_encode([
                "success" => false,
                "message" => "Wrong username or password!"
            ]);
        }
    } else {
        // Username does not exist
        echo json_encode([
            "success" => false,
            "message" => "Wrong username or password!"
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields"
    ]);
}
?>
