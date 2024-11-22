<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../view/dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CleanSwift - Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/login_register.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/formValidation.js"></script>
    <script src="../js/loginValidation.js"></script>
</head>

<body>
    <div class="background">
        <div class="overlay">
            <div class="container d-flex flex-column align-items-center justify-content-center text-center">
                <h1 class="welcome-text">Welcome to CleanSwift</h1>
                <p class="sub-text">Your All-in-One Laundry Management Solution</p>
                <div class="button-group">
                    <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal"
                        data-bs-target="#loginModal">Login</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#registerModal">Register</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login to CleanSwift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="../actions/loginprocess.php" method="post">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <p>Don't have an account? <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal"
                            data-bs-target="#registerModal">Register here</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register for CleanSwift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm">
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="registerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerContact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="registerContact" name="contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerRole" class="form-label">Registering as</label>
                            <select class="form-control" id="registerRole" name="role" required>
                                <option value="">Select Role</option>
                                <option value="customer">Customer</option>
                                <option value="driver">Driver</option>
                                <option value="provider">Provider</option>
                            </select>
                        </div>
                        <div id="customerFields" class="d-none">
                            <div class="mb-3">
                                <label for="registerCountry" class="form-label">Country</label>
                                <select class="form-control" id="registerCountry" name="country">
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="registerCity" class="form-label">Address (GhanaPost GPS)</label>
                                <input type="text" class="form-control" id="registerCity" name="city">
                                <small class="form-text text-muted">This address will be used for all laundry pickups.</small>
                            </div>
                        </div>
                        <div id="driverFields" class="d-none">
                            <div class="mb-3">
                                <label for="registerLicense" class="form-label">License Number</label>
                                <input type="text" class="form-control" id="registerLicense" name="license_number">
                            </div>
                            <div class="mb-3">
                                <label for="registerVehicleNumber" class="form-label">Vehicle Number</label>
                                <input type="text" class="form-control" id="registerVehicleNumber" name="vehicle_number">
                            </div>
                            <div class="mb-3">
                                <label for="registerVehicleModel" class="form-label">Vehicle Model</label>
                                <input type="text" class="form-control" id="registerVehicleModel" name="vehicle_model">
                                <small class="form-text text-muted">e.g. Hyundai Elantra.</small>
                            </div>
                            <div class="mb-3">
                                <label for="registerVehicleType" class="form-label">Vehicle Type</label>
                                <select class="form-control" id="registerVehicleType" name="vehicle_type">
                                    <option value="">Select Vehicle Type</option>
                                </select>
                            </div>
                        </div>
                        <div id="providerFields" class="d-none">
                            <div class="mb-3">
                                <label for="registerProviderName" class="form-label">Provider Name</label>
                                <input type="text" class="form-control" id="registerProviderName" name="provider_name">
                            </div>
                            <div class="mb-3">
                                <label for="registerProviderAddress" class="form-label">Provider Address (GhanaPost GPS)</label>
                                <input type="text" class="form-control" id="registerProviderAddress" name="provider_address" placeholder="XX-1111-2222" maxlength="13">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <p>Already have an account? <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal"
                            data-bs-target="#loginModal">Login here</a></p>
                    <p>If you are an admin, please visit the admin registration page.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const vehicleTypes = $('#registerVehicleType');
        $.ajax({
            url: '../actions/getVehicleOptions.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    console.log('Vehicle options:', data.data);
                    data.data.forEach(vehicleType => {
                        const option = $('<option></option>').val(vehicleType.option_id).text(vehicleType.option_description);
                        vehicleTypes.append(option);
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                Swal.fire('Error', 'Failed to fetch vehicle options.', 'error');
            }
        });
    </script>
</body>

</html>