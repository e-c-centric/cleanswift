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
    <title>My Spending</title>
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

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            /* Could be more or less, depending on screen size */
            border-radius: 8px;
            position: relative;
        }

        .close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .totals {
                flex-direction: column;
                align-items: center;
            }

            .total-box {
                width: 80%;
                margin-bottom: 15px;
            }

            .modal-content {
                width: 80%;
            }
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
            color: #343a40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #343a40;
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

        .status-paid {
            color: green;
            font-weight: bold;
        }

        .status-incomplete {
            color: orange;
            font-weight: bold;
        }

        .make-payment-btn {
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            animation: pulse 2s infinite;
            transition: background-color 0.3s;
        }

        .make-payment-btn:hover {
            background-color: #218838;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }

            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
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
                    <li><a href="../customer/cart.php"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
                    <li><a href="../customer/orders.php"><i class="fas fa-box-open"></i>My Orders</a></li>
                    <li><a href="../customer/deliveries.php"><i class="fas fa-user"></i>Deliveries</a></li>
                    <li><a href="../customer/spending.php" class="active"><i class="fas fa-money-check-alt"></i>Payments</a></li>
                    <li><a href="../customer/customer_details.php"><i class="fas fa-user"></i>My Details</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Spending 🥲</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>

            <div class="totals">
                <div class="total-box">
                    <h3>Total Due</h3>
                    <p id="total-spent">0</p>
                </div>
                <div class="total-box">
                    <h3>Total Paid</h3>
                    <p id="total-paid">0</p>
                </div>
                <div class="total-box">
                    <h3>Total Outstanding</h3>
                    <p id="total-outstanding">0</p>
                </div>
            </div>



            <table>
                <thead>
                    <tr>
                        <th>Amount (GHC)</th>
                        <th>Paid To</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="payments-table-body">
                    <!-- Payments will be dynamically inserted here -->
                </tbody>
            </table>
        </main>

        <div id="paymentModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Make Payment</h2>
                <form id="paymentForm">
                    <div class="form-group">
                        <label for="email-address">Email Address</label>
                        <input type="email" id="email-address" required />
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount (GHC)</label>
                        <input type="tel" id="amount" required disabled />
                    </div>
                    <div class="form-group">
                        <label for="currency">Currency</label>
                        <input type="text" id="currency" required value="GHS" disabled />
                    </div>
                    <button type="button" id="submitPayment" class="make-payment-btn">Submit Payment</button>
                </form>
            </div>
        </div>




        <script src="https://js.paystack.co/v1/inline.js"></script>
        <script src="../js/pay.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                const endpoint = '../actions/get_payments_by_customer.php';

                $.ajax({
                    url: endpoint,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            const payments = response.data;
                            let totalSpent = 0;
                            let totalPaid = 0;
                            let totalOutstanding = 0;

                            const tableBody = $('#payments-table-body');
                            tableBody.empty();

                            payments.forEach(payment => {
                                const amount = parseFloat(payment.amt);
                                totalSpent += amount;

                                if (payment.payment_status.toLowerCase() === 'paid') {
                                    totalPaid += amount;
                                } else if (payment.payment_status.toLowerCase() === 'incomplete') {
                                    totalOutstanding += amount;
                                }

                                let statusClass = '';
                                if (payment.payment_status.toLowerCase() === 'paid') {
                                    statusClass = 'status-paid';
                                } else if (payment.payment_status.toLowerCase() === 'incomplete') {
                                    statusClass = 'status-incomplete';
                                }

                                let actionButton = '';
                                if (payment.payment_status.toLowerCase() === 'incomplete') {
                                    // Pass both payment_id and amt to makePayment function
                                    actionButton = `<button class="make-payment-btn" onclick="makePayment(${payment.payment_id}, ${payment.amt})">Make Payment</button>`;
                                } else {
                                    actionButton = '-';
                                }

                                const date = new Date(payment.payment_date).toLocaleString();

                                const row = `
                                <tr>
                                    <td>GHC${amount.toFixed(2)}</td>
                                    <td>${payment.service_provider_name}</td>
                                    <td>${date}</td>
                                    <td class="${statusClass}">${payment.payment_status}</td>
                                    <td>${actionButton}</td>
                                </tr>
                            `;
                                tableBody.append(row);
                            });

                            $('#total-spent').text(`GHC${totalSpent.toFixed(2)}`);
                            $('#total-paid').text(`GHC${totalPaid.toFixed(2)}`);
                            $('#total-outstanding').text(`GHC${totalOutstanding.toFixed(2)}`);
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

            function makePayment(paymentId, amount) {
                $('#amount').val(amount.toFixed(2));
                $('#email-address').val('');
                $('#paymentModal').css('display', 'block');

                $('#paymentForm').data('payment-id', paymentId);
            }

            $(document).on('click', '.close', function() {
                $('#paymentModal').css('display', 'none');
            });

            $(window).on('click', function(event) {
                if ($(event.target).is('#paymentModal')) {
                    $('#paymentModal').css('display', 'none');
                }
            });

            $('#submitPayment').on('click', function() {
                const email = $('#email-address').val();
                const amount = $('#amount').val();
                const paymentId = $('#paymentForm').data('payment-id');

                if (!email || !amount) {
                    alert('Please fill in all fields.');
                    return;
                }

                payWithPaystack(email, amount, paymentId);
            });

            function payWithPaystack(email, amount, paymentId) {
                const handler = PaystackPop.setup({
                    key: 'pk_test_4884ce4a815934cd9718338e7aefcb982858cbde',
                    email: email,
                    amount: amount * 100,
                    currency: 'GHS',
                    ref: 'CLEANSWIFT-' + Math.floor((Math.random() * 1000000000) + parseInt(amount)),
                    callback: function(response) {
                        const paymentData = {
                            payment_id: paymentId,
                            amount: amount,
                            payment_ref: response.reference
                        };

                        $.ajax({
                            url: '../actions/checkout_process.php',
                            method: 'POST',
                            data: paymentData,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Payment Successful',
                                        text: response.message
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Payment Failed',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', error);
                                alert('An error occurred while processing payment.');
                            }
                        });
                    },
                    onClose: function() {
                        alert('Transaction was not completed, window closed.');
                    }
                });


                handler.openIframe();
            }



        </script>

</body>

</html>