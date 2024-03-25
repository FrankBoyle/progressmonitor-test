/*!
* Start Bootstrap - One Page Wonder v6.0.6 (https://startbootstrap.com/theme/one-page-wonder)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-one-page-wonder/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project


$(document).ready(function() {
    $('#registrationForm').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission
        var formData = $(this).serialize(); // Serialize the form data

        $.ajax({
            type: "POST",
            url: "./users/register.php",
            data: formData,
            success: function(response) {
                // Assuming your PHP script echoes "success" on successful registration
                if(response.trim() == "success") {
                    $('#signUpModal').modal('hide'); // Close the modal
                    // Optional: Show a success message or redirect
                    alert('Registration successful. You can now log in.');
                } else {
                    // Handle registration failure
                    alert('Registration failed: ' + response);
                }
            }
        });
    });
});


