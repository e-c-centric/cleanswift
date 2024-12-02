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
                    <li><a href="pickup.php"><i class="fas fa-chart-line"></i>Incoming Deliveries</a></li>
                    <li><a href="earnings.php"><i class="fas fa-wallet"></i>Earnings</a></li>
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
                <div class="customer-group" id="ordersReceivedGroup">
                    <h3>Orders Received</h3>
                    <ul class="customer-list" id="receivedCustomers">
                        <!-- Dynamically populated customers -->
                    </ul>
                </div>

                <div class="customer-group" id="ordersFulfilledGroup">
                    <h3>Orders Fulfilled</h3>
                    <ul class="customer-list" id="fulfilledCustomers">
                        <!-- Dynamically populated customers -->
                    </ul>
                </div>

                <div class="customer-group" id="regularCustomersGroup">
                    <h3>My Regulars</h3>
                    <ul class="customer-list" id="regularCustomers">
                        <!-- Dynamically populated customers -->
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>

    <script>
        $(document).ready(function() {
            fetchOrders();

            function fetchOrders() {
                $.ajax({
                    url: '../actions/getSellerOrders.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success' && data.data.length > 0) {
                            processOrders(data.data);
                        } else {
                            populateEmptyLists();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching orders:', error);
                        populateErrorLists();
                    }
                });
            }

            function populateEmptyLists() {
                $('#receivedCustomers').html('<li>No Orders Received</li>');
                $('#fulfilledCustomers').html('<li>No Orders Fulfilled</li>');
                $('#regularCustomers').html('<li>No Regular Customers</li>');
            }

            // Populate lists with error messages
            function populateErrorLists() {
                $('#receivedCustomers').html('<li>Error loading Orders Received</li>');
                $('#fulfilledCustomers').html('<li>Error loading Orders Fulfilled</li>');
                $('#regularCustomers').html('<li>Error loading Regular Customers</li>');
            }

            function processOrders(orders) {
                var receivedCustomersMap = {};
                var fulfilledCustomersMap = {};
                var customerOrderCounts = {};

                $.each(orders, function(index, order) {
                    var userEmail = order.user_email;
                    var userName = order.user_name;
                    var userContact = order.user_contact;

                    if (customerOrderCounts[userEmail]) {
                        customerOrderCounts[userEmail]++;
                    } else {
                        customerOrderCounts[userEmail] = 1;
                    }

                    if (order.status.toLowerCase() === 'fulfilled') {
                        fulfilledCustomersMap[userEmail] = {
                            name: userName,
                            contact: userContact,
                            order_id: order.order_id
                        };
                    } else {
                        receivedCustomersMap[userEmail] = {
                            name: userName,
                            contact: userContact,
                            order_id: order.order_id,
                            order_status: order.status
                        };
                    }
                });

                // Populate Orders Received
                var receivedCustomers = $.map(receivedCustomersMap, function(customer, key) {
                    return customer;
                });
                $('#receivedCustomers').empty();
                if (receivedCustomers.length > 0) {
                    $.each(receivedCustomers, function(index, customer) {
                        var li = $('<li>')
                            .text(customer.name)
                            .attr('data-customer-email', customer.email || customer.name)
                            .css('cursor', 'pointer')
                            .on('click', function() {
                                showCustomerModal(customer);
                            });
                        $('#receivedCustomers').append(li);
                    });
                } else {
                    $('#receivedCustomers').html('<li>No Orders Received</li>');
                }

                // Populate Orders Fulfilled
                var fulfilledCustomers = $.map(fulfilledCustomersMap, function(customer, key) {
                    return customer;
                });
                $('#fulfilledCustomers').empty();
                if (fulfilledCustomers.length > 0) {
                    $.each(fulfilledCustomers, function(index, customer) {
                        var li = $('<li>')
                            .text(customer.name)
                            .attr('data-customer-email', customer.email || customer.name)
                            .css('cursor', 'pointer')
                            .on('click', function() {
                                showCustomerModal(customer);
                            });
                        $('#fulfilledCustomers').append(li);
                    });
                } else {
                    $('#fulfilledCustomers').html('<li>No Orders Fulfilled</li>');
                }

                var regularCustomers = Object.keys(customerOrderCounts)
                    .map(function(email) {
                        return {
                            email: email,
                            count: customerOrderCounts[email]
                        };
                    })
                    .sort(function(a, b) {
                        return b.count - a.count;
                    })
                    .slice(0, 5);

                // Populate My Regulars
                $('#regularCustomers').empty();
                if (regularCustomers.length > 0) {
                    $.each(regularCustomers, function(index, customer) {
                        // Find customer details from orders
                        var customerData = orders.find(function(order) {
                            return order.user_email === customer.email;
                        });

                        if (customerData) {
                            var li = $('<li>')
                                .text(customerData.user_name + ' (' + customer.count + ' orders)')
                                .attr('data-customer-email', customer.email)
                                .css('cursor', 'pointer')
                                .on('click', function() {
                                    showCustomerModal(customerData);
                                });
                            $('#regularCustomers').append(li);
                        }
                    });
                } else {
                    $('#regularCustomers').html('<li>No Regular Customers</li>');
                }
            }

            // Function to show customer details in modal
            function showCustomerModal(customer) {
                $('#customerName').val(customer.user_name || customer.name);
                $('#customerContact').val(customer.user_contact || customer.contact);

                $.ajax({
                    url: '../actions/getOrderDetails.php',
                    type: 'GET',
                    data: {
                        order_id: customer.order_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success' && data.data.length > 0) {
                            var latestOrder = data.data[0];
                            var orderInfo = latestOrder.service_name + ' - ' + latestOrder.price + ' - ' + customer.order_status;
                            $('#customerOrder').val(orderInfo);
                        } else {
                            $('#customerOrder').val('No recent orders');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching customer order details:', error);
                        $('#customerOrder').val('Error fetching order details');
                    }
                });

                $('#customerModal').fadeIn();
            }

            $('.close').on('click', function() {
                $('#customerModal').fadeOut();
            });

            $(window).on('click', function(event) {
                if ($(event.target).is('#customerModal')) {
                    $('#customerModal').fadeOut();
                }
            });

            $('#search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();
                $('.customer-list li').each(function() {
                    if ($(this).text().toLowerCase().includes(searchValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#filter').on('change', function() {
                var filterValue = $(this).val();
                if (filterValue === 'all') {
                    $('.customer-group').show();
                } else if (filterValue === 'received') {
                    $('#ordersReceivedGroup').show();
                    $('#ordersFulfilledGroup, #regularCustomersGroup').hide();
                } else if (filterValue === 'fulfilled') {
                    $('#ordersFulfilledGroup').show();
                    $('#ordersReceivedGroup, #regularCustomersGroup').hide();
                } else if (filterValue === 'regular') {
                    $('#regularCustomersGroup').show();
                    $('#ordersReceivedGroup, #ordersFulfilledGroup').hide();
                }
            });
        });
    </script>
</body>

</html>