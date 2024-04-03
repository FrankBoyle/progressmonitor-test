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

     // Initialize FullCalendar
     var calendarEl = document.getElementById('calendar');
     var calendar = new FullCalendar.Calendar(calendarEl, {
         initialView: 'dayGridMonth',
         height: 'auto',
         headerToolbar: {
             left: 'prev,next today',
             center: 'title',
             right: 'dayGridMonth,timeGridWeek'
         },
         events: './users/fetch_events.php', // Load events
         dateClick: function(info) {
             $('#eventModal').modal('show');
             $('#eventStart').val(info.dateStr); // Set the start date based on clicked date
         },
         eventClick: function(info) {
            // Open the modal
            $('#editEventModal').modal('show');

            // Populate the form with event data
            $('#editEventId').val(info.event.id);
            $('#editEventName').val(info.event.title);

            // Format the date and time correctly for the datetime-local input
            var startStr = new Date(info.event.start).toISOString().slice(0, 16);
            $('#editEventStart').val(startStr);

            // Assuming you store the description in event.extendedProps
            $('#editEventDescription').val(info.event.extendedProps.description);
        }
     });
     calendar.render();
 
    // Ensure this is bound correctly and the event is properly prevented.
    $('#addEventForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way

        var eventData = $(this).serialize(); // Serialize the form data

        $.ajax({
            type: "POST",
            url: "./users/add_events.php", // Make sure this URL is correct and accessible
            data: eventData,
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if(data.success) {
                        $('#eventModal').modal('hide'); // Hide the modal on success
                        calendar.refetchEvents(); // Refresh the calendar to show the new event
                        alert('Event added successfully.');
                    } else {
                        // If the response indicates failure, alert the user
                        alert('Failed to add event: ' + data.message);
                    }
                } catch (error) {
                    console.error("Parsing error:", error);
                    alert('Failed to add event. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                // Log and alert the error if the AJAX call itself fails
                console.error("AJAX Error:", status, error);
                alert('Error: Could not save the event.');
            }
        });
    });
 
     // "Edit Event" save changes button click
     $('#saveEventChanges').click(function() {
         var eventId = $('#editEventId').val();
         var eventData = {
             eventId: eventId,
             title: $('#editEventName').val(),
             start: $('#editEventStart').val(),
             description: $('#editEventDescription').val()
         };
 
         $.ajax({
             type: "POST",
             url: './users/update_events.php', // URL to your update event script
             data: eventData,
             success: function(response) {
                 var result = JSON.parse(response);
                 if(result.success) {
                     $('#editEventModal').modal('hide');
                     calendar.refetchEvents(); // Refresh calendar events
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





