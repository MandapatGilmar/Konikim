<?php
session_start();

// Check if the user is logged in and if the user is not an administrator
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Administrator') {
    // Redirect to a different page or show an error
    header("Location: unauthorized.php"); // Redirect to an unauthorized access page
    exit();
}
$smsSent = false; // Set to true if SMS has been sent successfully
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $supplierId = $_POST['supplierDropdown'];
    $contactNumber = $_POST['contactNumber'];
    $staffName = $_POST['staffName'];
    $message = $_POST['message'];

    // Semaphore API credentials
    $apikey = '24cfd129f531fe4fc7279770c3212a0d'; // Replace with your Semaphore API key

    // Prepare data for API request
    $data = array(
        "apikey" => $apikey,
        "number" => $contactNumber,
        "message" => $message
        // Add "sendername" if you wish to specify a sender name
    );

    // Initialize cURL session
    $ch = curl_init('https://api.semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and capture response
    $response = curl_exec($ch);
    curl_close($ch);

    // Process response (Optional)
    // $response_data = json_decode($response, true);
    // ... Handle response as needed ...


    $smsSent = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>SMS | Konikim</title>

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
                <p class="font-weight-bold">SMS</p>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3>SMS Information</h3>
                    </div>
                </div>

                <form method="POST">
                    <?php if ($smsSent) : ?>
                        <div class="alert alert-success" role="alert">
                            SMS sent successfully!
                        </div>
                        <script>
                            // Automatically hide the alert after 5 seconds
                            $(document).ready(function() {
                                setTimeout(function() {
                                    $('.alert').fadeOut('slow');
                                }, 5000);
                            });
                        </script>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="supplierDropdown">Select Supplier:</label>
                            <select id="supplierDropdown" class="form-control" name="supplierDropdown">
                                <option value="">Select Supplier</option>
                                <?php
                                require_once('db_config.php');

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $sql = "SELECT id, companyname, staffname FROM supplier_list";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row["id"] . "'>" . $row["companyname"] . "</option>";
                                    }
                                } else {
                                    echo "<option>No suppliers found</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="userType">Contact Number:</label>
                            <input type="userType" class="form-control" id="contactNumber" name="contactNumber" placeholder="Contact Number" readonly>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="userType">Staff Name:</label>
                            <input type="userType" class="form-control" id="staffName" name="staffName" placeholder="Staff Name" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="message">Message</label>
                            <textarea type="text" class="form-control" name="message" style="width: 600px; height: 300px;" required> </textarea>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 1%">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;">Submit</button>
                            <a href="user.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;"> View Users List</a>
                        </div>
                    </div>
                </form>

                <script>
                    document.getElementById("supplierDropdown").addEventListener("change", function() {
                        var supplierId = this.value;

                        var xhr = new XMLHttpRequest();
                        xhr.open("GET", "sms_fetch_supplier.php?supplierId=" + supplierId, true);
                        xhr.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                var response = JSON.parse(this.responseText);
                                document.getElementById("contactNumber").value = response.contactnumber;
                                document.getElementById("staffName").value = response.staffname;

                                // Update other fields similarly
                            }
                        };
                        xhr.send();
                    });
                </script>
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