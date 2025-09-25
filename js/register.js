$(document).ready(function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault();

        // Collect all the form data to match PHP expectations
        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var country = $('#country').val();
        var city = $('#city').val();
        var contact = $('#contact').val();
        var role = $('input[name="role"]:checked').val();

        // Validation - check all required fields
        if (name == '' || email == '' || password == '' || country == '' || city == '' || contact == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return;
        } 
        
        // Password validation (added special character check)
        if (
            password.length < 6 ||
            !password.match(/[a-z]/) ||
            !password.match(/[A-Z]/) ||
            !password.match(/[0-9]/) ||
            !password.match(/[^a-zA-Z0-9]/) //must include special character
        ) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 6 characters long and contain one lowercase letter, one uppercase letter, one number, and one special character!',
            });
            return;
        }

        // AJAX call with correct field names
        $.ajax({
            url: '../actions/register_user_action.php',
            type: 'POST',
            dataType: 'json',
            data: {
                name: name,
                email: email,
                password: password,
                country: country,
                city: city,
                contact: contact,
                role: role
            },
            success: function(response) {
                console.log('Server response:', response); // Debug log
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText); // Debug log
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});
