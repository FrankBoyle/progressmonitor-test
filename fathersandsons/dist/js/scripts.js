$(document).ready(function() {
    $('#registrationform').on('submit', function(e) {
        e.preventDefault(); // Stop the form from causing a page reload.

        $.ajax({
            type: "POST",
            url: "./users/register.php",
            data: $(this).serialize(), // Serializes the form's elements.
            success: function(response) {
                if (response.trim() === "New record created successfully") {
                    $('#signUpModal').modal('hide'); // Close the signup modal
                    alert('Registration successful. You can now log in.'); // Inform the user
                } else {
                    // Handle failure
                    alert('Registration failed: ' + response);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX error
                console.error("AJAX Error: " + status + "\nError: " + error);
                alert('Registration failed. Please try again later.');
            }
        });
    });

    // This should be outside the 'submit' event handler
    var calendarEl = document.getElementById('calendar');
    var calendarHeight = window.matchMedia("(max-width: 799px)").matches ? "auto" : 650; // "auto" for mobile devices
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: calendarHeight,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        dateClick: function(info) {
            // Trigger modal form here
            $('#eventModal').modal('show'); // Assuming you're using Bootstrap's modal
            $('#eventStart').val(info.dateStr); // Automatically set the start date
            $('#eventEnd').val(info.dateStr); // Optionally set the end date
        },
        // Make sure to replace 'path/to/your/fetch-events.php' with the actual path to your PHP script
        events: './users/fetch_events.php',
        aspectRatio: 1.5 // Adjusts the width-to-height ratio of the calendar
    });
    calendar.render();

    function submitEvent() {
        // Construct data object manually if serialization doesn't work
        var eventData = {
            title: $('#eventName').val(),
            start: $('#eventStart').val(),
            end: $('#eventEnd').val(),
            description: $('#eventDescription').val()
        };
    
        $.ajax({
            type: "POST",
            url: "./users/add_events.php",
            data: eventData,
            success: function(response) {
                var data = JSON.parse(response);
                if(data.success) {
                    $('#eventModal').modal('hide');
                    // Add the event to the calendar or refresh events
                } else {
                    alert('Failed to add event.');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                alert('Error: Could not save the event.');
            }
        });
    }
    
});





