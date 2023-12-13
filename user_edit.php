<?php
session_start();

// Check if the user is logged in and if the user is not an administrator
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Administrator') {
    // Redirect to a different page or show an error
    header("Location: unauthorized.php"); // Redirect to an unauthorized access page
    exit();
}
require_once 'db_config.php';
if (isset($_POST['update'])) {

    $eid = $_GET['editid'];

    // Sanitize input data to prevent SQL injection
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Hash the password if it's not empty, otherwise, leave it unchanged
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($conn, password_hash($_POST['password'], PASSWORD_DEFAULT));
        $passwordQuery = "password='$password',";
    } else {
        $passwordQuery = "";
    }

    $user_level = mysqli_real_escape_string($conn, $_POST['user_level']);

    // Update query with firstname and lastname
    $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', username='$username', $passwordQuery user_type='$user_level' WHERE id='$eid'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('User Updated Successfully!')</script>;";
        echo "<script>window.location.href='user.php'</script>";
    } else {
        echo "<script>alert('User Update Failed!')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>User Edit</title>

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
    <link rel="stylesheet" href="css\user_registration.css">
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
                <p class="font-weight-bold">USER CREATION</p>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3>User Information</h3>
                    </div>
                </div>
                <form method="POST">
                    <?php
                    $eid = $_GET['editid'];
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE id='$eid'");
                    while ($row = mysqli_fetch_array($sql)) {
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="firstname">First Name</label>
                                <input type="text" class="form-control" value="<?php echo $row['firstname']; ?>" name="firstname" placeholder="Enter First Name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="lastname">Last Name</label>
                                <input type="text" class="form-control" value="<?php echo $row['lastname']; ?>" name="lastname" placeholder="Enter Last Name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" value="<?php echo $row['username']; ?>" name="username" placeholder="Enter Username" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                                    <span class="input-group-btn">
                                        <button id="togglePassword" class="btn btn-default" type="button">
                                            <span class="glyphicon glyphicon-eye-open"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="userType">User Type</label>
                                <select id="userType" name="user_level" class="form-control" required>
                                    <option value="Administrator" <?php echo ($row['user_type'] == 'Administrator') ? 'selected' : ''; ?>>Administrator</option>
                                    <option value="Staff" <?php echo ($row['user_type'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                                </select>
                            </div>
                        </div>
                    <?php }
                    ?>
                    <div class="row" style="margin-top: 1%">
                        <div class="col-md-6">
                            <button type="submit" name="update" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;">Submit</button>
                            <a href="user.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;"> View Users List</a>
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
</body>

</html>