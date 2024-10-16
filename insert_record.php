<?php
include("dbconn.php");
$con = dbconn();

// Debug: Print received POST data
error_log(print_r($_POST, true)); // This will log the POST data to the server's error log

// Check if all the required fields are present
if (isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["farmer_type"]) && isset($_POST["username"]) && isset($_POST["password"])) {
    $name = $_POST["name"];
    $address = $_POST["address"];
    $farmer_type = $_POST["farmer_type"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hash the password for security

    // Check if the username already exists
    $checkQuery = "SELECT username FROM users_data WHERE username = ?";
    $stmtCheck = $con->prepare($checkQuery);
    $stmtCheck->bind_param("s", $username);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        // If the username exists, return a message
        echo json_encode([
            "success" => false,
            "message" => "Username has been used, please use another username!"
        ]);
    } else {
        // Insert new user data
        $query = "INSERT INTO `users_data`(`name`, `address`, `farmer_type`, `username`, `password`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssss", $name, $address, $farmer_type, $username, $password);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Registration successful"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error executing query: " . $stmt->error
            ]);
        }

        $stmt->close();
    }

    $stmtCheck->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields"
    ]);
}
?>
