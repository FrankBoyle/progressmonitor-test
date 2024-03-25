$(document).ready(function() {
    $('#registrationForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "./users/register.php",
            data: formData,
            success: function(response) {
                // Check the response from your PHP script
                if(response.trim() == "Success!") {
                    $('#signUpModal').modal('hide');
                    alert('Registration successful. You can now log in.');
                } else {
                    // Display the error message directly to the user
                    alert(response);
                }
            }
        });
    });
});



