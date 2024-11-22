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
    <title>My Cart</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css"> <!-- FontAwesome CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
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
                    <li><a href="../customer/dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="../customer/services.php"><i class="fas fa-concierge-bell"></i>Available Services</a></li>
                    <li><a href="../customer/providers.php"><i class="fas fa-store"></i>Available Providers</a></li>
                    <li><a href="../customer/cart.php" class="active"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
                    <li><a href="../customer/orders.php"><i class="fas fa-box-open"></i>My Orders</a></li>
                    <li><a href="../customer/customer_details.php"><i class="fas fa-user"></i>My Details</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Cart</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Service Requested</th>
                        <th>Provider Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Time Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cartItems">
                </tbody>
            </table>

            <div class="total-cost" id="totalCost">
            </div>

            <div style="display: flex; justify-content: space-between;">
                <button class="empty-cart-button" id="emptyCartButton">Empty Cart</button>
                <button class="checkout-button" id="checkoutButton">Proceed to Checkout</button>
            </div>
            <!-- <button class="checkout-button" id="checkoutButton">Proceed to Checkout</button>
            <button class="empty-cart-button" id="emptyCartButton">Empty Cart</button> -->


            <div class="empty-cart-button" id="emptyCartMessage" style="display: none;">
                <p>Your cart is empty. <a href="../customer/services.php">Browse services</a> to add items to your cart.</p>
            </div>
        </main>
    </div>

    <!-- Provider Details Modal -->
    <div id="providerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Provider Details</h2>
            <p id="providerDetails"></p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadCartItems();

            function loadCartItems() {
                $.ajax({
                    url: '../actions/getCartByCustomer.php',
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            var cartItems = $('#cartItems');
                            var totalCost = 0;
                            cartItems.empty();
                            response.data.forEach(function(item) {
                                var itemTotal = item.price * item.quantity;
                                cartItems.append(`
                                    <tr>
                                        <td>${item.service_name}</td>
                                        <td><a href="#" class="provider-link" data-provider-name="${item.provider_name}" data-provider-address="${item.provider_address}">${item.provider_name}</a></td>
                                        <td>$${parseFloat(item.price).toFixed(2)}</td>
                                        <td>
                                            <input type="number" class="quantity-input" value="${item.quantity}" data-service-id="${item.service_id}">
                                        </td>
                                        <td>$${itemTotal.toFixed(2)}</td>
                                        <td>${item.added_at}</td>
                                        <td class="action-buttons">
                                            <button class="update-button btn-primary" data-service-id="${item.service_id}"><i class="fas fa-sync-alt"></i></button>
                                            <button class="delete-button btn-danger" data-service-id="${item.service_id}"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                `);
                                totalCost += itemTotal;
                            });
                            $('#totalCost').text('Total Cost: $' + totalCost.toFixed(2));
                            attachEventHandlers();
                            $('#checkoutButton').show();
                            $('#emptyCartMessage').hide();
                        } else {
                            $('#cartItems').empty();
                            $('#totalCost').text('');
                            $('#checkoutButton').hide();
                            $('#emptyCartMessage').show();
                        }
                    }
                });
            }

            function attachEventHandlers() {
                $('.update-button').on('click', function() {
                    var serviceId = $(this).data('service-id');
                    var quantity = $(this).closest('tr').find('.quantity-input').val();
                    $.ajax({
                        url: '../actions/updateCartItem.php',
                        type: 'POST',
                        data: {
                            service_id: serviceId,
                            quantity: quantity
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: response.status === 'success' ? 'success' : 'error',
                                title: response.message
                            });
                            if (response.status === 'success') {
                                loadCartItems();
                            }
                        }
                    });
                });

                $('.delete-button').on('click', function() {
                    var serviceId = $(this).data('service-id');
                    $.ajax({
                        url: '../actions/deleteCartItem.php',
                        type: 'POST',
                        data: {
                            service_id: serviceId
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: response.status === 'success' ? 'success' : 'error',
                                title: response.message
                            });
                            if (response.status === 'success') {
                                loadCartItems();
                            }
                        }
                    });
                });

                $('.provider-link').on('click', function(event) {
                    event.preventDefault();
                    var providerName = $(this).data('provider-name');
                    var providerAddress = $(this).data('provider-address');
                    $('#providerDetails').html(`<strong>${providerName}</strong><br>${providerAddress}`);
                    $('#providerModal').show();
                });

                $('.close').on('click', function() {
                    $('#providerModal').hide();
                });

                $(window).on('click', function(event) {
                    if ($(event.target).is('#providerModal')) {
                        $('#providerModal').hide();
                    }
                });
                $('#emptyCartButton').on('click', function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will clear all items in your cart.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, clear it!',
                        cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Type your name to confirm',
                                input: 'text',
                                inputPlaceholder: 'Enter your name',
                                showCancelButton: true,
                                confirmButtonText: 'Confirm',
                                cancelButtonText: 'Cancel',
                                preConfirm: (name) => {
                                    if (name !== '<?php echo addslashes($user_name); ?>') {
                                        Swal.showValidationMessage('Name does not match');
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '../actions/clearCart.php',
                                        type: 'POST',
                                        success: function(response) {
                                            Swal.fire({
                                                icon: response.status === 'success' ? 'success' : 'error',
                                                title: response.message
                                            });
                                            if (response.status === 'success') {
                                                loadCartItems();
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>