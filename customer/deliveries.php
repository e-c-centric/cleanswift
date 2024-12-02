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
    <title>Pickups</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="../js/cost.js"></script>
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


        /* Pickups Container */
        .deliveries-wrapper {
            display: flex;
            gap: 20px;
        }

        /* Pickups Container */
        .pickups-container {
            flex: 2;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Active Deliveries Container */
        .active-deliveries-container {
            flex: 1;
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .active-deliveries-container h2 {
            margin-top: 0;
            margin-bottom: 15px;
            text-align: center;
        }

        .active-delivery-tile {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .active-delivery-tile h3 {
            margin: 0 0 10px 0;
            font-size: 1em;
            color: #333;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.8em;
            color: #fff;
            margin-bottom: 10px;
        }

        .badge.pending {
            background-color: #ffc107;
        }

        .badge.in-transit {
            background-color: #17a2b8;
        }

        .badge.completed {
            background-color: #28a745;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            font-size: 0.9em;
            cursor: pointer;
        }

        .checkbox-group input {
            margin-right: 10px;
        }


        /* Delivery Tile Styles */
        .delivery-tile {
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 10px;
            width: 250px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            height: fit-content;
        }

        .delivery-tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .delivery-title {
            font-size: 1.2em;
            margin-bottom: 10px;
            animation: flash 1s infinite;
        }

        @keyframes flash {
            0% {
                color: red;
            }

            50% {
                color: black;
            }

            100% {
                color: red;
            }
        }

        .delivery-details {
            font-size: 0.9em;
            color: #555;
        }

        /* Modal Styles */
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
            padding: 40px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
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

        .modal-content h2 {
            margin-top: 0;
        }

        .modal-content .detail {
            margin-bottom: 15px;
        }

        .modal-content .detail label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .modal-content .detail span {
            display: block;
            padding: 8px;
            background-color: #f4f4f9;
            border-radius: 5px;
        }

        .btn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
        }

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

        /* Active Deliveries Container */
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
                    <li><a href="../customer/cart.php"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
                    <li><a href="../customer/orders.php"><i class="fas fa-box-open"></i>My Orders</a></li>
                    <li><a href="../customer/deliveries.php" class="active"><i class="fas fa-user"></i>Deliveries</a></li>
                    <li><a href="../customer/spending.php"><i class="fas fa-money-check-alt"></i>Payments</a></li>
                    <li><a href="../customer/customer_details.php"><i class="fas fa-user"></i>My Details</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>Deliveries</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Wowza, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>
            <div class="deliveries-wrapper">
                <div class="active-deliveries-container">
                    <h2>Active Deliveries</h2>
                    <!-- Active Delivery Tiles will be dynamically loaded here -->
                </div>
            </div>
        </main>
    </div>

    <div id="deliveryModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Delivery Details</h2>
            <div class="detail">
                <label for="customerAddress">Customer Address</label>
                <span id="customerAddress">1234 Placeholder St, City, Country</span>
            </div>
            <div class="detail">
                <label for="dropoffAddresses">Dropoff Address(es)</label>
                <span id="dropoffAddresses">
                    5678 Example Ave, City, Country<br>
                    9101 Sample Blvd, City, Country
                </span>
            </div>
            <div class="detail">
                <label for="deliveryCost">Cost</label>
                <span id="deliveryCost">GHC0.00</span>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>

    <script>
        $(document).ready(function() {
            fetchActiveDeliveries();

            function fetchActiveDeliveries() {
                $.ajax({
                    url: '../actions/get_active_deliveries_by_customer.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success' && response.data.length > 0) {
                            renderActiveDeliveryTiles(response.data);
                        } else {
                            $('.active-deliveries-container').html('<p>No active deliveries at the moment.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching active deliveries:', error);
                        $('.active-deliveries-container').html('<p>Error loading active deliveries.</p>');
                    }
                });
            }

            function renderActiveDeliveryTiles(activeDeliveries) {
                $('.active-deliveries-container').empty();
                $('.active-deliveries-container').append('<h2>Active Deliveries</h2>');

                $.each(activeDeliveries, function(index, delivery) {
                    var pickupAddress = delivery.dropoff_district;
                    var deliveryStatus = delivery.delivery_status;
                    var deliveryId = delivery.delivery_id;
                    var driverName = delivery.driver_name;
                    var vehicleNumber = delivery.vehicle_number;
                    var vehicleType = delivery.vehicle_type;

                    var $tile = $('<div>').addClass('active-delivery-tile').attr('data-delivery-id', deliveryId);

                    var $title = $('<h3>').text('Provider Address: ' + pickupAddress);
                    var $driverInfo = $('<p>').text('Driver: ' + driverName);
                    var $vehicleInfo = $('<p>').text('Vehicle: ' + vehicleNumber + ' (' + vehicleType + ')');

                    var $badge = $('<span>').addClass('badge');
                    switch (deliveryStatus.toLowerCase()) {
                        case 'coming to pickup':
                            $badge.addClass('in-transit').text('In Transit');
                            break;
                        case 'with customer':
                            $badge.addClass('pending').text('Pending');
                            break;
                        case 'transit to laundry':
                            $badge.addClass('in-transit').text('In Transit');
                            break;
                        case 'transit from laundry':
                            $badge.addClass('in-transit').text('In Transit');
                            break;
                        case 'returned to customer':
                            $badge.addClass('completed').text('Completed');
                            break;
                        default:
                            $badge.addClass('pending').text(deliveryStatus);
                    }

                    var $checkboxGroup = $('<div>').addClass('checkbox-group');

                    var statusOptions = ['With Customer', 'Coming to pickup', 'Transit to laundry', 'Transit from laundry', 'Returned to customer'];

                    var currentStatusIndex = statusOptions.findIndex(option => option.toLowerCase() === deliveryStatus.toLowerCase());

                    if (currentStatusIndex === -1) {
                        currentStatusIndex = statusOptions.length;
                    }

                    $.each(statusOptions, function(i, option) {
                        var $label = $('<label>');
                        var $checkbox = $('<input>').attr({
                            type: 'checkbox',
                            name: 'status',
                            value: option
                        });

                        if (i < currentStatusIndex) {
                            // Previous statuses: checked and disabled
                            $checkbox.prop('checked', true).prop('disabled', true);
                        } else if (i === currentStatusIndex) {
                            // Current status: checked and disabled
                            $checkbox.prop('checked', true).prop('disabled', true);
                        } else {
                            // Future statuses: unchecked and enabled
                            $checkbox.prop('checked', false).prop('disabled', false);
                        }

                        $label.append($checkbox).append(' ' + option);
                        $checkboxGroup.append($label);
                    });

                    // Append driver information to the tile
                    $tile.append($title, $driverInfo, $vehicleInfo, $badge, $checkboxGroup);
                    $('.active-deliveries-container').append($tile);
                });

                // Event delegation for dynamically added checkboxes
                $('.active-deliveries-container').on('change', 'input[type="checkbox"]', function() {
                    var $checkbox = $(this);
                    var $tile = $checkbox.closest('.active-delivery-tile');
                    var deliveryId = $tile.data('delivery-id');
                    var statusOptions = ['With Customer', 'Coming to pickup', 'Transit to laundry', 'Transit from laundry', 'Returned to customer'];

                    var newStatus = $checkbox.val();
                    var currentStatusIndex = statusOptions.findIndex(option => option === newStatus);

                    // Function to check if all previous checkboxes are checked
                    function arePreviousStatusesChecked(index, $tile) {
                        for (var i = 0; i < index; i++) {
                            var status = statusOptions[i];
                            var $prevCheckbox = $tile.find('input[type="checkbox"][value="' + status + '"]');
                            if (!$prevCheckbox.is(':checked')) {
                                return false;
                            }
                        }
                        return true;
                    }

                    // If the checkbox is being checked
                    if ($checkbox.is(':checked')) {
                        // Verify that all previous statuses are checked
                        if (!arePreviousStatusesChecked(currentStatusIndex, $tile)) {
                            Swal.fire("Error", "Please complete the previous status before updating to this status.", "error");
                            // Prevent checking the current checkbox
                            $checkbox.prop('checked', false);
                            return;
                        }

                        // Proceed to update the status via AJAX
                        $.ajax({
                            url: '../actions/update_delivery_status.php',
                            type: 'POST',
                            data: {
                                delivery_id: deliveryId,
                                new_status: newStatus
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire("Success", "Delivery status updated successfully!", "success").then(() => {
                                        fetchActiveDeliveries(); // Refresh the active deliveries list
                                    });
                                } else {
                                    Swal.fire("Error", response.message || "Failed to update delivery status.", "error");
                                    // Uncheck the checkbox on error
                                    $checkbox.prop('checked', false);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating delivery status:', error);
                                Swal.fire("Error", "An error occurred while updating the delivery status.", "error");
                                $checkbox.prop('checked', false);
                            }
                        });
                    } else {
                        // Optionally handle unchecked scenarios if necessary
                        // Currently, checkboxes up to current status are disabled, so unchecking shouldn't occur
                    }
                });
            }

            function openDeliveryModal(deliveryId) {
                $.ajax({
                    url: '../actions/get_delivery_details.php',
                    type: 'GET',
                    data: {
                        delivery_id: deliveryId
                    },
                    dataType: 'json',
                    success: async function(response) {
                        console.log('GET Response:', response);

                        if (response.status === 'success' && response.data.length > 0) {
                            const deliveries = response.data;

                            const pickupAddress = deliveries[0].customer_city;

                            let dropoffAddresses = deliveries.map(delivery => delivery.provider_address);
                            dropoffAddresses = [...new Set(dropoffAddresses)];

                            $('#customerAddress').text(pickupAddress);

                            let dropoffHtml = '';
                            dropoffAddresses.forEach(address => {
                                dropoffHtml += address + '<br>';
                            });
                            $('#dropoffAddresses').html(dropoffHtml);
                            try {
                                let totalCost = 0;
                                const costPromises = dropoffAddresses.map(dropoff => computeCost(pickupAddress, dropoff));
                                const costs = await Promise.all(costPromises);
                                costs.forEach(cost => {
                                    totalCost += cost;
                                });
                                $('#deliveryCost').text('GHC' + totalCost.toFixed(2));

                                // Remove existing Accept Trip button if any to prevent duplicates
                                $('#accept-trip-btn').remove();

                                // Create Accept Trip button
                                const $acceptBtn = $('<button>')
                                    .attr('id', 'accept-trip-btn')
                                    .addClass('btn btn-primary')
                                    .text('Accept Trip');

                                // Append the button to the modal-content
                                $('.modal-content').append($acceptBtn);

                                // Click handler for Accept Trip button
                                $acceptBtn.on('click', function() {
                                    $(this).attr('disabled', true).text('Accepting...');

                                    $.ajax({
                                        url: '../actions/accept_delivery.php',
                                        type: 'POST',
                                        data: {
                                            delivery_id: deliveryId,
                                            cost: totalCost.toFixed(2)
                                        },
                                        dataType: 'json',
                                        success: function(acceptResponse) {
                                            console.log('POST Response:', acceptResponse);

                                            if (acceptResponse.status === 'success') {
                                                Swal.fire("Success", "Trip accepted successfully!", "success")
                                                    .then(() => {
                                                        window.location.reload();
                                                        $('#deliveryModal').fadeOut(function() {
                                                            $('#accept-trip-btn').remove();
                                                        });
                                                    });
                                            } else {
                                                Swal.fire("Error", acceptResponse.message || "Failed to accept the trip.", "error");
                                                $acceptBtn.attr('disabled', false).text('Accept Trip');
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error accepting trip:', error);
                                            Swal.fire("Error", "An error occurred while accepting the trip.", "error");
                                            $acceptBtn.attr('disabled', false).text('Accept Trip');
                                        }
                                    });
                                });
                            } catch (error) {
                                console.error('Error computing cost:', error);
                                $('#deliveryCost').text('Error computing cost');
                                Swal.fire("Error", "Failed to compute delivery cost.", "error");
                            }

                            $('#deliveryModal').fadeIn();
                        } else {
                            Swal.fire("No Details Found", "No delivery details found for this delivery.", "info");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching delivery details:', error);
                        Swal.fire("Error", "Failed to load delivery details.", "error");
                    }
                });
            }

            // Close Modal when 'x' is clicked
            $('.close-modal').on('click', function() {
                $('#deliveryModal').fadeOut();
            });

            // Close Modal when clicking outside the modal content
            $(window).on('click', function(event) {
                if ($(event.target).is('#deliveryModal')) {
                    $('#deliveryModal').fadeOut();
                }
            });
        });
    </script>
</body>

</html>