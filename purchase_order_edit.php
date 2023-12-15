<?php
session_start();

if (!isset($_SESSION['user_type'])) {
    header('Location: login.php'); // Redirect to login page
    exit();
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['firstname']) && isset($_SESSION['user_type'])) {
    $userFirstName = $_SESSION['firstname']; // Set user's first name from session
    $userType = $_SESSION['user_type']; // Set user's type from session
} else {
    $userFirstName = 'Unknown'; // Set to 'Unknown' if not logged in or firstname not set
    $userType = 'Unknown'; // Set to 'Unknown' if not logged in or user_type not set
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once 'db_config.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Extract and sanitize data from the form
    $purchaseOrderId = filter_input(INPUT_GET, 'editid', FILTER_SANITIZE_NUMBER_INT);
    $subtotal = filter_input(INPUT_POST, 'sub-total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discountPerc = filter_input(INPUT_POST, 'discount_perc', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $taxPerc = filter_input(INPUT_POST, 'tax_perc', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discountTotal = $subtotal * ($discountPerc / 100);
    $taxTotal = $subtotal * ($taxPerc / 100);
    $grandtotal = filter_input(INPUT_POST, 'grand-total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update the purchase_orders table
        $orderStmt = $conn->prepare("UPDATE purchase_orders SET poSubtotal = ?, poDiscount = ?, poDiscountTotal = ?, poTax = ?, poTaxTotal = ?, poGrandtotal = ? WHERE id = ?");
        $orderStmt->bind_param("ddddddi", $subtotal, $discountPerc, $discountTotal, $taxPerc, $taxTotal, $grandtotal, $purchaseOrderId);
        $orderStmt->execute();

        // Update each item in the purchase_order_items table
        foreach ($_POST['productquantity'] as $itemId => $quantity) {
            $itemId = filter_var($itemId, FILTER_SANITIZE_NUMBER_INT);
            $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
            $unitPrice = filter_var($_POST['productunitprice'][$itemId], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $itemDiscount = $unitPrice * ($discountPerc / 100); // Calculate item-level discount
            $itemTax = $unitPrice * ($taxPerc / 100); // Calculate item-level tax

            $itemUpdateQuery = "UPDATE purchase_order_items SET productquantity = ?, productunitprice = ?, poDiscount = ?, poTax = ? WHERE id = ? AND purchase_order_id = ?";
            $itemStmt = $conn->prepare($itemUpdateQuery);
            $itemStmt->bind_param("idddii", $quantity, $unitPrice, $itemDiscount, $itemTax, $itemId, $purchaseOrderId);
            $itemStmt->execute();

            if ($itemStmt->error) {
                throw new Exception("Error: " . htmlspecialchars($itemStmt->error));
            }
            $itemStmt->close();
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to the purchase order list page
        header("Location: purchase_orders.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo $e->getMessage();
        exit;
    } finally {
        $conn->close(); // Ensure the connection is always closed
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
            <div class="header-right" style="margin-left: 900px;">
                <h4><?php echo htmlspecialchars($userFirstName); ?> - <?php echo htmlspecialchars($userType); ?></h4>
            </div>
            <div class="header-right">
                <span class="material-icons-outlined" id="userIcon">account_circle</span>
                <div id="userOptions" class="user-options" style="display: none;">
                    <form>
                        <button type="button" id="logoutButton">Logout</button>
                    </form>
                </div>
            </div>
            <script>
                document.getElementById('userIcon').addEventListener('click', function() {
                    var userOptions = document.getElementById('userOptions');
                    if (userOptions.style.display === 'none') {
                        userOptions.style.display = 'block';
                    } else {
                        userOptions.style.display = 'none';
                    }
                });

                document.getElementById('logoutButton').addEventListener('click', function() {
                    fetch('logout.php') // Make sure the path to logout.php is correct
                        .then(response => {
                            // Redirect to login page or show a logged out message
                            window.location.href = 'login.php'; // Replace 'login.php' with your login page
                        })
                        .catch(error => console.error('Error:', error));
                });
            </script>
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
                        $discountPerc = $row['poDiscount'];
                        $taxPerc = $row['poTax'];
                        $taxTotal = $row['poTaxTotal'];
                        $discountTotal = $row['poDiscountTotal'];
                        $subtotal = $row['poSubtotal'];
                        $grandtotal = $row['poGrandtotal'];
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
                                        <td class="text-center">
                                            <input type="number" class="form-control quantity-input" name="productquantity[<?php echo $itemRow['id']; ?>]" value="<?php echo $itemRow['productquantity']; ?>">
                                        </td>
                                        <td class="text-center"><?php echo htmlspecialchars($itemRow['productunit']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($itemRow['productname']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($itemRow['productattributes']); ?></td>
                                        <td class="text-center">
                                            <input type="text" class="form-control price-input" name="productprice[]" value="<?php echo htmlspecialchars($itemRow['productprice']); ?>" readonly>
                                            <input type="hidden" name="productunitprice[<?php echo $itemRow['id']; ?>]" value="<?php echo $itemRow['productquantity'] * $itemRow['productprice']; ?>">
                                        </td>
                                        <td class="text-center item-total"><?php echo number_format($totalPrice, 2); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Sub Total</th>
                                    <th class="text-right py-1 px-2 sub-total"><?php echo number_format($subtotal, 2); ?></th>
                                    <input type="hidden" name="sub-total" value="<?php echo number_format($subtotal, 2); ?>">
                                </tr>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Discount
                                        <input style="width:40px !important" name="discount_perc" type="number" min="0" max="100" value="<?php echo $discountPerc; ?>">%
                                        <input type="hidden" name="discount" value="0">
                                        <input type="hidden" name="poDiscountTotal" value="0">

                                    </th>
                                    <th class="text-right py-1 px-2 discount"><?php echo number_format($taxTotal, 2); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Tax
                                        <input style="width:40px !important" name="tax_perc" type="number" min="0" max="100" value="<?php echo $taxPerc; ?>">%
                                        <input type="hidden" name="tax" value="0">
                                        <input type="hidden" name="poTaxTotal" value="0">

                                    </th>
                                    <th class="text-right py-1 px-2 tax"><?php echo number_format($discountTotal, 2); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-right py-1 px-2" colspan="6">Total</th>
                                    <th class="text-right py-1 px-2 grand-total"><?php echo number_format($grandtotal, 2); ?></th>
                                    <input type="hidden" name="grand-total" value="<?php echo number_format($grandtotal, 2); ?>">
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

            // Function to recalculate the line item totals, subtotal, discount, tax, and grand total
            function recalculateTotals() {
                let subtotal = 0;
                let discountTotal = 0;
                let taxTotal = 0;

                // Calculate the total for each item and the subtotal
                document.querySelectorAll("#list tbody tr").forEach(row => {
                    const quantityInput = row.querySelector("input[name^='productquantity']");
                    const priceInput = row.querySelector("input[name^='productprice']");
                    const totalCell = row.querySelector(".item-total");
                    const totalInput = row.querySelector("input[name^='productunitprice']");

                    let quantity = parseInt(quantityInput.value) || 0;
                    let price = parseFloat(priceInput.value) || 0;
                    let total = quantity * price;

                    totalCell.textContent = total.toFixed(2);
                    totalInput.value = total; // Update the hidden input for productunitprice
                    subtotal += total;
                });

                // Update subtotal display
                document.querySelector('.sub-total').textContent = number_format(subtotal, 2);
                document.querySelector("input[name='sub-total']").value = number_format(subtotal, 2);

                // Calculate discount
                let discountPerc = parseFloat(document.querySelector("input[name='discount_perc']").value) || 0;
                discountTotal = (subtotal * discountPerc) / 100;

                // Update discount display
                document.querySelector('.discount').textContent = number_format(discountTotal, 2);
                document.querySelector("input[name='discount']").value = number_format(discountTotal, 2);

                // Calculate tax
                let taxPerc = parseFloat(document.querySelector("input[name='tax_perc']").value) || 0;
                taxTotal = ((subtotal - discountTotal) * taxPerc) / 100;

                // Update tax display
                document.querySelector('.tax').textContent = number_format(taxTotal, 2);
                document.querySelector("input[name='tax']").value = number_format(taxTotal, 2);

                // Calculate and update grand total display
                let grandTotal = subtotal - discountTotal + taxTotal;
                document.querySelector('.grand-total').textContent = number_format(grandTotal, 2);
                document.querySelector("input[name='grand-total']").value = number_format(grandTotal, 2);
            }

            // Attach event listeners to quantity and price inputs for recalculating on change
            document.querySelectorAll("#list tbody").forEach(table => {
                table.addEventListener('change', event => {
                    if (event.target.name.startsWith('productquantity') || event.target.name.startsWith('productprice')) {
                        recalculateTotals();
                    }
                });
            });

            // Attach event listeners to discount and tax inputs for recalculating on change
            document.querySelector("input[name='discount_perc']").addEventListener('change', recalculateTotals);
            document.querySelector("input[name='tax_perc']").addEventListener('change', recalculateTotals);

            // Recalculate button triggers the recalculation
            document.getElementById('recalculate').addEventListener('click', recalculateTotals);

            // Function to format numbers as currency (to mimic PHP's number_format function)
            function number_format(number, decimals, decPoint, thousandsSep) {
                decimals = decimals || 0;
                number = parseFloat(number);

                if (!decPoint || !thousandsSep) {
                    decPoint = '.';
                    thousandsSep = ',';
                }

                let roundedNumber = Math.round(Math.abs(number) * ('1e' + decimals)) + '';
                let numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
                let decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
                let formattedNumber = "";

                while (numbersString.length > 3) {
                    formattedNumber = thousandsSep + numbersString.slice(-3) + formattedNumber;
                    numbersString = numbersString.slice(0, -3);
                }

                return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimals ? decPoint + decimalsString : '');
            }

            // Initial calculation on page load
            recalculateTotals();
        });
    </script>




    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
</body>

</html>