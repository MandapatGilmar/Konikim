<?php
// Include your database configuration here
require_once 'db_config.php';
$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    // Fetch firstname along with other details
    $stmt = $conn->prepare("SELECT id, username, password, user_type, firstname FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Start the session and set session variables
            session_start();
            $_SESSION['loggedin'] = true; // Indicates the user is logged in
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['firstname'] = $row['firstname']; // Store user's first name

            header("Location: dashboard.php");
            exit();
        } else {
            $loginError = "Invalid password.";
        }
    } else {
        $loginError = "Username does not exist.";
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Login | Konikim Inc,</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <input type="checkbox" id="flip">
        <div class="cover">
            <div class="front">
                <img src="img\login.png" alt="">
            </div>
        </div>
        <div class="forms">
            <div class="form-content">
                <div class="login-form">
                    <div class="title">Login</div>
                    <form method="POST">
                        <div class="input-boxes">
                            <div class="input-box">
                                <i class="fas fa-envelope"></i>
                                <input type="text" name="username" placeholder="Username" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" placeholder="Password" required>
                            </div>
                            <?php if (!empty($loginError)) : ?>
                                <p class="error"><?php echo $loginError; ?></p>
                            <?php endif; ?>
                            <div class="button input-box">
                                <input type="submit" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>