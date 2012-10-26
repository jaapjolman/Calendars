<script type="text/javascript">
	$(document).ready(function(){
			
		<!-- Parse the calendar and all it's goodness -->
		$('#calendar-engine').fullCalendar({
			
			// Visual and DUI settings
			editable: true,
			dragRevertDuration: 100,
			
			selectable: true,
			selectHelper: true,
			
			weekMode: 'variable',
			
			// Assignable settings
			slotMinutes: <?php echo Settings::get('calendars_slot_minutes'); ?>,
			defaultView: '<?php echo Settings::get('calendars_default_view'); ?>',
			firstHour: '<?php echo Settings::get('calendars_first_hour'); ?>',
			weekends: <?php echo (Settings::get('calendars_weekends') == 1 ? 'true': 'false'); ?>,
						
			
			/*	Events
			-------------------------------------*/
			
			select: function( startDate, endDate, allDay, jsEvent, view ){

				<?php if ( ! empty($calendars['entries']) ): ?>
					window.location = BASE_URL + 'admin/calendars/events/create/' + startDate.getTime() / 1000 + '/' + endDate.getTime() / 1000;
				<?php else: ?>
					alert('<?php echo lang('calendars:error:no_calendars'); ?>');
				<?php endif; ?>
			},
			
			eventRender: function( event, element, view ){
				
				// Style: background-color
				element.find('.fc-event-head').css('background-color', '#'+event.border_color);
				element.children('.fc-event-skin').css('background-color', '#'+event.bg_color);
				
				// Style: border-color
				element.children('.fc-event-skin').parents('.fc-event-skin').css('border-color', '#'+event.border_color);
				element.children('.fc-event-skin').css('border-color', '#'+event.border_color);
				
				// Style: color
				element.children('.fc-event-skin').css('color', '#'+event.text_color);
			},
			
			eventClick: function(event, jsEvent, view){
				
				// Is there a link?
				if(event.link == undefined)
				{
						
					// Go there...
					window.location = BASE_URL + 'admin/calendars/events/edit/' + event.id;
					
					return false;
					
				}
				else
				{
					
					// Go to the link instead
					window.open(event.link);
					return false;
					
				}
			},
			
			eventResize: function(event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view){
				$.get(BASE_URL + 'calendars/events/ajax_adjust/' + event.id + '/' + event.start.getTime() + '/' + event.end.getTime());
			},
			
			eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc){		
				$.get(BASE_URL + 'calendars/events/ajax_adjust/' + event.id + '/' + event.start.getTime() + '/' + event.end.getTime());
			},
			
			viewDisplay: function(view){
				
				// Add the title to the Module title to 
				$('#calendar-showing-title').html( '<?php echo lang('calendars:label:calendar_for'); ?>&nbsp;'+ view.title );
				
				// Remove blue and add gray
				$('a.view-mode').removeClass('blue');
				
				// Add blue and remove gray
				$('a.view-mode[title="'+ view.name +'"]').addClass('blue');
				
			}
			
		});<!-- eof fullCalendar -->
		
	});
</script>