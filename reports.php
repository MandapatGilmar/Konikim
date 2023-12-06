<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Reports</title>

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
            </ul>

        </aside>
        <!-- End Sidebar -->

        <!-- Main -->
        <main class="main-container">
            <div class="main-title">
                <p class="font-weight-bold">REPORTS</p>
            </div>

            <div class="main-cards">

                <div class="card">
                    <div class="card-inner">
                        <p class="text-primary">PRODUCTS LIST</p>
                        <span class="material-icons-outlined text-red">assignment_return</span>
                    </div>
                    <a href="#" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;">PDF</a>
                    <a href="#" class="btn btn-success" style="margin-top: 10px; position: relative; background-color: #02964C; border-color: #02964C;">EXCEL</a>
                </div>
                <div class="card">
                    <div class="card-inner">
                        <p class="text-primary">INVENTORY</p>
                        <span class="material-icons-outlined text-orange">fact_check</span>
                    </div>
                    <a href="#" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;">PDF</a>
                    <a href="#" class="btn btn-success" style="margin-top: 10px; position: relative; background-color: #02964C; border-color: #02964C;">EXCEL</a>
                </div>
                <div class="card">
                    <div class="card-inner">
                        <p class="text-primary">PURCHASE ORDERS</p>
                        <span class="material-icons-outlined text-red">assignment_return</span>
                    </div>
                    <a href="#" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;">PDF</a>
                    <a href="#" class="btn btn-success" style="margin-top: 10px; position: relative; background-color: #02964C; border-color: #02964C;">EXCEL</a>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <p class="text-primary">SALES ORDERS</p>
                        <span class="material-icons-outlined text-green">shopping_cart</span>
                    </div>
                    <a href="#" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;">PDF</a>
                    <a href="#" class="btn btn-success" style="margin-top: 10px; position: relative; background-color: #02964C; border-color: #02964C;">EXCEL</a>
                </div>

                <div class="card">
                    <div class="card-inner">
                        <p class="text-primary">RETURN LIST</p>
                        <span class="material-icons-outlined text-red">assignment_return</span>
                    </div>
                    <a href="#" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;">PDF</a>
                    <a href="#" class="btn btn-success" style="margin-top: 10px; position: relative; background-color: #02964C; border-color: #02964C;">EXCEL</a>
                </div>
                <div class="card">
                    <div class="card-inner">
                        <p class="text-primary">SUPPLIERS</p>
                        <span class="material-icons-outlined text-blue">groups</span>
                    </div>
                    <a href="generate_pdf.php" class="btn btn-success" style="background-color: #cc3c43; border-color: #cc3c43;">PDF</a>
                    <a href="export_excel.php" class="btn btn-success" style="margin-top: 10px; position: relative; background-color: #02964C; border-color: #02964C;">EXCEL</a>
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
</body>

</html>