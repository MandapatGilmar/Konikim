<?php
session_start();

// Check if the user is logged in and if the user is not an administrator
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Administrator') {
    // Redirect to a different page or show an error
    header("Location: unauthorized.php"); // Redirect to an unauthorized access page
    exit();
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['firstname'])) {
    $userFirstName = $_SESSION['firstname'];
} else {
    $userFirstName = 'Unknown'; // Or handle the case where the user is not logged in
}

require_once 'db_config.php'; // Database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productId = $_POST['productDropdown'];
    $removeStocks = $_POST['remove_stocks'];
    $reason = $_POST['reason'];
    $adjustedBy = $_SESSION['firstname'] ?? 'Unknown'; // Get the user's first name from session
    $stockAfter = $_POST['stock_after'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update inventory table
        $updateInventorySql = "UPDATE inventory SET available_stocks = ? WHERE product_id = ?";
        $stmt = $conn->prepare($updateInventorySql);
        $stmt->bind_param("ii", $stockAfter, $productId);
        $stmt->execute();
        $stmt->close();

        // Insert into adjustment_logs table
        $insertLogSql = "INSERT INTO adjustment_log (product_id, adjustment_quantity, reason, adjusted_by, adjustment_date) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertLogSql);
        $stmt->bind_param("iiss", $productId, $removeStocks, $reason, $adjustedBy);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Redirect or display success message
        // header("Location: success_page.php");
        header("Location: inventory.php");
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        // Display error message
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>STOCK ADJUSTMENT | Konikim</title>

    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                <p class="font-weight-bold">INVENTORY</p>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3>STOCK ADJUSTMENT</h3>
                    </div>
                </div>

                <form method="POST">
                    <div class="row">
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
                            <label for="supplier">Supplier</label>
                            <input type="text" class="form-control" id="supplier" name="supplier" required readonly>

                        </div>
                        <div class="col-md-3">
                            <label for="productunit">Units</label>
                            <input type="text" class="form-control" id="productunit" name="productunit" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="in_stocks">In Stocks</label>
                            <input type="number" class="form-control" id="in_stocks" name="in_stocks" required readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="remove_stocks">Remove Stocks</label>
                            <input type="number" class="form-control" id="remove_stocks" name="remove_stocks" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label for="stock_after">Stock After</label>
                            <input type="number" class="form-control" id="stock_after" name="stock_after" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="adjusted_by">Adjusted By</label>
                            <input type="text" class="form-control" name="adjusted_by" value="<?php echo htmlspecialchars($userFirstName); ?>" required readonly>
                        </div>
                    </div>
                    <!-- Reason Field (Already in your form) -->
                    <div class="row">
                        <div class="col-md-6">
                            <label for="reason">Reason</label>
                            <textarea class="form-control" name="reason" id="reason" style="width: 600px; height: 300px;" required></textarea>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 1%">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;">Submit</button>
                            <a href="user.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;">View Users List</a>
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
        document.getElementById("productDropdown").addEventListener("change", function() {
            var productId = this.value;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "inventory_fetch_products.php?productId=" + productId, true);
            xhr.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        try {
                            var response = JSON.parse(this.responseText);
                            document.getElementById("supplier").value = response.supplier;
                            document.getElementById("productunit").value = response.productunit;
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
        document.getElementById("remove_stocks").addEventListener("input", function() {
            var inStocks = parseInt(document.getElementById("in_stocks").value) || 0;
            var removeStocks = parseInt(this.value) || 0;

            // Check if removeStocks is greater than inStocks
            if (removeStocks > inStocks) {
                alert("Cannot remove more stocks than available.");
                this.value = inStocks; // Reset to max possible value
                removeStocks = inStocks;
            }

            var stockAfter = inStocks - removeStocks;
            document.getElementById("stock_after").value = stockAfter;
        });
    </script>
</body>

</html>