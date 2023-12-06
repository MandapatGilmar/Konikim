<?php
include 'db_config.php';
if (isset($_POST['id'])) {
    $purchaseId = mysqli_real_escape_string($conn, $_POST['id']); // Prevent SQL Injection
    // Query the database to fetch product details
    $query = mysqli_query($conn, "SELECT * FROM purchase_order_list WHERE id = '$purchaseId'");
    if ($row = mysqli_fetch_assoc($query)) {
        // Start the table
        echo "<table class='table table-striped table-bordered'>";
        echo "<tr><th>Field</th><th>Value</th></tr>"; // Table headers

        // Display each field in a table row
        echo "<tr><td><strong>Purchase Order Code:</strong></td><td>" . $row['purchasecode'] . "</td></tr>";
        echo "<tr><td><strong>Supplier:</strong></td><td>" . $row['supplier'] . "</td></tr>";
        echo "<tr><td><strong>Product Name:</strong></td><td>" . $row['productname'] . "</td></tr>";
        echo "<tr><td><strong>Product Attributes:</strong></td><td>" . $row['productattributes'] . "</td></tr>";
        echo "<tr><td><strong>Product Category:</strong></td><td>" . $row['productcategory'] . "</td></tr>";
        echo "<tr><td><strong>Product Unit:</strong></td><td>" . $row['productunit'] . "</td></tr>";
        echo "<tr><td><strong>Product Price:</strong></td><td>₱" . $row['productprice'] . "</td></tr>";
        echo "<tr><td><strong>Product Quantity:</strong></td><td>" . $row['productquantity'] . "</td></tr>";
        echo "<tr><td><strong>Purchase Order Subtotal:</strong></td><td>₱" . $row['poSubtotal'] . "</td></tr>";
        echo "<tr><td><strong>Purchase Order Discount:</strong></td><td>₱" . $row['poDiscountTotal'] . "</td></tr>";
        echo "<tr><td><strong>Purchase Order Tax:</strong></td><td>₱" . $row['poTaxTotal'] . "</td></tr>";
        echo "<tr><td><strong>Purchase Order Total:</strong></td><td>₱" . $row['poGrandtotal'] . "</td></tr>";
        echo "<p id='receivedText' style='display:none; text-align:center;'>Received</p>";
        // End the table
        echo "</table>";


        echo "<div style='text-align: center; margin-top: 20px;'>";
        echo "<button id='receivedButton' class='btn btn-info btn-sm' onclick='handleReceived(" . $row['id'] . ")'>Received</button>";
        echo "</div>";


        // Placeholder for the received text
    } else {
        echo "<p class='text-danger'><b>Product details not found.</b></p>";
    }
}
