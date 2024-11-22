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
    <title>My Customers</title>
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

        .filter-search {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-search input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            max-width: 300px;
        }

        .filter-search select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            max-width: 200px;
        }

        .customer-groups {
            display: flex;
            justify-content: space-between;
        }

        .customer-group {
            flex: 1;
            margin-right: 20px;
        }

        .customer-group:last-child {
            margin-right: 0;
        }

        .customer-group h3 {
            margin-bottom: 10px;
            color: #006400;
        }

        .customer-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .customer-list li {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .customer-list li:hover {
            background-color: #f1f1f1;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .save-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .save-button:hover {
            background-color: #218838;
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
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="customers.php" class="active"><i class="fas fa-users"></i>Customers</a></li>
                    <li><a href="manage_services.php"><i class="fas fa-cogs"></i>Manage Services</a></li>
                    <li><a href="orders.php"><i class="fas fa-box"></i>Orders</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Customers</h1>
                <div class="user-profile">
                    <button class="user-profile-button">What's up, <?php echo htmlspecialchars($provider_name); ?>?</button>
                </div>
            </header>

            <div class="filter-search">
                <input type="text" id="search" placeholder="Search customers...">
                <select id="filter">
                    <option value="all">All</option>
                    <option value="received">Orders Received</option>
                    <option value="fulfilled">Orders Fulfilled</option>
                    <option value="regular">My Regulars</option>
                </select>
            </div>

            <div class="customer-groups">
                <div class="customer-group">
                    <h3>Orders Received</h3>
                    <ul class="customer-list" id="receivedCustomers">
                        <li data-customer-id="1">Customer 1</li>
                        <li data-customer-id="2">Customer 2</li>
                    </ul>
                </div>

                <div class="customer-group">
                    <h3>Orders Fulfilled</h3>
                    <ul class="customer-list" id="fulfilledCustomers">
                        <li data-customer-id="3">Customer 3</li>
                        <li data-customer-id="4">Customer 4</li>
                    </ul>
                </div>

                <div class="customer-group">
                    <h3>My Regulars</h3>
                    <ul class="customer-list" id="regularCustomers">
                        <li data-customer-id="5">Customer 5</li>
                        <li data-customer-id="6">Customer 6</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <!-- Customer Modal -->
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Customer Details</h2>
            <div class="form-group">
                <label for="customerName">Name</label>
                <input type="text" id="customerName" readonly>
            </div>
            <div class="form-group">
                <label for="customerContact">Contact</label>
                <input type="text" id="customerContact" readonly>
            </div>
            <div class="form-group">
                <label for="customerOrder">Last/Pending Order</label>
                <input type="text" id="customerOrder" readonly>
            </div>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById('customerModal');

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName('close')[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = 'none';
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Get customer list items
        var customerItems = document.querySelectorAll('.customer-list li');

        // Add click event to each customer item
        customerItems.forEach(function(item) {
            item.onclick = function() {
                var customerId = this.getAttribute('data-customer-id');
                // Fetch customer details using customerId (this is just a placeholder)
                var customerDetails = {
                    name: 'Customer ' + customerId,
                    contact: '+1234567890',
                    order: 'Order details for customer ' + customerId
                };

                // Populate modal with customer details
                document.getElementById('customerName').value = customerDetails.name;
                document.getElementById('customerContact').value = customerDetails.contact;
                document.getElementById('customerOrder').value = customerDetails.order;

                // Display the modal
                modal.style.display = 'block';
            }
        });

        // Filter and search functionality
        document.getElementById('search').addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();
            customerItems.forEach(function(item) {
                if (item.textContent.toLowerCase().includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        document.getElementById('filter').addEventListener('change', function() {
            var filterValue = this.value;
            document.querySelectorAll('.customer-group').forEach(function(group) {
                if (filterValue === 'all' || group.querySelector('h3').textContent.toLowerCase().includes(filterValue)) {
                    group.style.display = '';
                } else {
                    group.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>