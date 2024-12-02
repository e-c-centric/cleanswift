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
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background-color: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            height: fit-content;
        }

        .logo {
            text-align: center;
            background-color: #007bff;
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

        /* Make Payment Button Styling */
        .make-payment-btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .make-payment-btn:hover {
            background-color: #218838;
        }

        /* Pulsing Row Animation */
        @keyframes pulse {
            0% {
                background-color: rgba(0, 123, 255, 0.1);
            }

            50% {
                background-color: rgba(0, 123, 255, 0.3);
            }

            100% {
                background-color: rgba(0, 123, 255, 0.1);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Status Classes Styling */
        .status-in-progress {
            color: #ffc107;
            font-weight: bold;
        }

        .status-fulfilled {
            color: #28a745;
            font-weight: bold;
        }

        .status-cancelled {
            color: #dc3545;
            font-weight: bold;
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
            background-color: #495057;
            box-shadow: inset 5px 0 0 #007bff;
        }

        .nav ul li a.active {
            background-color: #f4f4f9;
            color: #333;
            box-shadow: inset 5px 0 0 #007bff;
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

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .cart-table th,
        .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .cart-table td {
            vertical-align: middle;
            background-color: #f9f9f9;
        }

        .cart-table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        .cart-table tr:hover td {
            background-color: #e9ecef;
        }

        .cart-table .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            transition: border-color 0.3s;
        }

        .cart-table .quantity-input:focus {
            border-color: #007bff;
            outline: none;
        }

        .cart-table .action-buttons {
            display: flex;
            gap: 10px;
        }

        .cart-table .action-buttons button {
            border: none;
            background: none;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .cart-table .action-buttons button:hover {
            transform: scale(1.1);
        }

        .cart-table .action-buttons .btn-primary {
            color: #007bff;
        }

        .cart-table .action-buttons .btn-danger {
            color: #dc3545;
        }

        .total-cost {
            text-align: right;
            font-size: 1.5em;
            margin-top: 20px;
        }

        .checkout-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            position: relative;
            right: 0;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: pulse 2s infinite;
        }

        .checkout-button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        .d-flex {
            display: flex;
            justify-content: flex-start;
        }

        .empty-cart-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            position: relative;
            right: 0;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .empty-cart-button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .d-flex {
            display: flex;
            justify-content: flex-end;
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
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
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


        /* Additional Modal Styling for Delivery Options */
        .delivery-modal {
            display: none;
            position: fixed;
            z-index: 2;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .delivery-modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            text-align: center;
        }

        .delivery-option-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
            transition: background-color 0.3s;
        }

        .delivery-option-button:hover {
            background-color: #0056b3;
        }

        .status-in-progress {
            color: #ffc107;
            font-weight: bold;
        }

        .status-fulfilled {
            color: #28a745;
            font-weight: bold;
        }

        .status-cancelled {
            color: #dc3545;
            font-weight: bold;
        }

        .order-details {
            margin-top: 20px;
        }

        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-details th,
        .order-details td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-details th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>Cleanswift</h2>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="../customer/dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="../customer/services.php"><i class="fas fa-concierge-bell"></i>Available Services</a></li>
                    <li><a href="../customer/providers.php"><i class="fas fa-store"></i>Available Providers</a></li>
                    <li><a href="../customer/cart.php"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
                    <li><a href="../customer/orders.php" class="active"><i class="fas fa-box-open"></i>My Orders</a></li>
                    <li><a href="../customer/deliveries.php"><i class="fas fa-user"></i>Deliveries</a></li>
                    <li><a href="../customer/customer_details.php"><i class="fas fa-user"></i>My Details</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Orders</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>
            <div class="orders">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Service Provider</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Orders will be displayed here -->
                    </tbody>
                </table>
                <p id="noOrdersMessage" style="display: none;">You have no orders.</p>
            </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="modal" role="dialog" aria-labelledby="orderDetailsTitle" aria-modal="true">
        <div class="modal-content">
            <span class="close" aria-label="Close">&times;</span>
            <h2 id="orderDetailsTitle">Order Details</h2>
            <div class="order-details">
                <p><strong>Service Provider:</strong> <span id="detailProviderName"></span></p>
                <p><strong>Order Date:</strong> <span id="detailOrderDate"></span></p>
                <p><strong>Status:</strong> <span id="detailStatus" class="status"></span></p>
                <h3>Order Items</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsBody">
                        <!-- Order items will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadOrders();

            function loadOrders() {
                $.ajax({
                    url: '../actions/getOrders.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success' && response.data.length > 0) {
                            var ordersTableBody = $('.cart-table tbody');
                            ordersTableBody.empty();
                            response.data.forEach(function(order) {
                                var statusClass = '';
                                if (order.status === 'In progress') {
                                    statusClass = 'status-in-progress';
                                } else if (order.status === 'Fulfilled') {
                                    statusClass = 'status-fulfilled';
                                } else if (order.status === 'Cancelled') {
                                    statusClass = 'status-cancelled';
                                }

                                // Determine the service provider name
                                var serviceProviderName = order.driver_name ? order.driver_name : order.provider_name;

                                // Determine if the status is 'Fulfilled' to show the payment button
                                var paymentButton = '';
                                if (order.status === 'Fulfilled') {
                                    paymentButton = `<button class="make-payment-btn" data-order-id="${order.order_id}">Make Payment</button>`;
                                }

                                ordersTableBody.append(`
                        <tr data-order-id="${order.order_id}" class="pulse">
                            <td>${order.order_date}</td>
                            <td class="${statusClass}">${order.status}</td>
                            <td>${serviceProviderName}</td>
                            <td>${paymentButton}</td>
                        </tr>
                    `);
                            });
                            attachOrderClickHandlers();
                            $('.cart-table').show();
                            $('#noOrdersMessage').hide();
                        } else {
                            $('.cart-table').hide();
                            $('#noOrdersMessage').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to load orders.',
                            text: error
                        });
                    }
                });
            }

            // Function to Attach Click Handlers to Orders and Payment Buttons
            function attachOrderClickHandlers() {
                // Handler for row clicks
                $('.cart-table tbody').on('click', 'tr', function() {
                    var orderId = $(this).data('order-id');

                    // Extract order-level details from the table row
                    var orderDate = $(this).find('td:eq(0)').text();
                    var status = $(this).find('td:eq(1)').text();
                    var serviceProviderName = $(this).find('td:eq(2)').text();

                    // Set order-level details in the modal
                    $('#detailProviderName').text(serviceProviderName);
                    $('#detailOrderDate').text(orderDate);
                    $('#detailStatus').text(status);

                    // Update status class based on status
                    var statusClass = '';
                    if (status === 'In progress') {
                        statusClass = 'status-in-progress';
                    } else if (status === 'Fulfilled') {
                        statusClass = 'status-fulfilled';
                    } else if (status === 'Cancelled') {
                        statusClass = 'status-cancelled';
                    }
                    $('#detailStatus').removeClass('status-in-progress status-fulfilled status-cancelled').addClass(statusClass);

                    // Fetch order items via AJAX
                    fetchOrderItems(orderId);
                });

                // Handler for Make Payment buttons using event delegation
                $('.cart-table tbody').on('click', '.make-payment-btn', function(e) {
                    e.stopPropagation(); // Prevent triggering the row click event
                    var orderId = $(this).data('order-id');

                    // Implement your payment logic here
                    Swal.fire({
                        title: 'Make Payment',
                        text: `Proceed to make payment for Order ID: ${orderId}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Pay',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to payment page or initiate payment process
                            window.location.href = `/payment.php?order_id=${orderId}`;
                        }
                    });
                });
            }

            // Function to Fetch and Populate Order Items
            function fetchOrderItems(orderId) {
                $.ajax({
                    url: '../actions/getOrderDetails.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        order_id: orderId
                    },
                    success: function(response) {
                        var orderItemsBody = $('#orderItemsBody');
                        orderItemsBody.empty();

                        if (response.status === 'success' && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                orderItemsBody.append(`
                                <tr>
                                    <td>${item.service_name}</td>
                                    <td>${item.quantity}</td>
                                    <td>$${parseFloat(item.unit_price).toFixed(2)}</td>
                                    <td>$${(parseFloat(item.unit_price) * parseInt(item.quantity)).toFixed(2)}</td>
                                </tr>
                            `);
                            });
                        } else {
                            orderItemsBody.append(`
                            <tr>
                                <td colspan="4">No items found for this order.</td>
                            </tr>
                        `);
                        }

                        // Show the modal
                        $('#orderDetailsModal').show();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to fetch order details.',
                            text: error
                        });
                    }
                });
            }

            // Close Modal When Clicking on <span> (x)
            $('.close').on('click', function() {
                $('.modal').hide();
            });

            // Close Modal When Clicking Outside of Modal Content
            $(window).on('click', function(event) {
                if ($(event.target).hasClass('modal')) {
                    $('.modal').hide();
                }
            });
        });
    </script>
</body>

</html>