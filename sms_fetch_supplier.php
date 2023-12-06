<?php
require_once('db_config.php');

$supplierId = $_GET['supplierId'];

$sql = "SELECT staffname, contactnumber FROM supplier_list WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(array('error' => 'No details found'));
}

$stmt->close();
$conn->close();
