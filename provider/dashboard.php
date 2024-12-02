<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login_register.php');
    exit();
}

$provider_name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css"> <!-- FontAwesome CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2e8b57;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
        }

        .logo {
            text-align: center;
            background-color: #006400;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo h2 {
            margin: 0;
            color: #fff;
        }

        .nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav ul li {
            margin: 10px 0;
        }

        .nav ul li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .nav ul li a i {
            margin-right: 10px;
        }

        .nav ul li a:hover,
        .nav ul li a.active {
            background-color: #3cb371;
            box-shadow: inset 5px 0 0 #006400;
        }

        .nav ul li a.active {
            background-color: #f4f4f9;
            color: #333;
            box-shadow: inset 5px 0 0 #006400;
        }

        .logout {
            display: block;
            padding: 15px 20px;
            background-color: #dc3545;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout:hover {
            background-color: #c82333;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f4f4f9;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .main-header h1 {
            margin: 0;
        }

        .user-profile-button {
            background-color: #006400;
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

        .overview {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            text-align: center;
            flex: 1;
            color: #333;
        }

        .card h3 {
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1.2em;
            margin: 0;
        }

        .charts {
            margin-top: 20px;
        }

        .chart {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
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
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="customers.php"><i class="fas fa-users"></i>Customers</a></li>
                    <li><a href="manage_services.php"><i class="fas fa-cogs"></i>Manage Services</a></li>
                    <li><a href="orders.php"><i class="fas fa-box"></i>Orders</a></li>
                    <li><a href="pickup.php"><i class="fas fa-chart-line"></i>Incoming Deliveries</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Dashboard</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Holla, <?php echo htmlspecialchars($provider_name); ?></button>
                </div>
            </header>

            <section class="overview">
                <div class="card">
                    <h3>Total Customers</h3>
                    <p id="totalCustomers">50 Customers</p>
                </div>
                <div class="card">
                    <h3>Pending Orders</h3>
                    <p id="pendingOrders">15 Orders</p>
                </div>
                <div class="card">
                    <h3>Completed Orders</h3>
                    <p id="completedOrders">120 Orders</p>
                </div>
            </section>

            <section class="charts">
                <div class="chart orders-chart">
                    <h3>Orders Overview</h3>
                    <canvas id="ordersChart"></canvas>
                </div>
            </section>

        </main>
    </div>
</body>

</html>