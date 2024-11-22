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
                    <li><a href="../view/dashboard.php">Dashboard</a></li>
                    <li><a href="../view/service.php">Add service</a></li>
                    <li><a href="../view/brand.php">Add brand</a></li>
                    <li><a href="../view/category.php">Add category</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Earnings</a></li>
                    <li><a href="#">Laundry Providers</a></li>
                     <li><a href="#">Laundry Drivers</a></li>
                    <li><a href="#">Customer Details</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="../Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Laundry Dashboard</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <section class="overview">
                <div class="card">
                    <h3>New Orders</h3>
                    <p>15 Orders today</p>
                </div>
                <div class="card">
                    <h3>Total Earnings</h3>
                    <p>$540.00</p>
                </div>
                <div class="card">
                    <h3>Active Providers</h3>
                    <p>12 Laundry Providers</p>
                </div>
                <div class="card">
                    <h3>Pending Deliveries</h3>
                    <p>7 Deliveries</p>
                </div>
            </section>

            <section class="charts">
                <div class="chart earnings-chart">
                    <h3>Earnings Overview</h3>
                    <canvas id="earningsChart"></canvas>
                </div>
                <div class="chart orders-chart">
                    <h3>Orders Overview</h3>
                    <canvas id="ordersChart"></canvas>
                </div>
            </section>

        </main>
    </div>
</body>
</html>