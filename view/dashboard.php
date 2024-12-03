<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login_register.php');
    exit();
}

$user_name = $_SESSION['name'];
if ($_SESSION['role_id'] == 1) {
    header('Location: ../customer/dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Management Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="../js/dashboard.js"></script>
    <style>
        .user-profile-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .user-profile-button:hover {
            transform: scale(1.1);
        }

        .nav ul {
            list-style-type: none;
            padding: 0;
        }

        .nav ul li {
            margin: 10px 0;
        }

        .nav ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 10px 0;
            text-align: center;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>CleanSwift</h2>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="#" id="user-management-link">User Management</a></li>
                    <li><a href="#" id="order-monitoring-link">Order Monitoring</a></li>
                    <li><a href="#" id="reports-analytics-link">Reports & Analytics</a></li>
                    <li><a href="../Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Laundry Dashboard</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                    <button class="logout-button" onclick="window.location.href='../login/logout.php'">Logout</button>
                </div>
            </header>

            <section class="overview">
                <div class="card" id="new-orders-card">
                    <h3>New Orders</h3>
                    <p id="new-orders-count">0 Orders today</p>
                </div>
                <div class="card" id="total-earnings-card">
                    <h3>Total Earnings</h3>
                    <p id="total-earnings-amount">$0.00</p>
                </div>
                <div class="card" id="active-providers-card">
                    <h3>Active Providers</h3>
                    <p id="active-providers-count">0 Laundry Providers</p>
                </div>
                <div class="card" id="active-drivers-card">
                    <h3>Active Drivers</h3>
                    <p id="active-drivers-count">0 Drivers</p>
                </div>
                <div class="card" id="active-customers-card">
                    <h3>Active Customers</h3>
                    <p id="active-customers-count">0 Customers</p>
                </div>
                <div class="card" id="pending-deliveries-card">
                    <h3>Pending Deliveries</h3>
                    <p id="pending-deliveries-count">0 Deliveries</p>
                </div>
                <div class="card" id="total-payments-card">
                    <h3>Total Payments</h3>
                    <p id="total-payments-amount">$0.00</p>
                </div>
                <div class="card" id="admin-cut-card">
                    <h3>Admin's Cut</h3>
                    <p id="admin-cut-amount">$0.00</p>
                </div>
            </section>

            <section class="users-overview">
                <div class="card" id="all-users-card">
                    <h3>All Users</h3>
                    <p id="all-users-count">0 Users</p>
                </div>
            </section>

        </main>
    </div>

    <script>
        document.getElementById('user-management-link').addEventListener('click', function() {
            window.location.href = '../admin/user_management.php';
        });

        document.getElementById('order-monitoring-link').addEventListener('click', function() {
            window.location.href = '../admin/order_monitoring.php';
        });

        document.getElementById('reports-analytics-link').addEventListener('click', function() {
            window.location.href = '../admin/reports_analytics.php';
        });

        // Fetch and update dashboard data dynamically
        function updateDashboardData() {
            fetch('../actions/get_overview_action.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('new-orders-count').textContent = data.today_order_count + ' Orders today';
                    document.getElementById('total-earnings-amount').textContent = '$' + data.total_earnings;
                    document.getElementById('active-providers-count').textContent = data.total_providers + ' Laundry Providers';
                    document.getElementById('active-drivers-count').textContent = data.total_drivers + ' Drivers';
                    document.getElementById('active-customers-count').textContent = data.total_customers + ' Customers';
                    document.getElementById('pending-deliveries-count').textContent = data.pending_deliveries + ' Deliveries';
                    document.getElementById('total-payments-amount').textContent = '$' + data.total_payments;
                    document.getElementById('admin-cut-amount').textContent = '$' + data.admin_cut.toFixed(2);
                    document.getElementById('all-users-count').textContent = data.total_users + ' Users';
                })
                .catch(error => console.error('Error fetching dashboard data:', error));
        }

        // Call the function to update data on page load
        updateDashboardData();
    </script>
</body>

</html>