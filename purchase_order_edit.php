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
    <title>Edit Purchase Order</title>

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
                <p class="font-weight-bold">PURCHASE ORDER</p>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Edit Purchase Order</h3>
                    </div>
                </div>
                <form method="POST">
                    <?php
                    // Fetch product details
                    $eid = $_GET['editid'];
                    $poQuery = "SELECT * FROM purchase_orders WHERE id = ?";
                    $stmt = $conn->prepare($poQuery);
                    $stmt->bind_param("i", $eid);
                    $stmt->execute();
                    $poResult = $stmt->get_result();

                    if ($row = $poResult->fetch_assoc()) {
                        $discountPerc = $row['poDiscount']; // Assuming this is the overall discount percentage for the order
                        $taxPerc = $row['poTax']; // Assuming this is the overall tax percentage for the order
                    ?>
                        <div class="row">
                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <label for="purchasecode">Purchase Order Code</label>
                                <input type="text" class="form-control" name="purchasecode" value="<?php echo htmlspecialchars($row['purchasecode']); ?>" readonly required>
                            </div>
                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <label for="supplier">Supplier</label>
                                <input type="text" class="form-control" name="supplier" value="<?php echo htmlspecialchars($row['supplier']); ?>" readonly required>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered" id="list">
                            <colgroup>
                                <col width="5%">
                                <col width="10%">
                                <col width="10%">
                                <col width="20%">
                                <col width="20%">
                                <col width="20%">
                            </colgroup>
                            <thead>
                                <tr class="text-light bg-navy">
                                    <th class="text-center py-1 px-2" scope="col">#</th>
                                    <th class="text-center py-1 px-2" scope="col">Qty</th>
                                    <th class="text-center py-1 px-2" scope="col">Unit</th>
                                    <th class="text-center py-1 px-2" scope="col">Item</th>
                                    <th class="text-center py-1 px-2" scope="col">Attributes</th>
                                    <th class="text-center py-1 px-2" scope="col">Price</th>
                                    <th class="text-center py-1 px-2" scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch items associated with the purchase order
                                $itemsQuery = "SELECT * FROM purchase_order_items WHERE purchase_order_id = ?";
                                $itemsStmt = $conn->prepare($itemsQuery);
                                $itemsStmt->bind_param("i", $eid);
                                $itemsStmt->execute();
                                $itemsResult = $itemsStmt->get_result();
                                $itemNumber = 0;

                                while ($itemRow = $itemsResult->fetch_assoc()) {
                                    $itemNumber++;
                                    $totalPrice = $itemRow['productquantity'] * $itemRow['productprice']; // Calculate total price for each item
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $itemNumber; ?></td>
                                        <td class="text-center"><input type="number" class="form-control" name="productquantity[]" value="<?php echo $itemRow['productquantity']; ?>"></td>
                                        <td class="text-center"><?php echo htmlspecialchars($itemRow['productunit']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($itemRow['productname']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($itemRow['productattributes']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($itemRow['productprice']); ?></td>
                                        <td class="text-center"><?php echo number_format($totalPrice, 2); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Sub Total</th>
                                    <th class="text-right py-1 px-2 sub-total">0</th>
                                    <input type="hidden" name="sub-total" value="0">

                                </tr>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Discount
                                        <input style="width:40px !important" name="discount_perc" type="number" min="0" max="100" value="<?php echo $discountPerc; ?>">%
                                        <input type="hidden" name="discount" value="0">
                                        <input type="hidden" name="poDiscountTotal" value="0">

                                    </th>
                                    <th class="text-right py-1 px-2 discount">0</th>
                                </tr>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Tax
                                        <input style="width:40px !important" name="tax_perc" type="number" min="0" max="100" value="<?php echo $taxPerc; ?>">%
                                        <input type="hidden" name="tax" value="0">
                                        <input type="hidden" name="poTaxTotal" value="0">

                                    </th>
                                    <th class="text-right py-1 px-2 tax">0</th>
                                </tr>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Total</th>
                                    <th class="text-right py-1 px-2 grand-total">0</th>
                                    <input type="hidden" name="grand-total" value="0">
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row" style="margin-top: 1%">
                            <div class="col-md-6">
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
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
        document.addEventListener("DOMContentLoaded", function() {
            const addItemButton = document.querySelector("button[name='add']");
            const tableBody = document.getElementById('list').getElementsByTagName('tbody')[0];
            const subtotalDisplay = document.querySelector('.sub-total');
            let subtotal = 0;

            addItemButton.addEventListener('click', function(event) {
                event.preventDefault();

                const rowNumber = tableBody.rows.length + 1;
                const quantity = parseInt(document.querySelector("input[name='productquantity']").value) || 0;
                const unit = document.getElementById('productunit').value;
                const selectedItem = document.getElementById('itemDropdown').selectedOptions[0];
                const itemName = selectedItem.text;
                const attributes = document.getElementById('productattributes').value;
                const price = parseFloat(document.getElementById('productprice').value) || 0;
                const total = quantity * price;

                // Fetch additional details from the selected item
                const productData = selectedItem.dataset;
                const productName = productData.name;
                const productUnit = productData.unit;
                const productAttributes = productData.attributes;

                const row = tableBody.insertRow();
                row.innerHTML = `
            <td class="text-center"><button class="btn btn-danger btn-sm" onclick="deleteRow(this)">Delete</button></td>
            <td class="text-center">${rowNumber}</td>
            <td class="text-center">${quantity}</td>
            <td class="text-center">${unit}</td>
            <td class="text-center">${itemName}</td>
            <td class="text-center">${attributes}</td>
            <td class="text-center">${price.toFixed(2)}</td>
            <td class="text-center">${total.toFixed(2)}</td>
            <input type="hidden" name="product_id[]" value="${selectedItem.value}">
            <input type="hidden" name="product_quantity[]" value="${quantity}">
            <input type="hidden" name="product_price[]" value="${price}">
            <input type="hidden" name="product_total[]" value="${total}">
            <input type="hidden" name="product_name[]" value="${productName}">
            <input type="hidden" name="product_unit[]" value="${productUnit}">
            <input type="hidden" name="product_attributes[]" value="${productAttributes}">
        `;

                subtotal += total;
                updateFooter();
            });

            window.deleteRow = function(btn) {
                const row = btn.closest('tr'); // More reliable way to get the row
                const rowIndex = row.rowIndex;
                row.remove(); // Removes the row from the table

                recalculateSubtotal(); // Recalculate the subtotal after deletion
                updateFooter(); // Update the table footer
                updateRowNumbers(); // Update row numbers after deletion
            };


            function recalculateSubtotal() {
                subtotal = 0; // Reset subtotal
                const tableRows = tableBody.rows;

                for (let row of tableRows) {
                    const price = parseFloat(row.cells[6].innerText);
                    const quantity = parseInt(row.cells[2].innerText);
                    subtotal += price * quantity; // Ensure correct calculation
                }
            }

            function updateRowNumbers() {
                const tableRows = tableBody.rows;

                for (let i = 0; i < tableRows.length; i++) {
                    tableRows[i].cells[1].innerText = i + 1; // Update row number sequentially
                }
            }

            function updateFooter() {
                const discountPerc = parseFloat(document.querySelector("input[name='discount_perc']").value) / 100 || 0;
                const taxPerc = parseFloat(document.querySelector("input[name='tax_perc']").value) / 100 || 0;
                const discount = subtotal * discountPerc;
                const tax = (subtotal - discount) * taxPerc;
                const grandTotal = subtotal - discount + tax;
                // Update the footer of the table
                subtotalDisplay.textContent = subtotal.toFixed(2);
                document.querySelector("input[name='sub-total']").value = subtotal.toFixed(2);
                document.querySelector("input[name='discount']").value = discount.toFixed(2); // Update the discount total
                document.querySelector("input[name='tax']").value = tax.toFixed(2); // Update the tax total
                document.querySelector('.discount').textContent = discount.toFixed(2);
                document.querySelector('.tax').textContent = tax.toFixed(2);
                document.querySelector('.grand-total').textContent = grandTotal.toFixed(2);
                document.querySelector("input[name='grand-total']").value = grandTotal.toFixed(2);
            }



        });
    </script>




    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
</body>

</html>