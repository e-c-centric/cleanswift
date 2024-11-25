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
    <title>Me</title>
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
            background-color: rgb(51, 7, 100);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
        }

        .logo {
            text-align: center;
            background-color: rgb(86, 27, 153);
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
            background-color: rgb(86, 27, 153);
            box-shadow: inset 5px 0 0 #34495e;
        }

        .nav ul li a.active {
            background-color: #f4f4f9;
            color: #333;
            box-shadow: inset 5px 0 0 #34495e;
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
            background-color: rgb(51, 7, 100);
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
                    <li><a href="pickup.php"><i class="fas fa-truck"></i>Deliveries</a></li>
                    <li><a href="earnings.php"><i class="fas fa-dollar-sign"></i>Earning Breakdown</a></li>
                    <li><a href="vehicle.php" class="active"><i class="fas fa-car"></i>My Vehicle</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i>Profile</a></li>
                </ul>
            </nav>
            <a href="../login/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <h1>My Vehicle</h1>
                <div class="user-profile">
                    <button class="user-profile-button">Welcome, <?php echo htmlspecialchars($user_name); ?>!</button>
                </div>
            </header>

            <div class="profile-info">
                <div class="info-item">
                    <span class="label">Vehicle Number:</span>
                    <span class="value" id="vehicleNumber">Loading...</span>
                </div>
                <div class="info-item">
                    <span class="label">Vehicle Type:</span>
                    <span class="value" id="vehicleType">Loading...</span>
                </div>
                <div class="info-item">
                    <span class="label">Option Description:</span>
                    <span class="value" id="optionDescription">Loading...</span>
                </div>
                <div class="button-group">
                    <button class="edit-button" id="editProfile">Edit Vehicle</button>
                </div>
            </div>

            <!-- Edit Vehicle Profile Modal -->
            <div id="editProfileModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Vehicle Profile</h2>
                    <form id="profileForm">
                        <div class="form-group">
                            <label for="vehicleNumberEdit">Vehicle Number</label>
                            <input type="text" id="vehicleNumberEdit" name="vehicleNumber" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicleTypeEdit">Vehicle Type</label>
                            <input type="text" id="vehicleTypeEdit" name="vehicleType" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="optionDescriptionEdit">Option Description</label>
                            <select id="optionDescriptionEdit" name="optionDescription" required>
                                <option value="">Loading options...</option>
                            </select>
                        </div>
                        <button type="submit" class="save-button">Save</button>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        $(document).ready(function() {
            // Function to fetch and display vehicle profile information
            function loadVehicleProfile() {
                $.ajax({
                    url: '../actions/getDriverProfileInfo.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var data = response.data;
                            $('#vehicleNumber').text(data.vehicle_number);
                            $('#vehicleType').text(data.vehicle_type);
                            $('#optionDescription').text(data.option_description);
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
            loadVehicleProfile();

            // Edit Profile Modal Functionality
            var editProfileModal = document.getElementById('editProfileModal');

            // When the user clicks the Edit Profile button, open the modal and load vehicle options
            $('#editProfile').on('click', function() {
                // Populate the input fields with current information
                $('#vehicleNumberEdit').val($('#vehicleNumber').text());
                $('#vehicleTypeEdit').val($('#vehicleType').text());

                editProfileModal.style.display = "block";
                loadVehicleOptions();
            });

            // When the user clicks on <span> (x), close the modal
            $('.modal .close').on('click', function() {
                $(this).closest('.modal').css('display', 'none');
            });

            // Function to load vehicle options and populate the dropdown
            function loadVehicleOptions() {
                $.ajax({
                    url: '../actions/getVehicleOptions.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var options = response.data;
                            var select = $('#optionDescriptionEdit');
                            select.empty(); // Clear existing options
                            select.append('<option value="">Select an option</option>');
                            $.each(options, function(index, option) {
                                select.append('<option value="' + option.option_id + '">' + option.option_description + '</option>');
                            });

                            // Set the current option
                            var currentOption = $('#optionDescription').text();
                            select.val(currentOption);
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
                            title: 'Failed to fetch vehicle options.',
                            text: error
                        });
                    }
                });
            }

            // When the user submits the Edit Profile form
            $('#profileForm').on('submit', function(event) {
                event.preventDefault();

                var vehicleNumber = $('#vehicleNumberEdit').val().trim();
                var vehicleType = $('#vehicleTypeEdit').val().trim();
                var optionId = $('#optionDescriptionEdit').val().trim();

                // Basic validation
                if (!vehicleNumber || !vehicleType || !optionDescription) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'All fields are required.'
                    });
                    return;
                }

                $.ajax({
                    url: '../actions/updateVehicleInfo.php', 
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        vehicle_number: vehicleNumber,
                        vehicle_type: vehicleType,
                        option_id: optionId
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.message
                        });
                        if (response.status === 'success') {
                            editProfileModal.style.display = "none";
                            loadVehicleProfile(); // Refresh the profile info
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

            // Close modals when clicking outside of them
            $(window).on('click', function(event) {
                if ($(event.target).is(editProfileModal)) {
                    editProfileModal.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>