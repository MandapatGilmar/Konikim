<?php
include 'db_config.php';

if (isset($_POST['id'])) {
    $purchaseId = mysqli_real_escape_string($conn, $_POST['id']); // Prevent SQL Injection

    // Query the database to fetch purchase order details
    $orderQuery = mysqli_query($conn, "SELECT * FROM purchase_orders WHERE id = '$purchaseId'");
    $orderDetails = mysqli_fetch_assoc($orderQuery);

    if ($orderDetails) {
        // Fetch related items for this purchase order
        $itemsQuery = mysqli_query($conn, "SELECT * FROM purchase_order_items WHERE purchase_order_id = '$purchaseId'");

        // Start the table
        echo "<table class='table table-striped table-bordered'>";
        // Table headers
        echo "<thead><tr class='text-light bg-navy'>
            <th class='text-center py-1 px-2'>#</th>
            <th class='text-center py-1 px-2'>Qty</th>
            <th class='text-center py-1 px-2'>Unit</th>
            <th class='text-center py-1 px-2'>Item</th>
            <th class='text-center py-1 px-2'>Attributes</th>
            <th class='text-center py-1 px-2'>Price</th>
            <th class='text-center py-1 px-2'>Total</th>
        </tr></thead>";
        echo "<tbody>";

        // Display each item in a table row
        $count = 1;
        while ($itemRow = mysqli_fetch_assoc($itemsQuery)) {
            $totalPrice = $itemRow['productprice'] * $itemRow['productquantity'];
            echo "<tr>
                <td class='text-center'>{$count}</td>
                <td class='text-center'>{$itemRow['productquantity']}</td>
                <td class='text-center'>{$itemRow['productunit']}</td>
                <td class='text-center'>{$itemRow['productname']}</td>
                <td class='text-center'>{$itemRow['productattributes']}</td>
                <td class='text-center'>₱{$itemRow['productprice']}</td>
                <td class='text-center'>₱{$totalPrice}</td>
            </tr>";
            $count++;
        }

        echo "</tbody></table>";

        // Display purchase order totals
        echo "<div class='totals'>
            <p><strong>Subtotal:</strong> ₱{$orderDetails['poSubtotal']}</p>
            <p><strong>Discount:</strong> ₱{$orderDetails['poDiscountTotal']}</p>
            <p><strong>Tax:</strong> ₱{$orderDetails['poTaxTotal']}</p>
            <p><strong>Grand Total:</strong> ₱{$orderDetails['poGrandtotal']}</p>
        </div>";
    } else {
        echo "<p class='text-danger'><b>Purchase order details not found.</b></p>";
    }
}
