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

        // Inside the eventClick function for editing events
        eventClick: function(info) {
            // Open the modal
            $('#editEventModal').modal('show');

            // Populate the form with event data
            $('#editEventId').val(info.event.id);
            $('#editEventName').val(info.event.title);

            // Get the start date/time and format it manually to avoid UTC conversion
            var start = info.event.start;
            var startYear = start.getFullYear();
            var startMonth = start.getMonth() + 1; // JavaScript months are 0-indexed.
            var startDay = start.getDate();
            var startHours = start.getHours();
            var startMinutes = start.getMinutes();

            // Ensure two digits for month, day, hours, and minutes
            startMonth = (startMonth < 10 ? '0' : '') + startMonth;
            startDay = (startDay < 10 ? '0' : '') + startDay;
            startHours = (startHours < 10 ? '0' : '') + startHours;
            startMinutes = (startMinutes < 10 ? '0' : '') + startMinutes;

            // Combine parts to match the datetime-local input format
            var startStr = `${startYear}-${startMonth}-${startDay}T${startHours}:${startMinutes}`;

            $('#editEventStart').val(startStr);

            // Assuming you store the description in event.extendedProps
            $('#editEventDescription').val(info.event.extendedProps.description);
        }

     });
     calendar.render();
 
    // Correctly attach the click event listener to the "Save Event" button
    $('#saveEventButton').click(function() {
        // AJAX request to add an event
        $.ajax({
            type: 'POST',
            url: './users/add_events.php',
            data: $('#addEventForm').serialize(), // Serialize the form data
            success: function(response) {
                // Handle success
                try {
                    var data = JSON.parse(response);
                    if(data.success) {
                        $('#eventModal').modal('hide'); // Close the modal
                        calendar.refetchEvents(); // Refresh calendar events
                        //alert('Event added successfully.');
                        // Optionally, refresh or update your event calendar view here
                    } else {
                        //alert('Failed to add event: ' + data.message);
                    }
                } catch (e) {
                   //alert('Failed to add event. Please try again.');
                }
            },
            error: function() {
                // Handle error
                //alert('There was an error adding the event. Please try again.');
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





