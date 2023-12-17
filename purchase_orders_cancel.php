<?php
require_once 'db_config.php';

if (isset($_GET['cancelid'])) {
    $orderId = $_GET['cancelid'];

    // SQL to update the status of the order to 'Cancelled'
    $sql = "UPDATE purchase_orders SET status = 2 WHERE id = '$orderId'";

    if (mysqli_query($conn, $sql)) {
        echo "Order cancelled successfully.";
    } else {
        echo "Error cancelling order: " . mysqli_error($conn);
    }
}
header("Location: purchase_orders.php");
