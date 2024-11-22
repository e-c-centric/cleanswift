$(document).ready(function () {
    $('#loginForm').on('submit', function (event) {
        event.preventDefault();

        var email = $('#loginEmail').val();
        var password = $('#loginPassword').val();

        console.log('Validating login form...');

        if (!validateEmail(email)) {
            Swal.fire('Error', 'Please enter a valid email address.', 'error');
            console.log('Validation failed: Invalid email address.');
            return;
        }

        if (password.length < 6) {
            Swal.fire('Error', 'Password must be at least 6 characters long.', 'error');
            console.log('Validation failed: Password too short.');
            return;
        }

        $.ajax({
            url: '../actions/login_action.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function (data) {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success',
                        text: data.message,
                        icon: 'success',
                        timer: 5000,
                        showConfirmButton: false
                    }).then(() => {
                        var role_id = parseInt(data.data.role_id);
                        console.log('Role ID:', role_id);
                        switch (role_id) {
                            case 1:
                                window.location.href = '../customer/dashboard.php';
                                break;
                            case 2:
                                window.location.href = '../driver/dashboard.php';
                                break;
                            case 3:
                                window.location.href = '../provider/dashboard.php';
                                break;
                            case 4:
                                window.location.href = '../view/dashboard.php';
                                break;
                            default:
                                Swal.fire('Error', 'Invalid role ID.', 'error');
                        }
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'An error occurred while processing your request.', 'error');
            }
        });

        console.log('Validation passed.');
    });

    function validateEmail(email) {
        var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(email);
    }
});