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
            height: max-content;
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
                    <li><a href="../customer/deliveries.php"><i class="fas fa-user"></i>Deliveries</a></li>
                    <li><a href="../customer/spending.php"><i class="fas fa-money-check-alt"></i>Payments</a></li>
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

    <div id="deliveryOptionsModal" class="delivery-modal">
        <div class="delivery-modal-content">
            <h2>Select Delivery Option</h2>
            <button class="delivery-option-button" id="no_delivery">Drop Off Yourself</button>
            <button class="delivery-option-button" id="delivery">Delivery</button>
        </div>
    </div>

    <!-- Delivery Details Modal -->
    <div id="deliveryDetailsModal" class="modal" role="dialog" aria-labelledby="deliveryDetailsTitle" aria-modal="true" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-content" style="background-color: #fff; margin: 5% auto; padding: 30px 40px; border: 1px solid #888; width: 90%; max-width: 500px; border-radius: 8px; position: relative;">
            <span class="close" style="color: #aaa; position: absolute; right: 20px; top: 15px; font-size: 28px; font-weight: bold; cursor: pointer;" aria-label="Close">&times;</span>
            <h2 id="deliveryDetailsTitle" style="text-align: center; margin-bottom: 20px; color: #333;">Delivery Details</h2>
            <form id="deliveryDetailsForm" style="display: flex; flex-direction: column;">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="pickupTime" style="font-weight: bold; display: block; margin-bottom: 5px; color: #555;">Pickup Time</label>
                    <input type="datetime-local" id="pickupTime" name="pickupTime" required aria-required="true" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;" aria-describedby="pickupTimeHelp">
                    <small id="pickupTimeHelp" style="color: #e74c3c; display: none;">Please enter a valid pickup time.</small>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="dropoffTime" style="font-weight: bold; display: block; margin-bottom: 5px; color: #555;">Dropoff Time</label>
                    <input type="datetime-local" id="dropoffTime" name="dropoffTime" required aria-required="true" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;" aria-describedby="dropoffTimeHelp">
                    <small id="dropoffTimeHelp" style="color: #e74c3c; display: none;">Please enter a valid dropoff time.</small>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="vehicleType" style="font-weight: bold; display: block; margin-bottom: 5px; color: #555;">Vehicle Type</label>
                    <select id="vehicleType" name="vehicleType" required aria-required="true" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;" aria-describedby="vehicleTypeHelp">
                        <option value="" disabled selected style="color: #999;">Select a vehicle type</option>
                        <!-- Options will be loaded dynamically -->
                    </select>
                    <small id="vehicleTypeHelp" style="color: #e74c3c; display: none;">Please select a vehicle type.</small>
                </div>
                <button type="submit" class="save-button" style="background-color: #007bff; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s;"
                    onmouseover="this.style.backgroundColor='#0056b3';"
                    onmouseout="this.style.backgroundColor='#007bff';">Confirm Delivery</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadCartItems();

            // Handle Checkout Button Click
            $('#checkoutButton').on('click', function() {
                $('#deliveryOptionsModal').show();
            });

            // Handle Delivery Option: Delivery
            $('#delivery').on('click', function() {
                $('#deliveryOptionsModal').hide();
                $('#deliveryDetailsModal').show();
            });

            // Handle Delivery Option: No Delivery
            // Handle Delivery Option: No Delivery
            $('#no_delivery').on('click', function() {
                $('#deliveryOptionsModal').hide();
                proceedToCheckout('no_delivery');
            });

            // Handle Delivery Details Form Submission
            $('#deliveryDetailsForm').on('submit', function(event) {
                event.preventDefault();

                var pickupTime = $('#pickupTime').val();
                var dropoffTime = $('#dropoffTime').val();
                var vehicleType = $('#vehicleType').val();

                if (!pickupTime || !dropoffTime || !vehicleType) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Please fill in all delivery details.'
                    });
                    return;
                }

                proceedToCheckout('delivery', {
                    pickup_time: pickupTime,
                    dropoff_time: dropoffTime,
                    vehicle_type: vehicleType
                });

                $('#deliveryDetailsModal').hide();
            });

            // Function to Proceed to Checkout
            function proceedToCheckout(option, deliveryData = {}) {
                var providerIds = [];

                // Collect unique provider IDs from quantity inputs
                $('.quantity-input').each(function() {
                    var providerId = $(this).data('provider-id');
                    if (providerId && !providerIds.includes(providerId)) {
                        providerIds.push(providerId);
                    }
                });

                console.log('Collected Provider IDs:', providerIds); // Debugging Statement

                if (option === 'delivery') {
                    // Process Delivery
                    var deliveryPayload = {
                        pickup_time: deliveryData.pickup_time,
                        dropoff_time: deliveryData.dropoff_time,
                        vehicle_type: deliveryData.vehicle_type,
                        provider_id: providerIds
                    };

                    $.ajax({
                        url: '../actions/request_delivery.php',
                        type: 'POST',
                        dataType: 'json',
                        data: deliveryPayload,
                        success: function(response) {
                            Swal.fire({
                                icon: response.status === 'success' ? 'success' : 'error',
                                title: response.message
                            });
                            if (response.status === 'success') {
                                // Proceed to Place Order
                                placeOrder(providerIds);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Delivery Request Failed',
                                text: error
                            });
                        }
                    });
                } else if (option === 'no_delivery') {
                    placeOrder(providerIds);
                }
            }

            // Function to Place Order
            function placeOrder(providerIds) {
                var orderPayload = {
                    provider_id: providerIds
                };

                $.ajax({
                    url: '../actions/placeOrder.php',
                    type: 'POST',
                    dataType: 'json',
                    data: orderPayload,
                    success: function(response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.message
                        });
                        if (response.status === 'success') {
                            window.location.href = 'cart.php';
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Order Placement Failed',
                            text: error
                        });
                    }
                });
            }

            // Function to Load Cart Items
            function loadCartItems() {
                $.ajax({
                    url: '../actions/getCartByCustomer.php',
                    type: 'GET',
                    dataType: 'json',
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
                                    <td>
                                        <a href="#" class="provider-link" 
                                           data-provider-name="${item.provider_name}" 
                                           data-provider-address="${item.provider_address}" 
                                           data-provider-id="${item.provider_id}">
                                           ${item.provider_name}
                                        </a>
                                    </td>
                                    <td>GHC${parseFloat(item.price).toFixed(2)}</td>
                                    <td>
                                        <input type="number" class="quantity-input" value="${item.quantity}" 
                                               data-service-id="${item.service_id}" 
                                               data-provider-id="${item.provider_id}" min="1">
                                    </td>
                                    <td>GHC${itemTotal.toFixed(2)}</td>
                                    <td>${item.added_at}</td>
                                    <td class="action-buttons">
                                        <button class="update-button btn-primary" data-service-id="${item.service_id}">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <button class="delete-button btn-danger" data-service-id="${item.service_id}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                                totalCost += itemTotal;
                            });
                            $('#totalCost').text('Total Cost: GHC' + totalCost.toFixed(2));
                            attachEventHandlers();
                            $('#checkoutButton').show();
                            $('#emptyCartMessage').hide();
                        } else {
                            $('#cartItems').empty();
                            $('#totalCost').text('');
                            $('#checkoutButton').hide();
                            $('#emptyCartMessage').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to load cart items.',
                            text: error
                        });
                    }
                });
            }

            // Function to Attach Event Handlers
            function attachEventHandlers() {
                // Update Quantity
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
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                icon: response.status === 'success' ? 'success' : 'error',
                                title: response.message
                            });
                            if (response.status === 'success') {
                                loadCartItems();
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: error
                            });
                        }
                    });
                });

                // Delete Cart Item
                $('.delete-button').on('click', function() {
                    var serviceId = $(this).data('service-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you really want to remove this item from your cart?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, remove it!',
                        cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '../actions/deleteCartItem.php',
                                type: 'POST',
                                data: {
                                    service_id: serviceId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    Swal.fire({
                                        icon: response.status === 'success' ? 'success' : 'error',
                                        title: response.message
                                    });
                                    if (response.status === 'success') {
                                        loadCartItems();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Deletion Failed',
                                        text: error
                                    });
                                }
                            });
                        }
                    });
                });

                // Provider Details
                $('.provider-link').on('click', function(event) {
                    event.preventDefault();
                    var providerName = $(this).data('provider-name');
                    var providerAddress = $(this).data('provider-address');
                    $('#providerDetails').html(`<strong>${providerName}</strong><br>${providerAddress}`);
                    $('#providerModal').show();
                });

                // Empty Cart
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
                                        dataType: 'json',
                                        success: function(response) {
                                            Swal.fire({
                                                icon: response.status === 'success' ? 'success' : 'error',
                                                title: response.message
                                            });
                                            if (response.status === 'success') {
                                                loadCartItems();
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Failed to empty cart.',
                                                text: error
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
            }

            // Close Modals When Clicking on <span> (x)
            $('.close').on('click', function() {
                $('.modal').hide();
            });

            // Close Modals When Clicking Outside of Modal Content
            window.onclick = function(event) {
                if ($(event.target).hasClass('modal')) {
                    $('.modal').hide();
                }
            };

            $.ajax({
                url: '../actions/getVehicleOptions.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var vehicleTypeSelect = $('#vehicleType');
                        vehicleTypeSelect.empty();
                        response.data.forEach(function(vehicleType) {
                            vehicleTypeSelect.append(`<option value="${vehicleType.option_id}">${vehicleType.option_description}</option>`);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load vehicle options:', error);
                }
            });

        });

        // Accessibility: Close Delivery Details Modal with Escape Key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('deliveryDetailsModal');
                if (modal.style.display === 'block') {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>