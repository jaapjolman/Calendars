<!-- Buttons for interacting with the calendar -->
<div class="buttons">
  
  <!-- Navigation -->
  <span>
    <a class="button small" onclick="$('#calendar-engine').fullCalendar('prev');">&lt;&lt;</a>
    <a class="button small" onclick="$('#calendar-engine').fullCalendar('next');">&gt;&gt;</a>
    <a class="button small blue" onclick="$('#calendar-engine').fullCalendar('today');">Today</a>
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <a class="button green small" onclick="$('#calendars').dialog({ position: [10, 150] }); return false;"><?php echo lang('calendars:button:calendars'); ?></a>
    <a class="button green small" onclick="$('#mini-calendar').dialog({ position: [10, 200] }); return false;"><?php echo lang('calendars:button:mini_calendar'); ?></a>
  </span>
  
  <!-- View Modes -->
  <span style="float:right;">
    <a class="view-mode button small blue" title="month" onclick="$('#calendar-engine').fullCalendar( 'changeView', 'month' ); $('a.view-mode').removeClass('blue'); $(this).addClass('blue'); return false;"><?php echo lang('calendars:button:month'); ?></a>
    <a class="view-mode button small" title="agendaWeek" onclick="$('#calendar-engine').fullCalendar( 'changeView', 'agendaWeek' ); $('a.view-mode').removeClass('blue'); $(this).addClass('blue'); return false;"><?php echo lang('calendars:button:week'); ?></a>
    <a class="view-mode button small" title="agendaDay" onclick="$('#calendar-engine').fullCalendar( 'changeView', 'agendaDay' ); $('a.view-mode').removeClass('blue'); $(this).addClass('blue'); return false;"><?php echo lang('calendars:button:day'); ?></a>
    <a class="view-mode button small" title="basicDay" onclick="$('#calendar-engine').fullCalendar( 'changeView', 'basicDay' ); $('a.view-mode').removeClass('blue'); $(this).addClass('blue'); return false;"><?php echo lang('calendars:button:daily_agenda'); ?></a>
    <a class="view-mode button small" title="basicWeek" onclick="$('#calendar-engine').fullCalendar( 'changeView', 'basicWeek' ); $('a.view-mode').removeClass('blue'); $(this).addClass('blue'); return false;"><?php echo lang('calendars:button:weekly_agenda'); ?></a>
  </span>

</div>

<div class="clearfix"></div><br>