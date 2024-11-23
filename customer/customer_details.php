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
    <title>My Details</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
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
            padding: 40px;
            overflow-y: auto;
            background-color: #f4f4f9;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .main-header h1 {
            margin: 0;
            font-size: 2em;
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

        .profile-info img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid #007bff;
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
                    <li><a href="../customer/dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li><a href="../customer/services.php"><i class="fas fa-concierge-bell"></i>Available Services</a></li>
                    <li><a href="../customer/providers.php"><i class="fas fa-store"></i>Available Providers</a></li>
                    <li><a href="../customer/cart.php"><i class="fas fa-shopping-cart"></i>My Cart</a></li>
                    <li><a href="../customer/orders.php"><i class="fas fa-box-open"></i>My Orders</a></li>
                    <li><a href="../customer/customer_details.php" class="active"><i class="fas fa-user"></i>My Details</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Details</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?></button>
                </div>
            </header>
            <div class="profile-info">
                <img src="" alt="Profile Picture" id="profilePicture">
                <div class="info-item">
                    <span class="label">Name:</span>
                    <span class="value" id="profileName"></span>
                </div>
                <div class="info-item">
                    <span class="label">Contact:</span>
                    <span class="value" id="profileContact"></span>
                </div>
                <div class="info-item">
                    <span class="label">Country:</span>
                    <span class="value" id="profileCountry"></span>
                </div>
                <div class="info-item">
                    <span class="label">City:</span>
                    <span class="value" id="profileCity"></span>
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
            <form id="profileForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profilePictureInput">Profile Picture (PNG only, Max 2MB)</label>
                    <input type="file" id="profilePictureInput" name="profilePicture" accept="image/png">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
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
                    <input type="password" id="oldPassword" name="oldPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            loadCustomerProfile();

            var editProfileModal = document.getElementById('editProfileModal');
            var changePasswordModal = document.getElementById('changePasswordModal');

            var editProfileBtn = document.getElementById('editProfile');
            var changePasswordBtn = document.getElementById('changePassword');

            var spans = document.querySelectorAll('.close');

            editProfileBtn.onclick = function() {
                editProfileModal.style.display = "block";
            }
            changePasswordBtn.onclick = function() {
                changePasswordModal.style.display = "block";
            }

            spans.forEach(span => {
                span.onclick = function() {
                    editProfileModal.style.display = "none";
                    changePasswordModal.style.display = "none";
                }
            });

            window.onclick = function(event) {
                if (event.target == editProfileModal) {
                    editProfileModal.style.display = "none";
                }
                if (event.target == changePasswordModal) {
                    changePasswordModal.style.display = "none";
                }
            }

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

            $('#profileForm').on('submit', function(event) {
                event.preventDefault();

                var form = this;
                var fileInput = $('#profilePictureInput')[0];
                var file = fileInput.files[0];

                // Validate file only if a file is selected
                if (file) {
                    // Validate file type (PNG)
                    if (file.type !== 'image/png') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Only PNG files are allowed for the profile picture.'
                        });
                        return;
                    }

                    // Validate file size (Max 2MB)
                    var maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'The profile picture must be less than 2MB.'
                        });
                        return;
                    }
                }

                var formData = new FormData(form);

                $.ajax({
                    url: '../actions/updateCustomerProfileInfo.php', // Ensure this endpoint exists
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json', // Ensure response is treated as JSON
                    success: function(response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.message
                        });
                        if (response.status === 'success') {
                            editProfileModal.style.display = "none";
                            $('#profileForm')[0].reset();
                            loadCustomerProfile(); // Refresh the profile info
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

            function loadCustomerProfile() {
                $.ajax({
                    url: '../actions/getCustomerProfileInfo.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var data = response.data;
                            $('#profileName').text(data.user_name);
                            $('#profileContact').text(data.user_contact);
                            $('#profileCountry').text(data.customer_country);
                            $('#profileCity').text(data.customer_city);
                            $('#name').val(data.user_name);
                            $('#contact').val(data.user_contact);
                            $('#country').val(data.customer_country);
                            $('#city').val(data.customer_city);

                            if (data.customer_image) {
                                var imgSrc = 'data:image/png;base64,' + data.customer_image;
                                $('#profilePicture').attr('src', imgSrc);
                            } else {
                                $('#profilePicture').attr('src', '../images/default_profile.png');
                            }
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

            $('#profilePictureInput').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profilePicture').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

</body>

</html>