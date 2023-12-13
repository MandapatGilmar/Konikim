<?php
require_once('db_config.php');

$productId = $_GET['productId'];

$sql = "SELECT supplier, productunit, available_stocks FROM inventory WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(array('error' => 'No details found'));
}

$stmt->close();
$conn->close();
