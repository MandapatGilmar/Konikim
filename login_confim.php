<?php
// Include your database configuration here
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a new session and save the username to the session
            session_start();
            $_SESSION['username'] = $username;
            // Redirect to dashboard or wherever you want
            header("Location: dashboard.php");
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Username does not exist";
    }

    mysqli_close($conn);
}
