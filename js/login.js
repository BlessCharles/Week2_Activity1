$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();

        
        email = $('#email').val();
        password = $('#password').val();
        

        if (email == '' || password == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });

            return;
        }

        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            data: {
                
                email: email,
                password: password,
                
            },
            dataType:'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
        
                        window.location.href = '../index.php';
                        
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});