<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login_register.php');
    exit();
}

$user_name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css"> <!-- FontAwesome CSS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

        /* Action Buttons Styles */
        .action-button {
            border: none;
            padding: 8px 12px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .fulfill-button {
            background-color: #28a745;
            /* Green */
        }

        .fulfill-button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .cancel-button {
            background-color: #dc3545;
            /* Red */
        }

        .cancel-button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        /* Optional: Adjust icon size if necessary */
        .action-button i {
            margin-right: 0;
            /* Remove right margin since there's no text */
        }

        /* Tooltip Styles (Optional) */
        .action-button[title] {
            position: relative;
        }

        .action-button[title]::after {
            content: attr(title);
            position: absolute;
            bottom: 125%;
            /* Position above the button */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.75);
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            font-size: 14px;
        }

        .action-button[title]:hover::after {
            opacity: 1;
        }

        .status-pending {
            color: #ffc107;
            /* Amber */
            font-weight: bold;
        }

        .status-in-progress {
            color: #17a2b8;
            /* Teal */
            font-weight: bold;
        }

        .status-completed {
            color: #28a745;
            /* Green */
            font-weight: bold;
        }

        .status-cancelled {
            color: #dc3545;
            /* Red */
            font-weight: bold;
        }

        .sidebar {
            width: 250px;
            background-color: #2e8b57;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            height: max-content;
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

        .totals {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .total-box {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 30%;
            text-align: center;
            background-color: #f9f9f9;
        }

        .total-box h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .total-box p {
            font-size: 1.5em;
            margin: 0;
            color: #2e8b57;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #2e8b57;
            color: white;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status-paid {
            color: green;
            font-weight: bold;
        }

        .status-incomplete {
            color: orange;
            font-weight: bold;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>CleanSwift</h2>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="customers.php"><i class="fas fa-users"></i>Customers</a></li>
                    <li><a href="manage_services.php"><i class="fas fa-cogs"></i>Manage Services</a></li>
                    <li><a href="orders.php"><i class="fas fa-box"></i>Orders</a></li>
                    <li><a href="pickup.php"><i class="fas fa-chart-line"></i>Incoming Deliveries</a></li>
                    <li><a href="earnings.php" class="active"><i class="fas fa-wallet"></i>Earnings</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Moneyyy</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Hello, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>


            <div class="totals">
                <div class="total-box">
                    <h3>Total Money Due</h3>
                    <p id = "total-due">0</p>
                </div>
                <div class="total-box">
                    <h3>Total Paid</h3>
                    <p id = "total-paid">0</p>
                </div>
                <div class="total-box">
                    <h3>To be paid</h3>
                    <p id = "total-incomplete">0</p>
                </div>

            </div>

            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Contact</th>
                        <th>Amount ($)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="payments-table-body">
                    <!-- Payments will be dynamically inserted here -->
                </tbody>
            </table>
            <script>
                $(document).ready(function() {
                    const endpoint = '../actions/get_payments_by_provider.php';

                    $.ajax({
                        url: endpoint,
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                const payments = response.data;
                                let totalDue = 0;
                                let totalIncomplete = 0;
                                let totalPaid = 0;

                                const tableBody = $('#payments-table-body');
                                tableBody.empty();

                                payments.forEach(payment => {
                                    const amount = parseFloat(payment.amt);
                                    totalDue += amount; // Fixed from 'amt' to 'amount'

                                    let statusClass = '';
                                    if (payment.payment_status.toLowerCase() === 'paid') {
                                        totalPaid += amount; // Fixed from 'amt' to 'amount'
                                        statusClass = 'status-paid';
                                    } else if (payment.payment_status.toLowerCase() === 'incomplete') {
                                        totalIncomplete += amount; // Fixed from 'amt' to 'amount'
                                        statusClass = 'status-incomplete';
                                    }

                                    const row = `
                                <tr>
                                    <td>${payment.customer_name}</td>
                                    <td>${payment.customer_contact}</td>
                                    <td>${amount.toFixed(2)}</td>
                                    <td class="${statusClass}">${payment.payment_status}</td>
                                </tr>
                            `;
                                    tableBody.append(row);
                                });

                                $('#total-due').text(`$${totalDue.toFixed(2)}`);
                                $('#total-incomplete').text(`$${totalIncomplete.toFixed(2)}`);
                                $('#total-paid').text(`$${totalPaid.toFixed(2)}`);
                            } else {
                                alert(response.message || 'Failed to fetch payments.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            alert('An error occurred while fetching payments.');
                        }
                    });
                });
            </script>
</body>

</html>