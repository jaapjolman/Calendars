<!-- Mini-Calendar for awesome shit -->
<div id="mini-calendar" title="<?php echo lang('calendars:title:jump_to'); ?>" style="display:none;">

  <div class="mini-calendar"></div>

  <script>
    $('.mini-calendar').datepicker({

      showOtherMonths: true,
      selectOtherMonths: true,
      onSelect: function(dateText, inst){	

      // Split up date
      var date = dateText.split('/');

      // Make changes to calendar
      $('#calendar-engine').fullCalendar('gotoDate',date[2],date[0]-1,date[1]);
      $('#calendar-engine').fullCalendar( 'changeView', 'agendaWeek' );

      }
    });
  </script>

</div>