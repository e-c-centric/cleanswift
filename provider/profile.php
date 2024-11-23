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
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-info .info-item {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            font-size: 1.1em;
        }

        .profile-info .info-item:last-child {
            border-bottom: none;
        }

        .profile-info .info-item span {
            font-weight: bold;
            color: #555;
        }

        .profile-info .info-item .label {
            flex: 1;
            text-align: left;
        }

        .profile-info .info-item .value {
            flex: 2;
            text-align: right;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .edit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            font-size: 1em;
        }

        .edit-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
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
            padding: 40px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
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
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .save-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px 20px;
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
                    <li><a href="customers.php"><i class="fas fa-users"></i>Customers</a></li>
                    <li><a href="manage_services.php"><i class="fas fa-cogs"></i>Manage Services</a></li>
                    <li><a href="orders.php"><i class="fas fa-box"></i>Orders</a></li>
                    <li><a href="profile.php" class="active"><i class="fas fa-user"></i>Profile</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Profile</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>
            <div class="profile-info">
                <div class="info-item">
                    <span class="label">Provider Name:</span>
                    <span class="value" id="providerName">Loading...</span>
                </div>
                <div class="info-item">
                    <span class="label">Address:</span>
                    <span class="value" id="providerAddress">Loading...</span>
                </div>
                <div class="info-item">
                    <span class="label">Manager's Name:</span>
                    <span class="value" id="managerName">Loading...</span>
                </div>
                <div class="info-item">
                    <span class="label">Contact:</span>
                    <span class="value" id="contact">Loading...</span>
                </div>
                <div class="button-group">
                    <button class="edit-button" id="editProfile">Edit Profile</button>
                    <button class="edit-button" id="changePassword">Change Password</button>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Profile</h2>
            <form id="profileForm">
                <div class="form-group">
                    <label for="providerNameEdit">Provider Name</label>
                    <input type="text" id="providerNameEdit" name="providerName" value="">
                </div>
                <div class="form-group">
                    <label for="addressEdit">Address</label>
                    <input type="text" id="addressEdit" name="address" value="">
                </div>
                <div class="form-group">
                    <label for="managerNameEdit">Manager's Name</label>
                    <input type="text" id="managerNameEdit" name="managerName" value="">
                </div>
                <div class="form-group">
                    <label for="contactEdit">Contact</label>
                    <input type="text" id="contactEdit" name="contact" value="">
                </div>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Change Password</h2>
            <form id="passwordForm">
                <div class="form-group">
                    <label for="oldPassword">Old Password</label>
                    <input type="password" id="oldPassword" name="oldPassword">
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword">
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword">
                </div>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Function to fetch and display provider profile information
            function loadProviderProfile() {
                $.ajax({
                    url: '../actions/getProviderProfileInfo.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var data = response.data;
                            $('#providerName').text(data.provider_name);
                            $('#providerAddress').text(data.provider_address);
                            $('#managerName').text(data.user_name);
                            $('#contact').text(data.user_contact);

                            // Also, populate the Edit Profile form fields
                            $('#providerNameEdit').val(data.provider_name);
                            $('#addressEdit').val(data.provider_address);
                            $('#managerNameEdit').val(data.user_name);
                            $('#contactEdit').val(data.user_contact);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to fetch profile information.',
                            text: error
                        });
                    }
                });
            }

            // Call the function to load profile info on page load
            loadProviderProfile();

            // Edit Profile Modal Functionality
            var editProfileModal = document.getElementById('editProfileModal');

            // When the user clicks the Edit Profile button, open the modal
            $('#editProfile').on('click', function() {
                editProfileModal.style.display = "block";
            });

            // When the user clicks on <span> (x), close the modal
            $('#editProfileModal .close').on('click', function() {
                editProfileModal.style.display = "none";
            });

            // When the user submits the Edit Profile form
            $('#profileForm').on('submit', function(event) {
                event.preventDefault();

                var providerName = $('#providerNameEdit').val().trim();
                var address = $('#addressEdit').val().trim();
                var managerName = $('#managerNameEdit').val().trim();
                var contact = $('#contactEdit').val().trim();

                // Basic validation
                if (!providerName || !address || !managerName || !contact) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'All fields are required.'
                    });
                    return;
                }

                // Send the updated profile data via AJAX
                $.ajax({
                    url: '../actions/updateProviderInfo.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        provider_name: providerName,
                        provider_address: address,
                        name: managerName,
                        contact: contact
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.message
                        });
                        if (response.status === 'success') {
                            editProfileModal.style.display = "none";
                            loadProviderProfile();
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to update profile.',
                            text: error
                        });
                    }
                });
            });

            var changePasswordModal = document.getElementById('changePasswordModal');

            $('#changePassword').on('click', function() {
                changePasswordModal.style.display = "block";
            });

            $('#changePasswordModal .close').on('click', function() {
                changePasswordModal.style.display = "none";
            });

            $('#passwordForm').on('submit', function(event) {
                event.preventDefault();

                var oldPassword = $('#oldPassword').val().trim();
                var newPassword = $('#newPassword').val().trim();
                var confirmPassword = $('#confirmPassword').val().trim();

                if (!oldPassword || !newPassword || !confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'All fields are required.'
                    });
                    return;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'New passwords do not match.'
                    });
                    return;
                }

                $.ajax({
                    url: '../actions/changePassword.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        old_password: oldPassword,
                        new_password: newPassword
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.message
                        });
                        if (response.status === 'success') {
                            changePasswordModal.style.display = "none";
                            $('#passwordForm')[0].reset();
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to change password.',
                            text: error
                        });
                    }
                });
            });

            $(window).on('click', function(event) {
                if ($(event.target).is(editProfileModal)) {
                    editProfileModal.style.display = "none";
                }
                if ($(event.target).is(changePasswordModal)) {
                    changePasswordModal.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>