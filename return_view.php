<?php
include 'db_config.php';

if (isset($_POST['id'])) {
    $returnId = mysqli_real_escape_string($conn, $_POST['id']);

    $returnQuery = mysqli_query($conn, "SELECT * FROM return_orders WHERE id = '$returnId'");
    $returnDetails = mysqli_fetch_assoc($returnQuery);

    if ($returnDetails) {
        $returnCode = $returnDetails['returncode'];
        $supplierName = $returnDetails['supplier'];

        $itemsQuery = mysqli_query($conn, "SELECT roi.*, pl.productattributes 
                                           FROM return_order_items roi 
                                           LEFT JOIN product_list pl ON roi.product_id = pl.id 
                                           WHERE roi.return_order_id = '$returnId'");
        echo "<div style='text-align: center; margin-bottom: 20px;'>";

        echo "<img src='img/logo.png' alt='Logo' style='width: 100px; height: auto;'>";
        echo "<div style='text-align: center; margin-bottom: 20px;'>
                <h3 style='color: #02964C;'>Return Order</h3>
                <p style='margin: 0;'><strong>Return Order Code:</strong> {$returnCode}</p>
                <p style='margin: 0;'><strong>Supplier:</strong> {$supplierName}</p>
                <p style='margin: 0;'><strong>Date:</strong> " . date("Y-m-d H:i:s") . "</p>
              </div>";

        echo "<table style='width: 100%; border-collapse: collapse;'>
              <thead style='background-color: #02964C; color: white;'>
                <tr>
                    <th>#</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Item</th>
                    <th>Attributes</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
              </thead><tbody>";

        $count = 1;
        while ($itemRow = mysqli_fetch_assoc($itemsQuery)) {
            $totalPrice = $itemRow['productprice'] * $itemRow['productquantity'];
            echo "<tr>
                    <td>{$count}</td>
                    <td>{$itemRow['productquantity']}</td>
                    <td>{$itemRow['productunit']}</td>
                    <td>{$itemRow['productname']}</td>
                    <td>{$itemRow['productattributes']}</td>
                    <td>₱{$itemRow['productprice']}</td>
                    <td>₱{$totalPrice}</td>
                  </tr>";
            $count++;
        }

        echo "</tbody></table>";
        // Assuming you have subtotal, discount, tax, and grand total fields in return_orders table
        echo "<div style='text-align: right; margin-top: 10px;'>
                <p><strong>Subtotal:</strong> ₱{$returnDetails['subtotal']}</p>
                <!-- Add additional fields for discount, tax, and grand total if they exist -->
              </div>";
    } else {
        echo "<p class='text-danger'><b>Return order details not found.</b></p>";
    }
}
