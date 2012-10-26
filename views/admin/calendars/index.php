<!-- Load up the calendar -->
<?php $this->load->view('../js/calendar.js.php'); ?>

<section class="title">
	<h4 id="calendar-showing-title"><?php echo lang('calendars:label:calendars'); ?></h4>
</section>

<section class="item">

	<!-- Load the controls / helpers that lay across the top of the calendar -->
	<?php $this->load->view('admin/calendars/partials/controls.php'); ?>
	<?php $this->load->view('admin/calendars/partials/calendars.php'); ?>
	<?php $this->load->view('admin/calendars/partials/mini-calendar.php'); ?>

	<!-- The actual calendar goes here -->
	<div id="calendar-engine"></div>
  
</section>