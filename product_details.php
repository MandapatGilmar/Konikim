<?php
include 'db_config.php';
if (isset($_POST['id'])) {
    $productId = mysqli_real_escape_string($conn, $_POST['id']); // Prevent SQL Injection
    // Query the database to fetch product details
    $query = mysqli_query($conn, "SELECT * FROM product_list WHERE id = '$productId'");
    if ($row = mysqli_fetch_assoc($query)) {
        // Start the table
        echo "<table class='table table-striped table-bordered'>";
        echo "<tr><th>Field</th><th>Value</th></tr>"; // Table headers

        // Display each field in a table row
        echo "<tr><td><strong>Product Code:</strong></td><td>" . $row['productcode'] . "</td></tr>";
        echo "<tr><td><strong>Supplier:</strong></td><td>" . $row['productsupplier'] . "</td></tr>";
        echo "<tr><td><strong>Product Name:</strong></td><td>" . $row['productname'] . "</td></tr>";
        echo "<tr><td><strong>Product Attributes:</strong></td><td>" . $row['productattributes'] . "</td></tr>";
        echo "<tr><td><strong>Product Category:</strong></td><td>" . $row['productcategory'] . "</td></tr>";
        echo "<tr><td><strong>Product Unit:</strong></td><td>" . $row['productunit'] . "</td></tr>";
        echo "<tr><td><strong>Product Price:</strong></td><td>₱" . $row['productprice'] . "</td></tr>";

        // End the table
        echo "</table>";
    } else {
        echo "<p class='text-danger'><b>Product details not found.</b></p>";
    }
}
