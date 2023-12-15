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

// Connect to the database
require_once 'db_config.php';

// Handle deletion if the 'delid' is set
if (isset($_GET['delid'])) {
    $id = intval($_GET['delid']);
    $sql = mysqli_query($conn, "DELETE FROM supplier_list WHERE id='$id'");
    echo "<script>alert('Supplier deleted successfully');</script>";
    echo "<script>window.location.href='supplier.php'</script>";
}

// Pagination setup
$records_per_page = 10; // Set the number of records per page
$page = '';

if (isset($_GET["page"])) {
    $page = intval($_GET["page"]);
} else {
    $page = 1;
}

$start_from = ($page - 1) * $records_per_page;

// Fetch the relevant records for the current page
$sql = "SELECT * FROM supplier_list LIMIT $start_from, $records_per_page";
$result = mysqli_query($conn, $sql);

// Calculate total number of pages required
$total_pages_sql = "SELECT COUNT(*) FROM supplier_list";
$page_result = mysqli_query($conn, $total_pages_sql);
$total_rows = mysqli_fetch_array($page_result)[0];
$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Supplier</title>

    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css">


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css\dashboard.css">
    <style>
        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>

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
                <p class="font-weight-bold">SUPPLIER LIST</p>
            </div>

            <div class="container" style="font-family: 'Montserrat', sans-serif !important;">
                <div class="row">
                    <div class="col-md-12">
                        <h3>RECORDS</h3>
                        <a href="supplier_add.php" class="btn btn-warning pull-right" style="margin-bottom: 10px; position: relative; background-color: #02964C; border-color: #02964C;"><span class="glyphicon glyphicon-plus"></span> Add Supplier </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3" style="margin-left: 870px; margin-bottom: 10px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search Table...">
                        <label for="itemDropdown">Supplier's Products Filter:</label>
                        <select id="itemDropdown" class="form-control" name="itemDropdown" onchange="filterSuppliers()">
                            <option value="">Select a Product</option>
                            <?php
                            require_once('db_config.php');
                            $query = "SELECT DISTINCT productname FROM product_list ORDER BY productname";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['productname'] . "'>" . $row['productname'] . "</option>";
                            }

                            ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Staff Name</th>
                                        <th>Contact Number</th>
                                        <th>Email Address</th>
                                        <th>Address</th>
                                        <th>Date Creation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="supplier-table-body">
                                    <?php
                                    require_once 'db_config.php';
                                    $sql = mysqli_query($conn, "SELECT * FROM supplier_list");
                                    $count = 1;
                                    $row = mysqli_num_rows($sql);
                                    if ($row > 0) {
                                        while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                            <tr>
                                                <td><?php echo $count ?></td>
                                                <td><?php echo $row['companyname']; ?></td>
                                                <td><?php echo $row['staffname']; ?></td>
                                                <td><?php echo $row['contactnumber']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['address']; ?></td>
                                                <td><?php echo $row['creationdate']; ?></td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <a href="supplier_edit.php?editid=<?php echo htmlentities($row['id']); ?>" class="btn btn-warning btn-sm" style="margin-right: 8px; position: relative;"><span class="glyphicon glyphicon-pencil"></span> Edit </a>
                                                        <a href="supplier.php?delid=<?php echo htmlentities($row['id']); ?>" onClick="return confirm('Do you really want to remove this record?')" class="btn btn-danger btn-sm" style="background-color: #cc3c43; border-color: #cc3c43;"><span class="glyphicon glyphicon-trash"></span> Delete </a>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                            $count = $count + 1;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pagination" style="margin-left: 965px;">
                                    <?php
                                    $page_query = "SELECT COUNT(*) FROM supplier_list";
                                    $page_result = mysqli_query($conn, $page_query);
                                    $total_records = mysqli_fetch_array($page_result)[0];
                                    $total_pages = ceil($total_records / $records_per_page);
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        echo "<a href='supplier.php?page=" . $i . "'>" . $i . "</a> ";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- Scripts -->
    <!-- ApexCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
    <script>
        $(document).ready(function() {
            $("#tableSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <script>
        function filterSuppliers() {
            var selectedProduct = document.getElementById('itemDropdown').value;
            if (!selectedProduct) {
                // Optionally, reload the original supplier list or handle empty selection
                return;
            }

            // AJAX request to fetch suppliers
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "supplier_gets.php?product=" + encodeURIComponent(selectedProduct), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update the supplier table with the response
                    document.querySelector('.supplier-table-body').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>

</body>

</html>