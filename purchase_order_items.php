<?php
include 'db_config.php';

if (isset($_POST['supplier'])) {
    $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);
    $query = "SELECT id, productname FROM product_list WHERE productsupplier = '$supplier'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">Select Item</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['productname'] . '</option>';
        }
    } else {
        echo '<option value="">No items available</option>';
    }
}
