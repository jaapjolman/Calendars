<ul>

	<li>
		<label for="lists">Show Upcoming:</label>
		<?php echo form_dropdown('upcoming_span', array('+1 week'=>'7 Days', '+2 weeks'=>'2 Weeks', '+1 month'=>'1 Month'), $options['upcoming_span']); ?>
	</li>

	<li>
		<label for="lists">Calendars:</label>
		<?php echo form_dropdown('calendars', array(), $options['calendars']); ?>
	</li>

</ul>