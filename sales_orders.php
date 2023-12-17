<!DOCTYPE html>
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
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Sales Order</title>

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
                <p class="font-weight-bold">SALES ORDERS</p>
            </div>

            <div class="container" style="font-family: 'Montserrat', sans-serif !important;">
                <div class="row">
                    <div class="col-md-12">
                        <h3>RECORDS</h3>
                        <a href="sales_order_add.php" class="btn btn-warning pull-right" style="margin-bottom: 10px; position: relative; background-color: #02964C; border-color: #02964C;"><span class="glyphicon glyphicon-plus"></span> Create New </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2" style="margin-left: 965px; margin-bottom: 10px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search Table...">
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sale Code</th>
                                        <th>Client</th>
                                        <th>Items</th>
                                        <th>Amount</th>
                                        <th>Date Creation</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once 'db_config.php';

                                    $sql = mysqli_query($conn, "SELECT so.*, COUNT(soi.id) AS itemCount FROM sales_orders so LEFT JOIN sales_order_items soi ON so.id = soi.sales_order_id GROUP BY so.id");
                                    $count = 1;
                                    $row = mysqli_num_rows($sql);
                                    if ($row > 0) {
                                        while ($row = mysqli_fetch_array($sql)) {

                                    ?> <tr>
                                                <td><?php echo $count ?></td>
                                                <td><?php echo $row['salescode']; ?></td>
                                                <td><?php echo $row['customername']; ?></td>
                                                <td><?php echo $row['itemCount']; ?></td>
                                                <td><?php echo $row['soGrandTotal']; ?></td>
                                                <td><?php echo $row['dateCreated']; ?></td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <a href="#" class="btn btn-info btn-sm view-sales-order" data-salesid="<?php echo htmlentities($row['id']); ?>" style="margin-right: 8px;"><span class="glyphicon glyphicon-search"></span> View </a>
                                                        <a href="#" class="btn btn-warning btn-sm" style="margin-right: 8px; position: relative;"><span class="glyphicon glyphicon-pencil"></span> Edit </a>
                                                        <a href="#" onClick="return confirm('Do you really want to remove this record?')" class="btn btn-danger btn-sm" style="background-color: #cc3c43; border-color: #cc3c43;"><span class="glyphicon glyphicon-trash"></span> Delete </a>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                            $count = $count + 1;
                                        }
                                    }
                                    ?>
                                    <div class="modal fade" id="productDetailsModal" tabindex="-1" role="dialog" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="productDetailsModalLabel"><b>Sales Order Details</b></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="orderDetailsContent">
                                                        <!-- Dynamic content will be loaded here -->
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="printOrderDetails()">Print</button>

                                                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
        $(document).ready(function() {
            $('.view-sales-order').on('click', function(e) {
                e.preventDefault();
                var salesId = $(this).data('salesid'); // Corrected variable name
                $.ajax({
                    url: 'sales_order_view.php', // Ensure this is the correct path
                    method: 'POST',
                    data: {
                        id: salesId // Ensure this matches the data attribute
                    },
                    success: function(response) {
                        $('#productDetailsModal .modal-body').html(response);
                        $('#productDetailsModal').modal('show');
                    }
                });
            });
        });
    </script>
    <script>
        function printOrderDetails() {
            var modalContent = document.getElementById('productDetailsModal').innerHTML;
            var printWindow = window.open('', '_blank', 'height=600,width=800');

            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('<style>');
            // Hide buttons
            printWindow.document.write('button { display: none; }');
            // Additional styles
            printWindow.document.write('th { background-color: #02964C; color: white; }');
            printWindow.document.write('body { font-family: Arial, sans-serif; }');
            printWindow.document.write('.modal-header { color: green; }');
            printWindow.document.write('.modal-body { padding: 20px; }');
            // Add more CSS rules as needed
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(modalContent);
            printWindow.document.write('</body></html>');

            printWindow.document.close();
            printWindow.focus();

            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.print();
                    printWindow.close();
                }, 250);
            };
        }
    </script>

</body>

</html>