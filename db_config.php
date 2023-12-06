<?php
$host = 'localhost';  // Replace with your host name
$dbUser = 'root';    // Replace with your database username
$dbPassword = '';    // Replace with your database password
$dbName = 'konikim_db';  // Replace with your database name

$conn = mysqli_connect($host, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
