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
require_once 'db_config.php';

if (isset($_POST['update'])) {
    // Using prepared statements for security
    $stmt = $conn->prepare("UPDATE product_list SET productname = ?, productsupplier = ?, productcategory = ?, productattributes = ?, productunit = ?, productprice = ? WHERE id = ?");
    $stmt->bind_param("sssssdi", $productname, $productsupplier, $category, $attributes, $unit, $price, $eid);

    // Set parameters and execute
    $productname = $_POST['productname'];
    $productsupplier = $_POST['supplierDropdown'];
    $category = $_POST['category'];
    $attributes = $_POST['attributes'];
    $unit = $_POST['unit'];
    $price = $_POST['price'];
    $eid = $_GET['editid'];

    if ($stmt->execute()) {
        echo "<script>alert('Product Updated Successfully!')</script>;";
        echo "<script>window.location.href='products.php'</script>";
    } else {
        echo "<script>alert('Product Update Failed!')</script>";
    }

    $stmt->close();
}

// Fetch product details
$eid = $_GET['editid'];
$productQuery = "SELECT * FROM product_list WHERE id = ?";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param("i", $eid);
$stmt->execute();
$productResult = $stmt->get_result();

// Fetch supplier list
$supplierQuery = "SELECT id, companyname FROM supplier_list";
$supplierResult = $conn->query($supplierQuery);
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
                <form method="POST">
                    <?php
                    // Fetch product details
                    $eid = $_GET['editid'];
                    $productQuery = "SELECT * FROM product_list WHERE id = ?";
                    $stmt = $conn->prepare($productQuery);
                    $stmt->bind_param("i", $eid);
                    $stmt->execute();
                    $productResult = $stmt->get_result();

                    if ($row = $productResult->fetch_assoc()) {
                    ?>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="productcode">Product Code</label>
                                <input type="text" class="form-control" name="productcode" value="<?php echo htmlspecialchars($row['productcode']); ?>" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="supplierDropdown">Select Supplier:</label>
                                <select id="supplierDropdown" class="form-control" name="supplierDropdown">
                                    <?php
                                    $supplierQuery = "SELECT id, companyname FROM supplier_list";
                                    $supplierResult = $conn->query($supplierQuery);
                                    while ($supplierRow = $supplierResult->fetch_assoc()) {
                                        $selected = ($row['productsupplier'] == $supplierRow['companyname']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($supplierRow["companyname"]) . "' $selected>" . htmlspecialchars($supplierRow["companyname"]) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="productname">Product Name</label>
                                <input type="text" class="form-control" name="productname" value="<?php echo htmlspecialchars($row['productname']); ?>" placeholder="Enter Product Name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="category">Product Category</label>
                                <input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($row['productcategory']); ?>" placeholder="Ex. Bond Paper, Envelope, etc." required>
                            </div>
                            <div class="col-md-6">
                                <label for="attributes">Product Attributes</label>
                                <input type="text" class="form-control" name="attributes" value="<?php echo htmlspecialchars($row['productattributes']); ?>" placeholder="Ex. Color: White, Size: 8 1/2 x 11" required>
                            </div>
                            <div class="col-md-3">
                                <label for="unit">Product Unit</label>
                                <input type="text" class="form-control" name="unit" value="<?php echo htmlspecialchars($row['productunit']); ?>" placeholder="Ex. Ream, Pieces, etc." required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="price">Product Price</label>
                                <div class="input-group">
                                    <span class="input-group-addon">₱</span>
                                    <input type="number" class="form-control" name="price" value="<?php echo htmlspecialchars($row['productprice']); ?>" placeholder="Enter Product Price" required>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 1%">
                            <div class="col-md-6">
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                <a href="products.php" class="btn btn-success">View Product List</a>
                            </div>
                        </div>
                    <?php
                    } else {
                        echo "Product not found.";
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
    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
</body>

</html>