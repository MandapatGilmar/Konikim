<?php
require_once 'db_config.php'; // Database configuration

$filename = "Supplier List Report " . date('Y-m-d') . ".csv"; // File name
$sql = "SELECT * FROM supplier_list"; // SQL query
$result = mysqli_query($conn, $sql); // Get number of rows

$array = array(); // Create an array

$file = fopen($filename, "w"); // Open file
$array = array('ID', 'Company Name', 'Staff Name', 'Contact Number', 'Email Address', 'Address', 'Date Creation'); // Create column headings
fputcsv($file, $array); // Write column headings to the file

while ($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
    $companyname = $row['companyname'];
    $staffname = $row['staffname'];
    $contactnumber = $row['contactnumber'];
    $email = $row['email'];
    $address = $row['address'];
    $creationdate = $row['creationdate'];

    $array = array($id, $companyname, $staffname, $contactnumber, $email, $address, $creationdate); // Add values to the array
    fputcsv($file, $array); // Write values to the file



}

fclose($file); // Close the file
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: application/csv; ");
readfile($filename);
unlink($filename);
exit();
