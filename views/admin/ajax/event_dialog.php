<!-- Title -->
<strong><?php echo $event->title; ?></strong>

<!-- Times -->
<?php if($event->all_day == 0): ?>
<p>
	<strong><?php echo lang('calendars:starting'); ?>: </strong><?php echo $event->starting; ?><br />
  <strong><?php echo lang('calendars:ending'); ?>: </strong><?php echo $event->ending; ?>
</p>
<?php else: ?>
<p><strong>All Day</strong></p>
<?php endif; ?>

<!-- Location -->
<?php if($event->location != NULL): ?>
	<p>
		<a href="http://maps.google.com/?q=<?php echo urlencode($event->location); ?>" class="google-it" target="_blank"></a>
		&nbsp;
		<a href="http://maps.google.com/?q=<?php echo urlencode($event->location); ?>" target="_blank"><?php echo $event->location; ?></a>
	</p>
<?php else: ?>
	<p>&nbsp;</p>
<?php endif; ?>

<!-- Description -->
<?php if($event->description != ''): ?>
	<p><?php echo nl2br($event->description); ?></p>
<?php endif; ?>


<div class="buttons">
	<a href="<?php echo site_url('admin/calendars/events/edit/'.$event->id); ?>" class="button small"><?php echo lang('global:edit'); ?></a>
  <a href="<?php echo site_url('admin/calendars/events/view/'.$event->id); ?>" class="button small"><?php echo lang('global:view'); ?></a>
  <a href="<?php echo site_url('admin/calendars/events/delete/'.$event->id.'/true'); ?>" class="button confirm small red"><?php echo lang('global:delete'); ?></a>
</div>