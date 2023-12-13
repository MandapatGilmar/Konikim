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

$query = "SELECT id FROM product_list ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Initialize $productcode
$productcode = "PRD-00001";

// Check if the query returned any rows
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $lastid = $row['id'];

    if (!empty($lastid)) {
        $code = str_replace("PRD-", "", $lastid);
        $prodcode = str_pad($code + 1, 5, '0', STR_PAD_LEFT);
        $productcode = "PRD-" . $prodcode;
    }
}

?>


<?php

if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['submit'])) {
    $productcode = $_POST['productcode'];
    $productname = $_POST['productname'];
    $productsupplier = $_POST['supplierDropdown'];
    $supplierCode = $_POST['supplierCode'];
    $category = $_POST['category'];
    $attributes = $_POST['attributes'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];

    $query = "INSERT INTO product_list (productcode,  productsupplier, supplierCode, productname, productcategory, productattributes, productunit, productprice) VALUES ('$productcode', '$productsupplier','$supplierCode' , '$productname', '$category', '$attributes', '$unit', '$price')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: products.php");
    } else {
        echo '<script>alert("Failed to add product. Please try again.")</script>';
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
                        <h3>Add Product</h3>
                    </div>
                </div>
                <form action="<?php echo ($_SERVER["PHP_SELF"]) ?>" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="productcode">Product Code</label>
                            <input type="text" class="form-control" name="productcode" value="<?php echo $productcode; ?>" readonly required>
                        </div>
                        <div class="col-md-3">
                            <label for="supplierDropdown">Select Supplier:</label>
                            <select id="supplierDropdown" class="form-control" name="supplierDropdown">
                                <?php
                                require_once('db_config.php');

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $sql = "SELECT id, companyname FROM supplier_list";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row["companyname"] . "' data-supplier-id='" . $row["id"] . "'>" . $row["companyname"] . "</option>";
                                    }
                                } else {
                                    echo "<option>No suppliers found</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                            <input type="hidden" id="supplierCode" name="supplierCode">
                        </div>
                        <div class="col-md-6">
                            <label for="product">Product Name</label>
                            <input type="text" class="form-control" name="productname" placeholder="Enter Product Name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3" style="padding-top: 10px;">
                            <label for="attributes">Product Category</label>
                            <input type="text" class="form-control" name="category" placeholder="Ex. Bond Paper, Envelope, etc." required>
                        </div>
                        <div class="col-md-6" style="padding-top: 10px;">
                            <label for="attributes">Product Attributes</label>
                            <input type="text" class="form-control" name="attributes" placeholder="Ex. Color: White, Size: 8 1/2 x 11" required>
                        </div>
                        <div class="col-md-3" style="padding-top: 10px;">
                            <label for="attributes">Product Unit</label>
                            <input type="text" class="form-control" name="unit" placeholder="Ex. Ream, Pieces, etc." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3" style="padding-top: 10px;">
                            <label for="price">Product Price</label>
                            <div class="input-group">
                                <span class="input-group-addon">₱</span>
                                <input type="number" class="form-control" name="price" placeholder="Enter Product Price" required>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 1%">
                        <div class="col-md-6">
                            <button type="text" name="submit" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;">Submit</button>
                            <a href="products.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;"> View Product List</a>
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
    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('supplierDropdown').addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var supplierId = selectedOption.getAttribute('data-supplier-id');
                document.getElementById('supplierCode').value = supplierId;
            });
        });
    </script>
</body>

</html>