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
include 'db_config.php';



// Generate Purchase Order Code
$query = "SELECT id FROM return_orders ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$returncode = "RC-00001";

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $lastid = $row['id'];

    if (!empty($lastid)) {
        $code = str_replace("RC-", "", $lastid);
        $retcode = str_pad($code + 1, 5, '0', STR_PAD_LEFT);
        $returncode = "RC-" . $retcode;
    }
}

// Check for form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier = mysqli_real_escape_string($conn, $_POST['supplierValue']);
    $returncode = mysqli_real_escape_string($conn, $_POST['returncode']);
    $productIds = $_POST['product_id'];
    $productQuantities = $_POST['product_quantity'];
    $productPrices = $_POST['product_price'];
    $productUnitPrices = $_POST['product_total'];

    $poGrandtotal = $_POST['grand-total'];

    // Calculating total discount and tax based on percentages

    $conn->begin_transaction();

    try {
        // Insert into purchase_orders
        $orderStmt = $conn->prepare("INSERT INTO return_orders (returncode, supplier, subtotal, dateCreated) VALUES (?, ?, ?, NOW())");
        $orderStmt->bind_param("ssd", $returncode, $supplier, $poGrandtotal);
        $orderStmt->execute();

        $return_order_id = $conn->insert_id;

        // Prepare statement for inserting into purchase_order_items
        $itemStmt = $conn->prepare("INSERT INTO return_order_items (return_order_id, product_id, productname, productunit, productattributes, productcategory, productprice, productquantity, productunitprice) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($productIds as $i => $productId) {
            // Fetch product details from the database
            $productQuery = "SELECT productname, productunit, productattributes, productcategory FROM product_list WHERE id = ?";
            $productStmt = $conn->prepare($productQuery);
            $productStmt->bind_param("i", $productId);
            $productStmt->execute();
            $productResult = $productStmt->get_result();
            $productDetails = $productResult->fetch_assoc();

            $productName = $productDetails['productname'];
            $productUnit = $productDetails['productunit'];
            $productAttributes = $productDetails['productattributes'];
            $productCategory = $productDetails['productcategory'];

            // Calculate item-level discount and tax based on individual item price and quantity


            // Insert into purchase_order_items
            $itemStmt->bind_param("iisssssdd", $return_order_id, $productId, $productName, $productUnit, $productAttributes, $productCategory, $productPrices[$i], $productQuantities[$i], $productUnitPrices[$i]);
            $itemStmt->execute();


            if ($itemStmt->error) {
                throw new Exception("Error: " . htmlspecialchars($itemStmt->error));
            }
        }

        // Commit transaction
        $conn->commit();
        header("Location: return_list.php");
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo $e->getMessage();
        exit;
    }
}



// Rest of your code...
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
                <?php if ($_SESSION['user_type'] == 'Administrator') : ?>
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

                <?php endif; ?>
            </ul>
        </aside>
        <!-- End Sidebar -->

        <!-- Main -->
        <main class="main-container">
            <div class="main-title">
                <p class="font-weight-bold">RETURN ORDERS</p>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Add Return Order</h3>
                    </div>
                </div>
                <form action="<?php echo ($_SERVER["PHP_SELF"]) ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="productcode">Return Order Code</label>
                            <input type="text" class="form-control" name="returncode" value="<?php echo $returncode; ?>" readonly required>
                        </div>
                        <div class="col-md-3">
                            <label for="supplierDropdown">Select Supplier:</label>
                            <select id="supplierDropdown" class="form-control" name="supplierDropdown">
                                <option value="">Select Supplier</option>
                                <?php
                                $query = "SELECT DISTINCT productsupplier FROM product_list ORDER BY productsupplier";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['productsupplier'] . "'>" . $row['productsupplier'] . "</option>";
                                }
                                ?>
                            </select>
                            <input type="hidden" id="supplierValue" name="supplierValue">
                        </div>
                        <div class="col-md-3">
                            <label for="itemDropdown">Select Item:</label>
                            <select id="itemDropdown" class="form-control" name="itemDropdown" onchange="updatePriceAndUnit()">
                                <option value="1" data-name="Item Name" data-unit="Unit" data-attributes="Attributes">Item Name</option>
                                <?php
                                $query = "SELECT id, productname, productprice, productunit FROM product_list ORDER BY productname";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['id'] . "' data-price='" . $row['productprice'] . "' data-unit='" . $row['productunit'] . "'>" . $row['productname'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>


                    </div>
                    <div class="row" style="padding-bottom: 10px; padding-top: 10px;">
                        <div class="col-md-3">
                            <label for="productunit">Product Unit</label>
                            <input type="text" id="productunit" class="form-control" name="productunit" readonly required>
                        </div>
                        <div class="col-md-3">
                            <label for="productattributes">Product Attributes</label>
                            <input type="text" id="productattributes" class="form-control" name="productattributes" readonly required>
                        </div>
                        <div class="col-md-2">
                            <label for="productquantity">Product Quantity</label>
                            <input type="number" class="form-control" name="productquantity" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" name="add" id="add" class="btn btn-primary" style="margin-top: 25px;  margin-left: 15px; background-color: #02964C; border-color: #02964C;"><span class="glyphicon glyphicon-plus"></span>Add Item</button>
                        </div>
                        <script>
                            document.getElementById('add').addEventListener('click', function() {
                                // Set the hidden input value
                                var supplierDropdown = document.getElementById('supplierDropdown');
                                document.getElementById('supplierValue').value = supplierDropdown.value;

                                // Disable the dropdown
                                supplierDropdown.disabled = true;
                            });
                        </script>
                        <div class="col-md-2">
                            <input type="hidden" id="productprice" class="form-control" name="productprice" readonly required>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered" id="list">
                        <colgroup>
                            <col width="5%">
                            <col width="5%">
                            <col width="10%">
                            <col width="10%">
                            <col width="20%">
                            <col width="20%">
                            <col width="20%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr class="text-light bg-navy">
                                <th class="text-center py-1 px-2" scope="col"></th>
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
                            <!-- Dynamic rows will be inserted here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right py-1 px-2" colspan="7">Total</th>
                                <th class="text-right py-1 px-2 grand-total">0</th>
                                <input type="hidden" name="grand-total" value="0">
                            </tr>
                        </tfoot>
                    </table>

                    <div class="row" style="margin-top: 1%">
                        <div class="col-md-6">
                            <button type="text" name="submit" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;">Submit</button>
                            <a href="return_list.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;"> View Return Order List</a>
                        </div>
                    </div>
            </div>


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

                        }
                    });
                } else {
                    document.getElementById('productunit').value = '';
                    document.getElementById('productattributes').value = '';
                    document.getElementById('productprice').value = '';

                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addItemButton = document.querySelector("button[name='add']");
            const tableBody = document.getElementById('list').getElementsByTagName('tbody')[0];
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

                const grandTotal = subtotal;
                // Update the footer of the table

                document.querySelector('.grand-total').textContent = grandTotal.toFixed(2);
                document.querySelector("input[name='grand-total']").value = grandTotal.toFixed(2);
            }



        });
    </script>




    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
</body>

</html>