<?php
// Connect to the database
// Include your database configuration here
include 'db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, password_hash($_POST['password'], PASSWORD_DEFAULT));
    $employee_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
    $user_level = mysqli_real_escape_string($conn, $_POST['user_level']);

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, password, employee_name, user_level) VALUES ('$username', '$password', '$employee_name', '$user_level')";

    if (mysqli_query($conn, $sql)) {
        echo "New user created successfully";
        echo "<script>window.location.href='user.php'</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
