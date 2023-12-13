<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header('Location: login.php'); // Adjust the redirection as needed
exit();
