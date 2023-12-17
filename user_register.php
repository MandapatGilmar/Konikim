<?php
session_start();

$firstnameErr = $lastnameErr = $usernameErr = $passwordErr = "";
$firstname = $lastname = $username = "";

// Check if the user is logged in and if the user is not an administrator
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Administrator') {
    header("Location: unauthorized.php"); // Redirect to an unauthorized access page
    exit();
}
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['firstname']) && isset($_SESSION['user_type'])) {
    $userFirstName = $_SESSION['firstname']; // Set user's first name from session
    $userType = $_SESSION['user_type']; // Set user's type from session
} else {
    $userFirstName = 'Unknown'; // Set to 'Unknown' if not logged in or firstname not set
    $userType = 'Unknown'; // Set to 'Unknown' if not logged in or user_type not set
}

// Connect to the database
require_once 'db_config.php'; // Assuming db_config.php contains the database connection details

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $userType = mysqli_real_escape_string($conn, $_POST['user_level']);

    // Validation
    $valid = true;

    // First Name and Last Name validation: only letters
    if (!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
        $firstnameErr = "Only letters and white space allowed";
        $valid = false;
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $lastname)) {
        $lastnameErr = "Only letters and white space allowed";
        $valid = false;
    }

    // Username validation
    if (!preg_match("/^[a-zA-Z0-9.]{1,}[^\W_]$/", $username)) {
        $usernameErr = "Invalid username format";
        $valid = false;
    } else {
        // Check if username already exists
        $userCheck = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $userCheck->bind_param("s", $username);
        $userCheck->execute();
        $result = $userCheck->get_result();
        if ($result->num_rows > 0) {
            $usernameErr = "Username already exists";
            $valid = false;
        }
        $userCheck->close();
    }

    // Password validation
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $passwordErr = "Password does not meet requirements";
        $valid = false;
    }

    // Insert into database
    if ($valid) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $username, $hashed_password, $userType);

        if ($stmt->execute()) {
            echo "<script>alert('New user created successfully');</script>";
            echo "<script>window.location.href='user.php'</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>User Register</title>

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
        .error {
            color: red;
            font-size: 12px;
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
                    <div class="row">
                        <div class="col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Enter First Name" required>
                            <span class="error"><?php echo $firstnameErr; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Enter Last Name" required>
                            <span class="error"><?php echo $lastnameErr; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter Username" required>
                            <span class="error"><?php echo $usernameErr; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                <span class="error"><?php echo $passwordErr; ?></span>
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
                                <option value="Administrator">Administrator</option>
                                <option value="Managing Parter">Managing Parter</option>
                                <option value="Sales Clerk">Sales Clerk</option>
                                <option value="Inventory Clerk">Inventory Clerk</option>

                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 1%">
                        <div class="col-md-6">
                            <button type="text" name="submit" class="btn btn-primary" style="background-color: #02964C; border-color: #02964C;">Submit</button>
                            <a href="user.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;"> View Users List</a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <!-- ApexCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>
    <!-- Custom JS -->
    <script src="js\scipt.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            // Toggle the type of the password field
            var passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<span class="glyphicon glyphicon-eye-close"></span>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<span class="glyphicon glyphicon-eye-open"></span>';
            }
        });
    </script>

</body>

</html>