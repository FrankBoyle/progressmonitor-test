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

        // Listen for form submission
        $('#addEventForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
    
            // Collect the form data
            let eventName = $('#eventName').val();
            let eventStart = $('#eventStart').val();
            let eventEnd = $('#eventEnd').val();
    
            // Send the data to add_events.php
            $.ajax({
                type: "POST",
                url: "./users/add_events.php", // Path to your add_events.php file
                data: {
                    name: eventName,
                    start: eventStart,
                    end: eventEnd
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if(data.success) {
                        $('#eventModal').modal('hide'); // Hide the modal
                        // Optionally, refresh the calendar or add the event directly
                        calendar.addEvent({
                            title: eventName,
                            start: eventStart,
                            end: eventEnd
                        });
                    } else {
                        alert('Failed to add event: ' + data.message);
                    }
                },
                error: function() {
                    alert('Error: Could not contact the server.');
                }
            });
        });
});





