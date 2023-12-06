<?php
include 'db_config.php';

if (isset($_POST['item_id'])) {
    $itemId = mysqli_real_escape_string($conn, $_POST['item_id']);
    $query = "SELECT productunit, productattributes, productprice, productcategory FROM product_list WHERE id = '$itemId'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(['productunit' => '', 'productattributes' => '', 'productprice' => '', 'productcategory' => '']);
    }
}
