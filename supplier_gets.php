<?php
require_once 'db_config.php';

$product = isset($_GET['product']) ? $_GET['product'] : '';

// Check if the product parameter is set
if (empty($product)) {
    echo "No product selected.";
    exit;
}

// SQL to fetch suppliers based on the product
$query = "SELECT DISTINCT s.* FROM supplier_list s JOIN product_list p ON s.companyname = p.productsupplier WHERE p.productname = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $product);
$stmt->execute();
$result = $stmt->get_result();

$count = 1; // Initialize a counter for numbering the rows

if ($result->num_rows > 0) {
    // Output each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $count . "</td>";
        echo "<td>" . htmlspecialchars($row['companyname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['staffname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['contactnumber']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
        echo "<td>" . htmlspecialchars($row['creationdate']) . "</td>";
        echo "<td>
                <div style='display: flex; align-items: center;'>
                    <a href='supplier_edit.php?editid=" . htmlentities($row['id']) . "' class='btn btn-warning btn-sm' style='margin-right: 8px; position: relative;'><span class='glyphicon glyphicon-pencil'></span> Edit </a>
                    <a href='supplier.php?delid=" . htmlentities($row['id']) . "' onClick='return confirm(\"Do you really want to remove this record?\")' class='btn btn-danger btn-sm' style='background-color: #cc3c43; border-color: #cc3c43;'><span class='glyphicon glyphicon-trash'></span> Delete </a>
                </div>
              </td>";
        echo "</tr>";
        $count++;
    }
} else {
    echo "<tr><td colspan='8'>No suppliers found for the selected product.</td></tr>";
}
