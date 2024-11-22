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
    <title>Driver Dashboard</title>
    <link rel="stylesheet" href="../css/driver.css">
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
                    <li><a href="../view/orders.php">Orders</a></li>
                    <li><a href="../view/earnings.php">Earnings</a></li>
                    <li><a href="../view/past_deliveries.php">Past Deliveries</a></li>
                    <li><a href="../Login/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Driver Dashboard</h1>
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
                    <h3>Pending Deliveries</h3>
                    <p>7 Deliveries</p>

                </div>
            </section>

            <section class="order-management">
                <h2>Order Management</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#12345</td>
                            <td>John Doe</td>
                            <td>Pending</td>
                            <td>
                            <button class="btn-approve">Approve</button>
                            <button class="btn-decline">Decline</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#12346</td>
                            <td>Jane Smith</td>
                            <td>Approved</td>
                            <td>
                            <button class="btn-approve">Approve</button>
                            <button class="btn-decline">Decline</button>
                            </td>
                        </tr>
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </section>

            <section class="past-deliveries">
                <h2>Past Deliveries</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Delivery Time</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#12344</td>
                            <td>Mark Taylor</td>
                            <td>Oct 21, 2024 - 2:30 PM</td>
                            <td>Great service!</td>
                        </tr>
                        <tr>
                            <td>#12343</td>
                            <td>Lisa Ray</td>
                            <td>Oct 20, 2024 - 1:15 PM</td>
                            <td>Very satisfied!</td>
                        </tr>
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </section>

            <!-- Modal for declining orders -->
            <div class="modal" id="declineModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Decline Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Enter a reason for declining the order:</p>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger">Decline Order</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
