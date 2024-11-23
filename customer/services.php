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
    <title>Available Services</title>
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

        .search-filter {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-filter input,
        .search-filter select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 48%;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
        }

        .services-table th,
        .services-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .services-table th {
            background-color: #007bff;
            color: white;
        }

        .request-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .request-button:hover {
            background-color: #218838;
        }

        /* Add this to your existing <style> block */

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 30px 40px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }

        .close,
        .close-delivery {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close-delivery:hover {
            color: #000;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .save-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .save-button:hover {
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
                    <li><a href="../customer/services.php" class="active"><i class="fas fa-concierge-bell"></i>Available Services</a></li>
                    <li><a href="../customer/providers.php"><i class="fas fa-store"></i>Available Providers</a></li>
                    <li><a href="../customer/cart.php"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
                    <li><a href="../customer/orders.php"><i class="fas fa-box-open"></i>My Orders</a></li>
                    <li><a href="../customer/customer_details.php"><i class="fas fa-user"></i>My Details</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Available Services</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <div class="search-filter">
                <input type="text" id="search" placeholder="Search services...">
                <select id="filter">
                    <option value="">Filter by provider</option>
                </select>
            </div>

            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Provider Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="servicesList">
                    <!-- Services will be dynamically loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Request Service Modal -->
    <div id="requestServiceModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Request Service</h2>
            <form id="requestServiceForm">
                <div class="form-group">
                    <label for="serviceName">Service Name</label>
                    <input type="text" id="serviceName" name="serviceName" readonly>
                </div>
                <div class="form-group">
                    <label for="servicePrice">Price</label>
                    <input type="text" id="servicePrice" name="servicePrice" readonly>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required min="1" value="1">
                </div>
                <div class="form-group">
                    <label for="deliveryOption">Delivery Option</label>
                    <select id="deliveryOption" name="deliveryOption" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="pickup">Drop off yourself</option>
                        <option value="delivery">Delivery</option>
                    </select>
                </div>
                <!-- Hidden Inputs -->
                <input type="hidden" id="serviceId" name="serviceId">
                <input type="hidden" id="providerId" name="providerId">
                <input type="hidden" id="customerId" name="customerId" value="<?php echo $_SESSION['user_id']; ?>">
                <button type="submit" class="save-button">Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Delivery Details Modal -->
    <div id="deliveryDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-delivery">&times;</span>
            <h2>Delivery Details</h2>
            <form id="deliveryDetailsForm">
                <div class="form-group">
                    <label for="pickupTime">Pickup Time</label>
                    <input type="datetime-local" id="pickupTime" name="pickupTime" required>
                </div>
                <div class="form-group">
                    <label for="dropoffTime">Drop-off Time</label>
                    <input type="datetime-local" id="dropoffTime" name="dropoffTime" required>
                </div>
                <div class="form-group">
                    <label for="vehicleType">Preferred Vehicle Type</label>
                    <select id="vehicleType" name="vehicleType" required>
                        <option value="" disabled selected>Select a vehicle type</option>
                    </select>
                </div>
                <button type="submit" class="save-button">Confirm Delivery</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadServices();
            loadProviders();

            $('#search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();
                filterServices(searchValue, $('#filter').val());
            });

            $('#filter').on('change', function() {
                var filterValue = $(this).val();
                filterServices($('#search').val().toLowerCase(), filterValue);
            });

            function loadServices() {
                $.ajax({
                    url: '../actions/getServicesWithDetails.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var servicesList = $('#servicesList');
                            servicesList.empty();
                            response.data.forEach(function(service) {
                                servicesList.append(`
                                    <tr>
                                        <td>${service.service_name}</td>
                                        <td>${service.provider_name}</td>
                                        <td>$${service.price}</td>
                                        <td>
                                            <button class="request-button" 
                                                    data-service-id="${service.service_id}" 
                                                    data-service-name="${service.service_name}" 
                                                    data-service-price="${service.price}" 
                                                    data-provider-id="${service.provider_id}">
                                                Request Service
                                            </button>
                                        </td>
                                    </tr>
                                `);
                            });
                            attachRequestEventHandlers();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to load services.',
                            text: error
                        });
                    }
                });
            }

            function loadProviders() {
                $.ajax({
                    url: '../actions/getProviders.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var filter = $('#filter');
                            response.data.forEach(function(provider) {
                                filter.append(`<option value="${provider.provider_name}">${provider.provider_name}</option>`);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to load providers.',
                            text: error
                        });
                    }
                });
            }

            function filterServices(searchValue, filterValue) {
                $('#servicesList tr').filter(function() {
                    var serviceName = $(this).find('td:nth-child(1)').text().toLowerCase();
                    var providerName = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var matchesSearch = serviceName.includes(searchValue);
                    var matchesFilter = filterValue === "" || providerName === filterValue.toLowerCase();
                    $(this).toggle(matchesSearch && matchesFilter);
                });
            }

            function attachRequestEventHandlers() {
                $('.request-button').on('click', function() {
                    var serviceId = $(this).data('service-id');
                    var serviceName = $(this).data('service-name');
                    var servicePrice = $(this).data('service-price');
                    var providerId = $(this).data('provider-id');

                    $('#serviceId').val(serviceId);
                    $('#serviceName').val(serviceName);
                    $('#servicePrice').val(servicePrice);
                    $('#quantity').val(1);
                    $('#providerId').val(providerId);

                    $('#requestServiceModal').show();
                });
            }

            $('.close').on('click', function() {
                $('#requestServiceModal').hide();
            });

            $('.close-delivery').on('click', function() {
                $('#deliveryDetailsModal').hide();
            });

            $(window).on('click', function(event) {
                if ($(event.target).is('#requestServiceModal')) {
                    $('#requestServiceModal').hide();
                }
                if ($(event.target).is('#deliveryDetailsModal')) {
                    $('#deliveryDetailsModal').hide();
                }
            });

            $('#deliveryOption').on('change', function() {
                if ($(this).val() === 'delivery') {
                    $('#requestServiceModal').hide();
                    $('#deliveryDetailsModal').show();
                }
            });

            $('#requestServiceForm').on('submit', function(event) {
                event.preventDefault();

                if ($('#deliveryOption').val() === 'delivery') {
                    $('#requestServiceModal').hide();
                    $('#deliveryDetailsModal').show();
                } else {
                    var serviceId = $('#serviceId').val();
                    var quantity = $('#quantity').val();
                    var customerId = $('#customerId').val();

                    $.ajax({
                        url: '../actions/addCartItem.php',
                        type: 'POST',
                        dataType: 'json',
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
                                $('#requestServiceModal').hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to add to cart.',
                                text: error
                            });
                        }
                    });
                }
            });

            $('#deliveryDetailsForm').on('submit', function(event) {
                event.preventDefault();

                var serviceId = $('#serviceId').val();
                var providerId = $('#providerId').val();
                var customerId = $('#customerId').val();
                var quantity = $('#quantity').val();
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

                $.ajax({
                    url: '../actions/addCartItem.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        service_id: serviceId,
                        quantity: quantity,
                        delivery_option: 'delivery'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $.ajax({
                                url: '../actions/request_delivery.php',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    customer_id: customerId,
                                    provider_id: providerId,
                                    pickup_time: pickupTime,
                                    dropoff_time: dropoffTime
                                },
                                success: function(deliveryResponse) {
                                    Swal.fire({
                                        icon: deliveryResponse.status === 'success' ? 'success' : 'error',
                                        title: deliveryResponse.message
                                    });
                                    if (deliveryResponse.status === 'success') {
                                        $('#deliveryDetailsModal').hide();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed to request delivery.',
                                        text: error
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to add to cart.',
                            text: error
                        });
                    }
                });
            });

            function loadVehicleTypes() {
                $.ajax({
                    url: '../actions/getVehicleOptions.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            data.data.forEach(function(vehicle) {
                                $('#vehicleType').append(
                                    $('<option>', {
                                        value: vehicle.option_id,
                                        text: vehicle.option_description
                                    })
                                );
                            });
                        } else {
                            console.error('Failed to load vehicle types:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                });
            }

            loadVehicleTypes();
        });
    </script>
</body>

</html>