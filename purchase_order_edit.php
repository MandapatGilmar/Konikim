<?php
require_once 'db_config.php';

if (isset($_POST['update'])) {


    // Rest of your variables
    $purchasecode = mysqli_real_escape_string($conn, $_POST['purchasecode']);
    $supplier = mysqli_real_escape_string($conn, $_POST['supplierDropdown']);
    $productname = mysqli_real_escape_string($conn, $_POST['productname']);
    $productunit = mysqli_real_escape_string($conn, $_POST['productunit']);
    $productcategory = mysqli_real_escape_string($conn, $_POST['productcategory']);
    $productattributes = mysqli_real_escape_string($conn, $_POST['productattributes']);
    $productprice = mysqli_real_escape_string($conn, $_POST['productprice']);
    $productquantity = mysqli_real_escape_string($conn, $_POST['productquantity']);
    $poDiscount = mysqli_real_escape_string($conn, $_POST['poDiscount']);
    $poTax = mysqli_real_escape_string($conn, $_POST['poTax']);
    $poSubtotal = floatval($_POST['poSubtotal']);
    $poGrandtotal = floatval($_POST['poGrandtotal']);
    $poDiscountTotal = floatval($_POST['poDiscountTotal']);
    $poTaxTotal = floatval($_POST['poTaxTotal']);
    $eid = intval($_GET['editid']);

    // Update query
    $query = "UPDATE purchase_order_list SET purchasecode = '$purchasecode', supplier = '$supplier', productname = '$productname', productunit = '$productunit', productcategory = '$productcategory', productattributes = '$productattributes', productprice = '$productprice', productquantity = '$productquantity', poDiscount = '$poDiscount', poTax = '$poTax', poSubtotal = '$poSubtotal', poGrandtotal = '$poGrandtotal', poDiscountTotal = '$poDiscountTotal', poTaxTotal = '$poTaxTotal' WHERE id = $eid";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Purchase Order Updated Successfully!');</script>";
        echo "<script>window.location.href='purchase_orders.php'</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Supplier Add</title>

    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css\dashboard.css">
</head>

<body>
    <div class="grid-container">

        <!-- Header -->
        <header class="header">
            <div class="menu-icon" onclick="openSidebar()">
                <span class="material-icons-outlined">menu</span>
            </div>
            <div class="header-left">
                <span class="material-icons-outlined">search</span>
            </div>
            <div class="header-right">
                <span class="material-icons-outlined">notifications</span>
                <span class="material-icons-outlined">email</span>
                <span class="material-icons-outlined">account_circle</span>
            </div>
        </header>
        <!-- End Header -->

        <!-- Sidebar -->
        <aside id="sidebar">
            <div class="sidebar-title">
                <div class="sidebar-brand">
                    <span class="material-icons-outlined">inventory</span> KONIKIM INC.
                </div>
                <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
            </div>

            <ul class="sidebar-list">
                <hr class="sidebar-divider hr-sidebar-divider">
                <li class="sidebar-list-item">
                    <a href="dashboard.php" target="_self">
                        <span class="material-icons-outlined">dashboard</span> Dashboard
                    </a>
                </li>
                <hr class="sidebar-divider hr-sidebar-divider">
                <li class="sidebar-list-item">
                    <a href="products.php" target="_self">
                        <span class="material-icons-outlined">inventory_2</span> Products
                    </a>
                </li>
                </hr>
                <li class="sidebar-list-item">
                    <a href="inventory.php" target="_self">
                        <span class="material-icons-outlined">fact_check</span> Inventory
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="purchase_orders.php" target="_self">
                        <span class="material-icons-outlined">add_shopping_cart</span> Purchase Orders
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="sales_orders.php" target="_self">
                        <span class="material-icons-outlined">shopping_cart</span> Sales Orders
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="return_list.php" target="_self">
                        <span class="material-icons-outlined">assignment_return</span> Return List
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="supplier.php" target="_self">
                        <span class="material-icons-outlined">groups</span> Supplier List
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="reports.php" target="_self">
                        <span class="material-icons-outlined">poll</span> Reports
                    </a>
                </li>
                <hr class="sidebar-divider hr-sidebar-divider">
                <li class="sidebar-list-item">
                    <a href="user.php" target="_self">
                        <span class="material-icons-outlined">manage_accounts</span> Users
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="sms.php" target="_self">
                        <span class="material-icons-outlined">message</span> SMS
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="cms.php" target="_self">
                        <span class="material-icons-outlined">settings</span> Settings
                    </a>
                </li>
            </ul>
        </aside>
        <!-- End Sidebar -->

        <!-- Main -->
        <main class="main-container">
            <div class="main-title">
                <p class="font-weight-bold">PRODUCT LIST</p>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Add Purchase Order</h3>
                    </div>
                </div>
                <form method="POST">
                    <?php
                    // Fetch product details
                    $eid = $_GET['editid'];
                    $poQuery = "SELECT * FROM purchase_order_list WHERE id = ?";
                    $stmt = $conn->prepare($poQuery);
                    $stmt->bind_param("i", $eid);
                    $stmt->execute();
                    $poResult = $stmt->get_result();

                    if ($row = $poResult->fetch_assoc()) {
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="purchasecode">Purchase Order Code</label>
                                <input type="text" class="form-control" name="purchasecode" value="<?php echo htmlspecialchars($row['purchasecode']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="supplierDropdown">Supplier</label>
                                <input type="text" class="form-control" name="supplierDropdown" value="<?php echo htmlspecialchars($row['supplier']); ?>" readonly required>
                            </div>

                            <div class="col-md-3">
                                <label for="productname">Item</label>
                                <input type="text" class="form-control" name="productname" value="<?php echo htmlspecialchars($row['productname']); ?>" readonly required>
                            </div>
                        </div>
                        <div class="row" style="padding-bottom: 10px; padding-top: 10px;">
                            <div class="col-md-3">
                                <label for="productunit">Product Unit</label>
                                <input type="text" id="productunit" class="form-control" name="productunit" value="<?php echo htmlspecialchars($row['productunit']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="productcategory">Product Category</label>
                                <input type="text" id="productcategory" class="form-control" name="productcategory" value="<?php echo htmlspecialchars($row['productcategory']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="productattributes">Product Attributes</label>
                                <input type="text" id="productattributes" class="form-control" name="productattributes" value="<?php echo htmlspecialchars($row['productattributes']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="productprice">Product Price</label>
                                <input type="number" id="productprice" class="form-control" name="productprice" value="<?php echo htmlspecialchars($row['productprice']); ?>" readonly required>
                            </div>
                        </div>
                        <div class="row" style="padding-left: 200px;">
                            <div class="col-md-3">
                                <label for="productquantity">Product Quantity</label>
                                <input type="number" id="productquantity" class="form-control" name="productquantity" value="<?php echo htmlspecialchars($row['productquantity']); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="poDiscount">Discount (%)</label>
                                <input type="number" id="poDiscount" class="form-control" name="poDiscount" value="<?php echo htmlspecialchars($row['poDiscount']); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="poTax">Tax (%)</label>
                                <input type="number" id="poTax" class="form-control" name="poTax" value="<?php echo htmlspecialchars($row['poTax']); ?>" required>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 1%; margin-bottom: 1%; padding-left: 500px;">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;" onclick="calculateTotals()">Compute</button>
                            </div>
                        </div>

                        <!-- Display Fields -->
                        <div class="row">
                            <div class="col-md-3">
                                <label for="poSubtotal">Subtotal</label>
                                <input type="number" id="poSubtotal" class="form-control" name="poSubtotal" value="<?php echo htmlspecialchars($row['poSubtotal']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="poDiscountTotal">Discount Total</label>
                                <input type="number" id="poDiscountTotal" class="form-control" name="poDiscountTotal" value="<?php echo htmlspecialchars($row['poDiscountTotal']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="poTaxTotal">Tax Total</label>
                                <input type="number" id="poTaxTotal" class="form-control" name="poTaxTotal" value="<?php echo htmlspecialchars($row['poTaxTotal']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="poGrandtotal">Grand Total</label>
                                <input type="number" id="poGrandtotal" class="form-control" name="poGrandtotal" value="<?php echo htmlspecialchars($row['poGrandtotal']); ?>" readonly required>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 1%">
                            <div class="col-md-6">
                                <button type="submit" name="update" class="btn btn-primary" onclick="return checkForRecalculation()">Update</button>
                                <a href="purchase_orders.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;"> View Purchase Order List</a>
                            </div>
                        </div>
                    <?php
                    } else {
                        echo "Purchase Order not found.";
                    }
                    $stmt->close();
                    ?>
                </form>
            </div>

        </main>

        <!-- End Main -->

    </div>

    <!-- Scripts -->
    <!-- ApexCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('supplierDropdown').addEventListener('change', function() {
                var supplier = this.value;
                if (supplier) {
                    $.ajax({
                        type: 'POST',
                        url: 'purchase_order_items.php', // PHP script to get items based on supplier
                        data: 'supplier=' + supplier,
                        success: function(html) {
                            $('#itemDropdown').html(html);
                        }
                    });
                } else {
                    $('#itemDropdown').html('<option value="">Select Item</option>');
                }
            });

            document.getElementById('itemDropdown').addEventListener('change', function() {
                var itemId = this.value;
                if (itemId) {
                    $.ajax({
                        type: 'POST',
                        url: 'purchase_order_details.php', // PHP script to get item details
                        data: 'item_id=' + itemId,
                        success: function(data) {
                            var details = JSON.parse(data);
                            document.getElementById('productunit').value = details.productunit;
                            document.getElementById('productattributes').value = details.productattributes;
                            document.getElementById('productprice').value = details.productprice;
                            document.getElementById('productcategory').value = details.productcategory;
                        }
                    });
                } else {
                    document.getElementById('productunit').value = '';
                    document.getElementById('productattributes').value = '';
                    document.getElementById('productprice').value = '';
                    document.getElementById('productcategory').value = '';
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('itemDropdown').addEventListener('change', function() {
                // This gets the text (product name) of the selected option
                var productName = this.options[this.selectedIndex].text;
                // This sets the value of the hidden input field to the product name
                document.getElementById('productname').value = productName;
            });
        });
    </script>
    <script>
        var needsRecalculation = false;

        function calculateTotals() {
            var productPrice = parseFloat(document.getElementById('productprice').value) || 0;
            var productQuantity = parseFloat(document.getElementById('productquantity').value) || 0;
            var discountPercent = parseFloat(document.getElementById('poDiscount').value) / 100 || 0;
            var taxPercent = parseFloat(document.getElementById('poTax').value) / 100 || 0;

            var subtotal = productPrice * productQuantity;
            var discountTotal = subtotal * discountPercent;
            var taxableAmount = subtotal - discountTotal;
            var taxTotal = taxableAmount * taxPercent;
            var grandTotal = subtotal - discountTotal + taxTotal;

            document.getElementById('poSubtotal').value = subtotal.toFixed(2);
            document.getElementById('poDiscountTotal').value = discountTotal.toFixed(2);
            document.getElementById('poTaxTotal').value = taxTotal.toFixed(2);
            document.getElementById('poGrandtotal').value = grandTotal.toFixed(2);

            needsRecalculation = false;

        }

        function checkForRecalculation() {
            if (needsRecalculation) {
                alert("Please click 'Compute' to recalculate totals before updating.");
                return false;
            }
            return true;
        }

        // Event listeners for fields that require recalculation when changed
        document.addEventListener("DOMContentLoaded", function() {
            var fields = ['productprice', 'productquantity', 'poDiscount', 'poTax']; // Add other field IDs as needed
            fields.forEach(function(fieldId) {
                var field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('change', function() {
                        needsRecalculation = true;
                    });
                }
            });
        });
    </script>




    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
</body>

</html>