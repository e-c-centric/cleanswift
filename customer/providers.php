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
    <title>Available Providers</title>
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

        .providers-table {
            width: 100%;
            border-collapse: collapse;
        }

        .providers-table th,
        .providers-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .providers-table th {
            background-color: #007bff;
            color: white;
        }

        .provider-row {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .provider-row:hover {
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
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
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

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .select-service {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .select-service:hover {
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
                    <li><a href="../customer/dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="../customer/services.php"><i class="fas fa-concierge-bell"></i>Available Services</a></li>
                    <li><a href="../customer/providers.php" class="active"><i class="fas fa-store"></i>Available Providers</a></li>
                    <li><a href="../customer/cart.php"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
                    <li><a href="../customer/orders.php"><i class="fas fa-box-open"></i>My Orders</a></li>
                    <li><a href="../customer/deliveries.php"><i class="fas fa-user"></i>Deliveries</a></li>
                    <li><a href="../customer/customer_details.php"><i class="fas fa-user"></i>My Details</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Available Providers</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <div class="search-filter">
                <input type="text" id="search" placeholder="Search providers...">
                <select id="filter">
                    <option value="">Filter by service</option>
                </select>
            </div>

            <table class="providers-table">
                <thead>
                    <tr>
                        <th>Provider Name</th>
                        <th>Provider Address</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="providersList">
                    <!-- Providers will be dynamically loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Provider Services Modal -->
    <div id="providerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="providerName">Provider Name</h2>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody id="servicesList">
                    <!-- Services will be dynamically loaded here -->
                </tbody>
            </table>
        </div>
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
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <input type="hidden" id="serviceId" name="serviceId">
                <button type="submit" class="save-button">Add to Cart</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadProviders();
            loadServices();

            $('#search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();
                filterProviders(searchValue, $('#filter').val());
            });

            $('#filter').on('change', function() {
                var filterValue = $(this).val();
                filterProviders($('#search').val().toLowerCase(), filterValue);
            });

            function loadProviders() {
                $.ajax({
                    url: '../actions/getProviders.php',
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            var providersList = $('#providersList');
                            providersList.empty();
                            response.data.forEach(function(provider) {
                                providersList.append(`
                                    <tr class="provider-row" data-provider-id="${provider.provider_id}" data-services="${provider.services}">
                                        <td>${provider.provider_name}</td>
                                        <td>${provider.provider_address}</td>
                                        <td>${provider.street}, ${provider.area}, ${provider.district}, ${provider.region}</td>
                                    </tr>
                                `);
                            });
                            attachProviderEventHandlers();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    }
                });
            }

            function loadServices() {
                $.ajax({
                    url: '../actions/getServicesWithDetails.php',
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            var filter = $('#filter');
                            response.data.forEach(function(service) {
                                filter.append(`<option value="${service.service_name}">${service.service_name}</option>`);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    }
                });
            }

            function filterProviders(searchValue, filterValue) {
                searchValue = searchValue.toLowerCase().trim();
                filterValue = filterValue.toLowerCase().trim();

                $('#providersList tr').each(function() {
                    var providerName = $(this).find('td:nth-child(1)').text().toLowerCase().trim();
                    var providerServices = $(this).data('services').toLowerCase().trim();

                    var providerServicesArray = providerServices.split(',').map(function(service) {
                        return service.trim();
                    });

                    var matchesFilter = filterValue === "" || providerServicesArray.includes(filterValue);

                    var matchesSearch = providerName.includes(searchValue);
                    $(this).toggle(matchesSearch && matchesFilter);
                });
            }

            function attachProviderEventHandlers() {
                $('.provider-row').on('click', function() {
                    var providerId = $(this).data('provider-id');
                    var providerName = $(this).find('td:nth-child(1)').text();
                    $('#providerName').text(providerName);
                    loadProviderServices(providerId);
                    $('#providerModal').show();
                });
            }

            function loadProviderServices(providerId) {
                $.ajax({
                    url: '../actions/getServiceByProvider.php',
                    type: 'GET',
                    data: {
                        provider_id: providerId
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            var servicesList = $('#servicesList');
                            servicesList.empty();
                            response.data.forEach(function(service) {
                                servicesList.append(`
                                    <tr>
                                        <td>${service.service_name}</td>
                                        <td>$${service.price}</td>
                                        <td><button class="select-service" data-service-id="${service.service_id}" data-service-name="${service.service_name}" data-service-price="${service.price}">Select</button></td>
                                    </tr>
                                `);
                            });
                            attachSelectServiceEventHandlers();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    }
                });
            }

            function attachSelectServiceEventHandlers() {
                $('.select-service').on('click', function() {
                    var serviceId = $(this).data('service-id');
                    var serviceName = $(this).data('service-name');
                    var servicePrice = $(this).data('service-price');

                    $('#serviceId').val(serviceId);
                    $('#serviceName').val(serviceName);
                    $('#servicePrice').val(servicePrice);
                    $('#quantity').val(1);

                    $('#requestServiceModal').show();
                });
            }

            $('.close').on('click', function() {
                $('#providerModal').hide();
                $('#requestServiceModal').hide();
            });

            $(window).on('click', function(event) {
                if ($(event.target).is('#providerModal')) {
                    $('#providerModal').hide();
                }
                if ($(event.target).is('#requestServiceModal')) {
                    $('#requestServiceModal').hide();
                }
            });

            $('#requestServiceForm').on('submit', function(event) {
                event.preventDefault();
                var serviceId = $('#serviceId').val();
                var quantity = $('#quantity').val();

                $.ajax({
                    url: '../actions/addCartItem.php',
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
                            $('#requestServiceModal').hide();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>