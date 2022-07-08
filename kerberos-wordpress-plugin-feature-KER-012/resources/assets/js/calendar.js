document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: [],
      selectable: true,
      select: function(start, end, jsEvent, view) {
          document.getElementById('datetime').value = (moment(start.start).toISOString().split("T")[0]);
          $('#modal_toggle').click();
      }	
    });
    calendar.render();
  });