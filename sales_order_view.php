<?php
include 'db_config.php';

if (isset($_POST['id'])) {
    $salesId = mysqli_real_escape_string($conn, $_POST['id']); // Sanitize the input to prevent SQL Injection

    // Query the database to fetch sales order details
    $salesQuery = mysqli_query($conn, "SELECT * FROM sales_orders WHERE id = '$salesId'");
    $salesDetails = mysqli_fetch_assoc($salesQuery);

    if ($salesDetails) {
        // Fetch the sales code and customer name
        $salesCode = $salesDetails['salescode'];
        $customerName = $salesDetails['customername'];

        // Fetch related items for this sales order
        $itemsQuery = mysqli_query($conn, "SELECT soi.*, pl.productattributes 
                                           FROM sales_order_items soi 
                                           LEFT JOIN product_list pl ON soi.product_id = pl.id 
                                           WHERE soi.sales_order_id = '$salesId'");
        echo "<div style='text-align: center; margin-bottom: 20px;'>";

        echo "<img src='img/logo.png' alt='Logo' style='width: 100px; height: auto;'>";
        // Header of the details display
        echo "<div style='text-align: center; margin-bottom: 20px;'>
                <h3 style='color: #02964C;'>Sales Order</h3>
                <p style='margin: 0;'><strong>Sales Order Code:</strong> {$salesCode}</p>
                <p style='margin: 0;'><strong>Customer:</strong> {$customerName}</p> 
                <p style='margin: 0;'><strong>Date:</strong>" . date("Y-m-d H:i:s") . "</p>
              </div>";

        // Table for displaying order items
        echo "<table style='width: 100%; border-collapse: collapse;'>";

        // Table headers
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

        // Display sales order totals
        echo "<div style='text-align: right; margin-top: 10px;'>
                <p><strong>Subtotal:</strong> ₱{$salesDetails['soSubtotal']}</p>
                <p><strong>Discount:</strong> ₱{$salesDetails['soDiscountTotal']}</p>
                <p><strong>Tax:</strong> ₱{$salesDetails['soTaxTotal']}</p>
                <p><strong>Grand Total:</strong> <span style='color: #02964C;'>₱{$salesDetails['soGrandTotal']}</span></p>
              </div>";
    } else {
        echo "<p class='text-danger'><b>Sales order details not found.</b></p>";
    }
}
