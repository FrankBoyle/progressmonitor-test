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
    let calendarEl = document.getElementById('calendar');
    let calendarHeight = window.matchMedia("(max-width: 799px)").matches ? "auto" : 650; // "auto" for mobile devices
    let calendar = new FullCalendar.Calendar(calendarEl, {
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

    $('#saveEventButton').click(function(e) {
        e.preventDefault(); // Prevent the default button click behavior
        submitEvent(); // Call your submitEvent function
    });

    // Now, make sure the 'Save Event' button actually submits the form.
    // This can be done by changing the button type to 'submit' in your HTML:
    // <button type="submit" class="btn btn-primary">Save Event</button>

    function submitEvent() {
        var eventData = {
            title: $('#eventName').val(),
            start: $('#eventStart').val(),
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
                    alert('Event added successfully.');
                } else {
                    alert('Failed to add event: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                alert('Error: Could not save the event.');
            }
        });
    }
   
        // Example of binding the event click (adjust according to your calendar setup)
        $('#calendar').on('eventClick', function(event) {
            // Populate the modal fields with the event details
            $('#editEventId').val(event.id);
            $('#editEventName').val(event.title);
            $('#editEventStart').val(event.start.toISOString().slice(0,16)); // Adjust formatting as needed
            $('#editEventDescription').val(event.description);
    
            // Show the modal
            $('#editEventModal').modal('show');
        });
    
        // Handle save changes button click
        $('#saveEventChanges').click(function() {
            var eventId = $('#editEventId').val();
            var title = $('#editEventName').val();
            var start = $('#editEventStart').val();
            var description = $('#editEventDescription').val();
    
            // Send the updated details to the server
            $.ajax({
                url: './users/update_events.php', // Adjust URL as needed
                type: 'POST',
                data: {
                    eventId: eventId,
                    title: title,
                    start: start,
                    description: description
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if(result.success) {
                        // Close the modal
                        $('#editEventModal').modal('hide');
    
                        // Optionally, refresh the calendar or update the event visually
                    } else {
                        alert('Failed to update event: ' + result.message);
                    }
                },
                error: function() {
                    alert('There was an error updating the event. Please try again.');
                }
            });
        });

});





