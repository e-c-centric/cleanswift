<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login/login_register.php');
    exit();
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Service Provider Dashboard</title>
    <link rel="stylesheet" href="../css/laundry_provider.css">
    <script src="../js/dashboard.js"></script>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>CleanSwift</h2>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="../view/provider_dashboard.php">Dashboard</a></li>
                    <li><a href="../view/manage_services.php">Manage Services</a></li>
                    <li><a href="../view/manage_orders.php">Manage Orders</a></li>
                    <li><a href="../view/earnings.php">Earnings Overview</a></li>
                    <li><a href="../view/past_deliveries.php">Past Deliveries</a></li>
                    <li><a href="../Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Laundry Service Provider Dashboard</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <section class="overview">
                <div class="card">
                    <h3>New Orders</h3>
                    <p>10 Orders Today</p>
                </div>
                <div class="card">
                    <h3>Total Earnings</h3>
                    <p>$1,200.00</p>
                </div>
                <div class="card">
                    <h3>Peak Service Time</h3>
                    <p>3 PM - 5 PM</p>
                </div>
            </section>

            <section class="order-management">
                <h2>Order Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample data, you would dynamically generate these rows -->
                        <tr>
                            <td>#1001</td>
                            <td>John Doe</td>
                            <td>Laundry Service</td>
                            <td>Pending</td>
                            <td>
                                <button class="btn-approve">Approve</button>
                                <button class="btn-decline">Decline</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#1002</td>
                            <td>Jane Smith</td>
                            <td>Laundry Service</td>
                            <td>Pending</td>
                            <td>
                                <button class="btn-approve">Approve</button>
                                <button class="btn-decline">Decline</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="past-deliveries">
                <h2>Past Deliveries</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Earnings</th>
                            <th>User Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-10-20</td>
                            <td>2:00 PM</td>
                            <td>$30.00</td>
                            <td>Great service!</td>
                        </tr>
                        <tr>
                            <td>2024-10-19</td>
                            <td>4:00 PM</td>
                            <td>$25.00</td>
                            <td>Very satisfied.</td>
                        </tr>
                    </tbody>
                </table>
            </section>

        </main>
    </div>
</body>
</html>
