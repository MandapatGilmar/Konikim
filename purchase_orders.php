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
require_once 'db_config.php';

// Check if the delete ID is set in the URL
if (isset($_GET['delid'])) {
    $delid = intval($_GET['delid']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // First, delete the related items in purchase_order_items
        $stmt = $conn->prepare("DELETE FROM purchase_order_items WHERE purchase_order_id = ?");
        $stmt->bind_param("i", $delid);
        $stmt->execute();
        $stmt->close();

        // Then, delete the purchase order itself
        $stmt = $conn->prepare("DELETE FROM purchase_orders WHERE id = ?");
        $stmt->bind_param("i", $delid);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Redirect to the same page to see the updated list
        header("Location: purchase_orders.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        // Handle error - you might want to show a user-friendly message
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Purchase Order</title>

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
                <p class="font-weight-bold">PURCHASE ORDERS</p>
            </div>
            <div class="container" style="font-family: 'Montserrat', sans-serif !important;">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Records</h3>
                        <a href="purchase_order_add.php" class="btn btn-warning pull-right" style="margin-bottom: 10px; position: relative; background-color: #02964C; border-color: #02964C;"><span class="glyphicon glyphicon-plus"></span> Create New </a>
                    </div>
                </div>

                <div class="row" style="padding-bottom: 10px; padding-top: 10px;">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PO Code</th>
                                        <th>Supplier</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once 'db_config.php';
                                    $sql = mysqli_query($conn, "SELECT po.*, COUNT(poi.id) AS itemCount FROM purchase_orders po LEFT JOIN purchase_order_items poi ON po.id = poi.purchase_order_id GROUP BY po.id");
                                    $count = 1;
                                    $row = mysqli_num_rows($sql);
                                    if ($row > 0) {
                                        while ($row = mysqli_fetch_array($sql)) {
                                            // Determine the status string and corresponding class
                                            if ($row['status'] == 0) {
                                                $statusString = 'Pending';
                                                $statusClass = 'label label-danger';
                                                $isReceived = false;
                                            } else {
                                                $statusString = 'Received';
                                                $statusClass = 'label label-success';
                                                $isReceived = true;
                                            }
                                    ?>
                                            <tr>
                                                <td><?php echo $count ?></td>
                                                <td><?php echo $row['purchasecode']; ?></td>
                                                <td><?php echo $row['supplier']; ?></td>
                                                <td><?php echo $row['itemCount']; ?></td>
                                                <td><?php echo $row['poGrandtotal']; ?></td>
                                                <td><span class="<?php echo $statusClass; ?>"><?php echo $statusString; ?></span></td>
                                                <td><?php echo $row['dateCreated']; ?></td>
                                                <td>
                                                    <div style="display: flex; align-items: center;">
                                                        <a href="#" class="btn btn-info btn-sm view-purchase-order" data-purchaseid="<?php echo htmlentities($row['id']); ?>" style="margin-right: 8px;"><span class="glyphicon glyphicon-search"></span> View </a>
                                                        <?php if (!$isReceived) : ?>
                                                            <a href="received_items.php?receivedid=<?php echo htmlentities($row['id']); ?>" class="btn btn-warning btn-sm" style="margin-right: 8px;"><span class="glyphicon glyphicon-plus"></span> Received </a>
                                                            <a href="purchase_order_edit.php?editid=<?php echo htmlentities($row['id']); ?>" class="btn btn-warning btn-sm" style="margin-right: 8px;"><span class="glyphicon glyphicon-pencil"></span> Edit </a>
                                                            <a href="purchase_orders.php?delid=<?php echo htmlentities($row['id']); ?>" onClick="return confirm('Do you really want to remove this record?')" class="btn btn-danger btn-sm" style="background-color: #cc3c43; border-color: #cc3c43;"><span class="glyphicon glyphicon-trash"></span> Delete </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                            $count = $count + 1;
                                        }
                                    }
                                    ?>
                                    <div class="modal fade" id="productDetailsModal" tabindex="-1" role="dialog" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg"> <!-- Change this line for a larger modal -->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="productDetailsModalLabel"><b>Purchase Order Details</b></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Content will be loaded here via jQuery -->
                                                    <!-- Example content -->
                                                    <div id="orderDetailsContent">
                                                        <!-- Dynamic content goes here -->
                                                    </div>
                                                    <!-- End Example content -->
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
    </div>

    </main>
    <!-- End Main -->

    </div>

    <!-- Scripts -->
    <!-- ApexCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle opening the modal and loading the purchase order details
            $('.view-purchase-order').on('click', function(e) {
                e.preventDefault();
                var purchaseId = $(this).data('purchaseid');
                $.ajax({
                    url: 'purchase_order_view.php', // Ensure the path is correct
                    method: 'POST',
                    data: {
                        id: purchaseId
                    },
                    success: function(response) {
                        $('#productDetailsModal .modal-body').html(response);
                        // Dynamically set the purchaseId for the "Received" button inside the modal
                        $('#productDetailsModal').find('#receivedButton').data('purchaseid', purchaseId);
                        $('#productDetailsModal').modal('show');
                    }
                });
            });
        });
    </script>
    <script>
        function printOrderDetails() {
            var content = document.getElementById("orderDetailsContent").innerHTML;
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title>');
            // Optionally include a link to a CSS file for styling
            printWindow.document.write('<link rel="stylesheet" href="print.css" type="text/css" />');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>



    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
</body>

</html>