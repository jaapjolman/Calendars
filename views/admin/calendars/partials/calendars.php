<!-- Calendars that can be toggled -->
<div id="calendars" title="<?php echo lang('calendars:title:calendars'); ?>" style="display:none;">
  
  <div class="buttons" style="margin-bottom:10px;">
    
    <a href="admin/calendars/create" class="button blue small"><?php echo lang('calendars:button:new_calendar'); ?></a>
    <a href="#" class="button red small" onclick="$('ul#calendar-list li div').each(function(){ $(this).addClass('off'); $('#calendar-engine').fullCalendar( 'removeEventSource', $(this).children('a').attr('data-source')); }); return false;">Hide All</a>
    <a href="#" class="button green small" onclick="$('ul#calendar-list li div').each(function(){ $(this).removeClass('off'); $('#calendar-engine').fullCalendar( 'addEventSource', $(this).children('a').attr('data-source')); }); return false;">Show All</a>
    
  </div>
  
  <?php if(!empty($calendars['entries'])): ?>
    
    <ul id="calendar-list">
      <?php foreach($calendars['entries'] as $calendar): ?>
        
        <!-- Calendar -->
        <li id="calendar_<?php echo $calendar['id']; ?>">
          <div style="background-color:<?php echo $calendar['bg_color']['code']; ?>; border:1px solid <?php echo $calendar['bg_color']['code']; ?>;">
            <a 
            	href="#"
              data-source="<?php echo site_url('calendars/events/ajax_event_source/'.$calendar['id']); ?>" 
            	onclick="$(this).parent().toggleClass('off'); if($(this).parent().hasClass('off')){ $('#calendar-engine').fullCalendar( 'removeEventSource', $(this).attr('data-source')); }else{ $('#calendar-engine').fullCalendar( 'addEventSource', $(this).attr('data-source')); }; return false;" 
              style="color:<?php echo $calendar['text_color']['code']; ?>;"
             >
              <?php echo (strlen($calendar['title']) > 70 ? substr($calendar['title'], 0, 70).'...' : $calendar['title']); ?>
            </a>
            <a href="<?php echo site_url('admin/calendars/delete/'.$calendar['id']); ?>" style="float:right; color:<?php echo $calendar['text_color']['code']; ?>;" class="confirm"><?php echo lang('global:delete'); ?>&nbsp;</a>
            <a href="<?php echo site_url('admin/calendars/edit/'.$calendar['id']); ?>" style="float:right; color:<?php echo $calendar['text_color']['code']; ?>;"><?php echo lang('global:edit'); ?>&nbsp;</a>
          </div>
        </li>
        <!-- /Calendar -->
        
      <?php endforeach; ?>
    </ul>
    
  <?php else: ?>
  
    <div class="blank-slate">
      <div class="no_data">
        <?php echo lang('streams.no_results'); ?>
      </div>
    </div>
    
  <?php endif; ?>
  
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	<?php if(!empty($calendars['entries'])): ?>
    
   	<?php foreach($calendars['entries'] as $calendar): ?>
			
			// Show this calendar by default?
			$('#calendar-engine').fullCalendar( 'addEventSource', '<?php echo site_url('calendars/events/ajax_event_source/'.$calendar['id']); ?>');
			
		<?php endforeach; ?>
		
	<?php endif; ?>
	
});
</script>