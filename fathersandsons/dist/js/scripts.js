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


