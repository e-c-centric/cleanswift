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

        .orders-list {
            margin-top: 20px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .orders-table th,
        .orders-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .orders-table th {
            background-color: #006400;
            color: white;
        }

        .orders-table tr:hover {
            background-color: #f1f1f1;
        }

        .edit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-button:hover {
            background-color: #0056b3;
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

        .form-group input,
        .form-group select {
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

        /* Enhanced Order Items Table Styles */
        .order-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .order-details th,
        .order-details td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .order-details th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .order-details tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .order-details tr:hover {
            background-color: #e9ecef;
        }

        .order-details th:first-child,
        .order-details td:first-child {
            border-top-left-radius: 8px;
        }

        .order-details th:last-child,
        .order-details td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .order-details th,
        .order-details td {
            vertical-align: middle;
        }

        /* Responsive Styling */
        @media screen and (max-width: 768px) {
            .order-details table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .order-details th,
            .order-details td {
                padding: 10px;
            }
        }

        /* Modal Enhancements */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 30px 40px;
            border: 1px solid #888;
            width: 90%;
            max-width: 700px;
            border-radius: 10px;
            position: relative;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
            }

            to {
                transform: translateY(0);
            }
        }

        .close-modal {
            color: #aaa;
            position: absolute;
            right: 25px;
            top: 20px;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-modal:hover,
        .close-modal:focus {
            color: #000;
        }

        /* Order Details Header */
        .order-details h3 {
            margin-bottom: 15px;
            color: #17a2b8;
            font-size: 20px;
            border-bottom: 2px solid #17a2b8;
            display: inline-block;
            padding-bottom: 5px;
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
                    <li><a href="customers.php"><i class="fas fa-users"></i>Customers</a></li>
                    <li><a href="manage_services.php"><i class="fas fa-cogs"></i>Manage Services</a></li>
                    <li><a href="orders.php" class="active"><i class="fas fa-box"></i>Orders</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Orders</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Hello, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <section class="orders-list">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Customer Email</th>
                            <th>Customer Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <!-- Orders will be populated here dynamically -->
                    </tbody>
                </table>
                <p id="noOrdersMessage" style="display: none;">No orders found.</p>
            </section>
        </main>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="order-details">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            fetchOrders();

            // Fetch Orders from getSellerOrders.php
            function fetchOrders() {
                $.ajax({
                    url: '../actions/getSellerOrders.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success' && data.data.length > 0) {
                            populateOrdersTable(data.data);
                            $('#noOrdersMessage').hide();
                        } else {
                            $('#ordersTableBody').empty();
                            $('#noOrdersMessage').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching orders:', error);
                        $('#noOrdersMessage').text('Error loading orders.').show();
                    }
                });
            }

            // Populate Orders Table
            function populateOrdersTable(orders) {
                var $tbody = $('#ordersTableBody');
                $tbody.empty();

                $.each(orders, function(index, order) {
                    var statusClass = getStatusClass(order.status);

                    var $tr = $('<tr>').attr('data-order-id', order.order_id);

                    // Customer Name
                    $('<td>').text(order.user_name).appendTo($tr);

                    // Order Date
                    $('<td>').text(order.order_date).appendTo($tr);

                    // Status with Colored Class
                    $('<td>').text(order.status).addClass(statusClass).appendTo($tr);

                    // Customer Email
                    $('<td>').text(order.user_email).appendTo($tr);

                    // Customer Contact
                    $('<td>').text(order.user_contact).appendTo($tr);

                    // Add Click Event to Row
                    $tr.on('click', function() {
                        var orderId = $(this).data('order-id');
                        showModal(orderId);
                    });

                    // Actions
                    var $actions = $('<td>');

                    // Fulfill Button
                    $('<button>')
                        .html('<i class="fas fa-check-circle"></i>') // FontAwesome Check Icon
                        .addClass('action-button fulfill-button') // Add classes for styling
                        .attr('title', 'Fulfill Order') // Tooltip for accessibility
                        .on('click', function(event) {
                            event.stopPropagation(); // Prevent triggering row click
                            var orderId = $(this).closest('tr').data('order-id');
                            fulfillOrder(orderId);
                        })
                        .appendTo($actions);

                    // Cancel Button
                    $('<button>')
                        .html('<i class="fas fa-times-circle"></i>') // FontAwesome Times Icon
                        .addClass('action-button cancel-button') // Add classes for styling
                        .attr('title', 'Cancel Order') // Tooltip for accessibility
                        .on('click', function(event) {
                            event.stopPropagation(); // Prevent triggering row click
                            var orderId = $(this).closest('tr').data('order-id');
                            cancelOrder(orderId);
                        })
                        .appendTo($actions);

                    $tr.append($actions);
                    $tbody.append($tr);
                });
            }

            // Determine Status Class based on Status Text
            function getStatusClass(status) {
                switch (status.toLowerCase()) {
                    case 'pending':
                        return 'status-pending';
                    case 'in progress':
                        return 'status-in-progress';
                    case 'completed':
                        return 'status-completed';
                    case 'cancelled':
                        return 'status-cancelled';
                    default:
                        return '';
                }
            }

            // Populate Order Details in Modal
            function populateOrderDetails(orderId, items) {
                $('#detailOrderId').text(orderId);
                // Set Order Date and Status if available
                // Example:
                // $('#detailOrderDate').text(orderDate);
                // $('#detailStatus').text(status).removeClass().addClass(getStatusClass(status));

                var $tbody = $('#orderItemsBody');
                $tbody.empty(); // Clear previous items

                $.each(items, function(index, item) {
                    var $tr = $('<tr>');

                    // Service Name
                    $('<td>')
                        .text(item.service_name)
                        .attr('data-label', 'Service Name')
                        .appendTo($tr);

                    // Quantity
                    $('<td>')
                        .text(item.quantity)
                        .attr('data-label', 'Quantity')
                        .appendTo($tr);

                    // Unit Price
                    $('<td>')
                        .text(`$${parseFloat(item.unit_price).toFixed(2)}`)
                        .attr('data-label', 'Unit Price')
                        .appendTo($tr);

                    // Total Price
                    var totalPrice = parseFloat(item.unit_price) * parseInt(item.quantity);
                    $('<td>')
                        .text(`$${totalPrice.toFixed(2)}`)
                        .attr('data-label', 'Total Price')
                        .appendTo($tr);

                    $tbody.append($tr);
                });
            }

            // Show Modal
            function showModal(orderId) {
                $('#orderDetailsModal').fadeIn();

                // Fetch Order Details
                $.ajax({
                    url: '../actions/getOrderDetails.php',
                    type: 'GET',
                    data: {
                        order_id: orderId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            populateOrderDetails(orderId, data.data);
                        } else {
                            console.error('Error fetching order details:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching order details:', error);
                    }
                });
            }

            // Hide Modal
            function hideModal() {
                $('#orderDetailsModal').fadeOut();
            }

            // Close Modal When Clicking on <span> (x)
            $('.close-modal').on('click', function() {
                hideModal();
            });

            // Close Modal When Clicking Outside of Modal Content
            $(window).on('click', function(event) {
                if ($(event.target).is('#orderDetailsModal')) {
                    hideModal();
                }
            });
        });
    </script>
</body>

</html>