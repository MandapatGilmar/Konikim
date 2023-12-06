<?php
include 'db_config.php';

if (isset($_POST['id'])) {
    $purchaseId = $_POST['id'];

    mysqli_begin_transaction($conn);

    try {
        // Update status in purchase_order_list
        $updateStatus = $conn->prepare("UPDATE purchase_order_list SET status = 1 WHERE id = ?");
        $updateStatus->bind_param("i", $purchaseId);
        $updateStatus->execute();

        // Fetch data from purchase_order_list
        $query = $conn->prepare("SELECT * FROM purchase_order_list WHERE id = ?");
        $query->bind_param("i", $purchaseId);
        $query->execute();
        $result = $query->get_result();
        $orderData = $result->fetch_assoc();

        // Insert into inventory_list
        $insertInventory = $conn->prepare("INSERT INTO inventory_list (po_id, supplier, productname, productunit, productattributes, productprice, productquantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertInventory->bind_param("issssid", $purchaseId, $orderData['supplier'], $orderData['productname'], $orderData['productunit'], $orderData['productattributes'], $orderData['productprice'], $orderData['productquantity']);
        $insertInventory->execute();

        mysqli_commit($conn);
        echo 'Success';
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo 'Error: ' . $e->getMessage();
    }

    $conn->close();
}
