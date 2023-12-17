<?php
include 'db_config.php';

if (isset($_POST['id'])) {
    $purchaseId = mysqli_real_escape_string($conn, $_POST['id']); // Prevent SQL Injection

    // Query the database to fetch purchase order details
    $orderQuery = mysqli_query($conn, "SELECT * FROM purchase_orders WHERE id = '$purchaseId'");
    $orderDetails = mysqli_fetch_assoc($orderQuery);

    if ($orderDetails) {
        // Fetch the purchase code
        $purchaseCode = $orderDetails['purchasecode']; // Replace 'purchasecode' with the actual column name in your database
        $supplierName = $orderDetails['supplier']; // Replace 'suppliername' with the actual column name in your database
        // Fetch related items for this purchase order
        $itemsQuery = mysqli_query($conn, "SELECT * FROM purchase_order_items WHERE purchase_order_id = '$purchaseId'");

        echo "<div style='text-align: center; margin-bottom: 20px;'>";

        echo "<img src='img/logo.png' alt='Logo' style='width: 100px; height: auto;'>";
        // Header of the receipt
        echo "<div style='text-align: center; margin-bottom: 20px;'>
                <h3 style='color: #02964C;'>Purchase Order</h3>
                <p style='margin: 0;'><strong>Purchase Order Code:</strong> {$purchaseCode}</p> <!-- Use the fetched purchase code here -->
                <p style='margin: 0;'><strong>Supplier:</strong> {$supplierName}</p> 
                <p style='margin: 0;'><strong>Date:</strong>" . date("Y-m-d H:i:s") . "</p>
              </div>";

        // Start the table with a simpler design
        echo "<table style='width: 100%; border-collapse: collapse;'>";

        // Table headers with color
        echo "<thead style='background-color: #02964C; color: white;'>
                <tr>
                    <th class='text-center py-1 px-2'>#</th>
                    <th class='text-center py-1 px-2'>Qty</th>
                    <th class='text-center py-1 px-2'>Unit</th>
                    <th class='text-center py-1 px-2'>Item</th>
                    <th class='text-center py-1 px-2'>Attributes</th>
                    <th class='text-center py-1 px-2'>Price</th>
                    <th class='text-center py-1 px-2'>Total</th>
                </tr>
              </thead>";

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
        echo "<div style='text-align: right; margin-top: 10px;'>
                <p><strong>Subtotal:</strong> ₱{$orderDetails['poSubtotal']}</p>
                <p><strong>Discount:</strong> ₱{$orderDetails['poDiscountTotal']}</p>
                <p><strong>Tax:</strong> ₱{$orderDetails['poTaxTotal']}</p>
                <p><strong>Grand Total:</strong> <span style='color: #02964C;'>₱{$orderDetails['poGrandtotal']}</span></p>
              </div>";
    } else {
        echo "<p class='text-danger'><b>Purchase order details not found.</b></p>";
    }
}
