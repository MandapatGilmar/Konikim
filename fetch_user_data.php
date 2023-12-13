<?php
// Start the session
session_start();

// Include your database connection script
include 'db_config.php';

// Check if user is logged in
if (isset($_SESSION['user_type'])) {
    $user_id = $_SESSION['user_type'];

    // Prepare and execute the query
    $query = "SELECT username, user_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} else {
    echo json_encode(["error" => "Not logged in"]);
}
