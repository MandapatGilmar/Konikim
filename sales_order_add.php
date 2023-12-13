<?php
session_start();

if (!isset($_SESSION['user_type'])) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'db_config.php';



// Generate Purchase Order Code
$query = "SELECT id FROM sales_orders ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$salescode = "SALES-00001";

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $lastid = $row['id'];

    if (!empty($lastid)) {
        $code = str_replace("SALES-", "", $lastid);
        $salcode = str_pad($code + 1, 5, '0', STR_PAD_LEFT);
        $salescode = "SALES-" . $salcode;
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
            <div class="header-left">
                <span class="material-icons-outlined">search</span>
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
                    <li class="sidebar-list-item">
                        <a href="cms.php" target="_self">
                            <span class="material-icons-outlined">settings</span> Settings
                        </a>
                    </li>
                <?php endif; ?>
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
                <form action="<?php echo ($_SERVER["PHP_SELF"]) ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="salescode">Sales Order Code</label>
                            <input type="text" class="form-control" name="salescode" value="<?php echo $salescode; ?>" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="customername">Customer Name</label>
                            <input type="text" id="customername" class="form-control" name="customername" required>
                        </div>
                    </div>
                    <div class="row" style="padding-bottom: 10px; padding-top: 10px;">
                        <div class="col-md-3">
                            <label for="productDropdown">Select Product Name:</label>
                            <select id="productDropdown" class="form-control" name="productDropdown">
                                <option value="">Select Product</option>
                                <?php
                                require_once('db_config.php');

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // Adjust the query to join the inventory table with the product_list table
                                $sql = "SELECT p.id, p.productname FROM product_list p JOIN inventory i ON p.id = i.product_id";
                                $result = $conn->query($sql);

                                if ($result) {
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["id"] . "'>" . htmlspecialchars($row["productname"]) . "</option>";
                                        }
                                    } else {
                                        echo "<option>No products found</option>";
                                    }
                                } else {
                                    echo "<option>Error in fetching products</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="productunit">Units</label>
                            <input type="text" class="form-control" id="productunit" name="productunit" required readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="productquantity">Product Quantity</label>
                            <input type="number" class="form-control" name="productquantity" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" name="add" id="add" class="btn btn-primary" style="margin-top: 25px;  margin-left: 15px; background-color: #02964C; border-color: #02964C;"><span class="glyphicon glyphicon-plus"></span>Add Item</button>
                        </div>
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
                        </colgroup>
                        <thead>
                            <tr class="text-light bg-navy">
                                <th class="text-center py-1 px-2" scope="col"></th>
                                <th class="text-center py-1 px-2" scope="col">#</th>
                                <th class="text-center py-1 px-2" scope="col">Qty</th>
                                <th class="text-center py-1 px-2" scope="col">Unit</th>
                                <th class="text-center py-1 px-2" scope="col">Item</th>
                                <th class="text-center py-1 px-2" scope="col">Price</th>
                                <th class="text-center py-1 px-2" scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be inserted here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right py-1 px-2" colspan="6">Sub Total</th>
                                <th class="text-right py-1 px-2 sub-total">0</th>
                                <input type="hidden" name="sub-total" value="0">

                            </tr>
                            <tr>
                                <th class="text-right py-1 px-2" colspan="6">Discount <input style="width:40px !important" name="discount_perc" type="number" min="0" max="100" value="0">%
                                    <input type="hidden" name="discount" value="0">
                                    <input type="hidden" name="poDiscountTotal" value="0">

                                </th>
                                <th class="text-right py-1 px-2 discount">0</th>
                            </tr>
                            <tr>
                                <th class="text-right py-1 px-2" colspan="6">Tax <input style="width:40px !important" name="tax_perc" type="number" min="0" max="100" value="0">%
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
                            <button type="text" name="submit" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;">Submit</button>
                            <a href="purchase_orders.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;"> View Purchase Order List</a>
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
        document.getElementById("productDropdown").addEventListener("change", function() {
            var productId = this.value;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "inventory_fetch_products.php?productId=" + productId, true);
            xhr.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        try {
                            var response = JSON.parse(this.responseText);
                            document.getElementById("productunit").value = response.productunit;
                            document.getElementById("productprice").value = response.productprice;
                            document.getElementById("in_stocks").value = response.available_stocks;
                        } catch (e) {
                            console.error("Error parsing response: ", e);
                        }
                    } else {
                        console.error("AJAX request failed: ", this.status);
                    }
                }
            };
            xhr.send();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addItemButton = document.getElementById("add");
            const tableBody = document.getElementById('list').getElementsByTagName('tbody')[0];
            const subtotalDisplay = document.querySelector('.sub-total');
            let subtotal = 0;

            addItemButton.addEventListener('click', function(event) {
                event.preventDefault();

                const rowNumber = tableBody.rows.length + 1;
                const quantity = parseInt(document.querySelector("input[name='productquantity']").value) || 0;
                const unit = document.getElementById('productunit').value;
                const productDropdown = document.getElementById('productDropdown');
                const selectedOption = productDropdown.options[productDropdown.selectedIndex];
                const itemName = selectedOption.textContent;
                const price = parseFloat(document.getElementById('productprice').value) || 0;
                const total = quantity * price;

                const row = tableBody.insertRow();
                row.innerHTML = `
            <td class="text-center"><button class="btn btn-danger btn-sm" onclick="deleteRow(this)">Delete</button></td>
            <td class="text-center">${rowNumber}</td>
            <td class="text-center">${quantity}</td>
            <td class="text-center">${unit}</td>
            <td class="text-center">${itemName}</td>
            <td class="text-center">${price.toFixed(2)}</td>
            <td class="text-center">${total.toFixed(2)}</td>
            <input type="hidden" name="product_id[]" value="${selectedOption.value}">
            <input type="hidden" name="product_quantity[]" value="${quantity}">
            <input type="hidden" name="product_price[]" value="${price}">
            <input type="hidden" name="product_total[]" value="${total}">
            <input type="hidden" name="product_name[]" value="${itemName}">
            <input type="hidden" name="product_unit[]" value="${unit}">
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