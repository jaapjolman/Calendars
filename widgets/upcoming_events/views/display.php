<?php if ( defined('ADMIN_THEME') ): ?>
	<style type="text/css">
		<?php foreach ( $calendars['entries'] as $calendar ): ?>
		.calendar_<?php echo $calendar['str_id']; ?>::before
		{
			background-color: <?php echo $calendar['bg_color']['code']; ?> !important;
		}
		<?php endforeach; ?>
	</style>
<?php endif; ?>


<?php if ( ! empty($events) ): ?>
	<?php $print_time = ''; ?>
	<ul class="event-list">
		<?php foreach ( $events as $event ): ?>
		<?php 

			/*
			 *	Make the header times to help separate things
			 */
			$entry_time = date('Y-m-d', $event->start);

			$now = date('Y-m-d');

			if( $print_time != $entry_time )
			{

				// Print label
				echo '<h4>'.($entry_time == $now ? 'Today - ' : ($entry_time == date('Y-m-d', strtotime('+1 Day '.$now)) ? 'Tomorrow - ' : NULL) ).date('l, F jS, Y', strtotime($entry_time)).'</h4>';

				$print_time = $entry_time;
			}
		?>
		<li class="event <?php echo (defined('ADMIN_THEME')) ? 'calendar_'.$event->calendar : null; ?>" >
			<strong><?php echo $event->title; ?></strong> <?php echo $event->description; ?>

			<div class="time"><?php echo date('g:i A', $event->start); ?></div>

			<?php echo (!empty($event->location) ? '<em class="location">'.$event->location.'</em>': null); ?>
		</li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
<div class="blank-slate">
	<div class="no_data">
		<?php echo lang('streams.no_results'); ?>
	</div>
</div>
<?php endif; ?>