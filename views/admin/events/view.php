<div class="one_whole">

	<section class="title">
		<h4><?php echo lang('calendars:title:view_event'); ?></h4>

		<a class="button btn alignright red confirm" href="<?php echo site_url('admin/calendars/events/delete/'.$event->id); ?>"><?php echo lang('calendars:button:delete'); ?></a>
	</section>

	<section class="item entry-view-wrapper">
		
		<!-- Left Portion -->
		<section class="left">

			<!-- Event Image -->
			<?php if ( ! empty($event->event_image) ):?>
				<?php echo $event->event_image; ?>
			<?php else: ?>
				<img src="<?php echo $this->module_details['path'].'/img/no-image.jpg'; ?>" class="event-image">
			<?php endif; ?>

			<!-- Title -->
			<h1 class="title"><?php echo $event->title; ?> <span style="color: <?php echo $calendar->bg_color; ?>;">(<?php echo $calendar->title; ?>)</span></h1>

			<!-- Times -->
			<?php if ( empty($event->all_day) ) : ?>
				<p>
					<?php if ( date('md', strtotime($event->starting)) == date('md', strtotime($event->ending)) ): ?>
						<?php echo date('l, F jS', strtotime($event->starting)); ?>
						<?php echo lang('calendars:misc:from_space'); ?>
						<?php echo date('g:ia', strtotime($event->starting)); ?>
						<?php echo lang('calendars:misc:to_space'); ?>
						<?php echo date('g:ia', strtotime($event->ending)); ?>
					<?php else: ?>
						<?php echo date('D, M jS g:ia', strtotime($event->starting)); ?>
						<?php echo lang('calendars:misc:to_space'); ?>
						<?php echo date('D, M jS g:ia', strtotime($event->ending)); ?>
					<?php endif; ?>
				</p>
			<?php else: ?>
				<p><?php echo date('l, F jS', strtotime($event->starting)); ?> <span class="muted">(<?php echo lang('calendars:misc:all_day'); ?>)</span></p>
			<?php endif; ?>

			<br>

			<div class="tabs">

				<ul class="tab-menu">
				
					<li>
						<a href="#comments-tab">
							<span><?php echo lang('calendars:tab:comments'); ?></span>
						</a>
					</li>

					<!-- Tasks Tab -->
					<?php if( module_installed('tasks') ): ?>
					<li>
						<a href="#tasks-tab">
							<span><?php echo lang('tasks:title:tasks'); ?></span>
						</a>
					</li>
					<?php endif; ?>
				
				</ul>

				
				<!-- Comments -->
				<div class="form_inputs" id="comments-tab">
				
					<fieldset>

						<?php

							/*$this->load->library(
								'comments/comments',
								array(
									'module' => 'calendars',
									'singular' => 'event',
									'plural' => 'events',
									'entry_id' => $event->id,
									'entry_title' => $event->title
									)
								);

							echo $this->comments->display();

							echo $this->comments->form();*/

						?>

						Comments coming soon (2.2 release).

					</fieldset>

				</div>


				<!-- Reminders -->
				<?php if( module_installed('tasks') ): ?>
				<div class="form_inputs" id="tasks-tab">
				
					<fieldset>

						<?php

							$this->load->library('tasks/tasks',
								array(
									'module' => 'calendars',
									'singular' => 'event',
									'plural' => 'events',
									'entry_id' => $event->id
									)
								);

							echo $this->tasks->display();

							echo '<br>';

							echo '<h4>'.lang('tasks:title:create_task').'</h4>';

							echo $this->tasks->form();
						?>

					</fieldset>

				</div>
				<?php endif; ?>


			</div>

		</section>
		<!-- /Left Portion -->


		<!-- Right Portion -->
		<aside class="right">

			<!-- Edit this event -->
			<p class="ntm"><?php echo anchor(site_url('admin/calendars/events/edit/'.$event->id), lang('calendars:link:edit_this_event'), 'style="color:red;"'); ?></p>			

			<!-- Tasks - Mini-Heading -->
			<div class="mini-heading"><span><?php echo lang('calendars:title:tasks'); ?></span></div>


			<!-- Small Tasks List -->
			<?php if( module_installed('tasks') ): ?>

				<?php echo $this->tasks->display(); ?>

				<br>

			<?php else: ?>

				<p><?php echo lang('calendars:error:install_tasks'); ?></p>

			<?php endif; ?>
			<!-- /Small Tasks List -->



			<!-- event Information - Mini-Heading -->
			<div class="mini-heading"><span><?php echo lang('calendars:title:event_information'); ?></span></div>

			<!-- Title -->
			<p><?php echo $event->title; ?> <span class="muted"><?php echo lang('calendars:title'); ?></span></p>

			<!-- Times -->
			<?php if ( empty($event->all_day) ) : ?>
				<p>
					<?php echo date('D, M jS g:ia', strtotime($event->starting)); ?> <span class="muted"><?php echo lang('calendars:starting'); ?></span>
					<br>
					<?php echo date('D, M jS g:ia', strtotime($event->ending)); ?> <span class="muted"><?php echo lang('calendars:ending'); ?></span>
				</p>
			<?php else: ?>
				<p><?php echo date('l, F jS', strtotime($event->starting)); ?> <span class="muted">(<?php echo lang('calendars:misc:all_day'); ?>)</span></p>
			<?php endif; ?>

			<!-- Location -->
			<?php if ( ! empty($event->location) ) : ?>
				<p>
					<span class="muted"><?php echo lang('calendars:location'); ?></span>
					<br>
					<a 
						href="https://maps.google.com/maps?q=<?php echo urlencode($event->location); ?>" 
						style="background-image:url(https://maps.google.com/maps/api/staticmap?center=<?php echo str_replace('+', '%20', urlencode($event->location)); ?>&amp;size=189x90&amp;sensor=false&amp;maptype=terrain&amp;markers=size:small|color:red|<?php echo str_replace('+', '%20', urlencode($event->location)); ?>);"
						class="map"
						target="_blank">
					</a>
					<br>
					<?php echo $event->location; ?><br>
				</p>
			<?php endif; ?>

			<!-- Notes -->
			<?php if ( ! empty($event->notes) ) : ?>
				<p><span class="muted"><?php echo lang('calendars:notes'); ?><br></span><?php echo nl2br($event->notes); ?></p>
			<?php endif; ?>

			

			<!-- Contacts
			<?php //if ( ! empty($contacts['entries']) ): ?>
			<div class="mini-heading"><span><strong><?php //echo $event->name.'</strong> '.strtolower(lang('calendars:title:contacts')); ?></span></div>

				<!-- Loop Em
				<?php //foreach( $contacts['entries'] as $contact ): ?>

				<p class="ntm"><?php //echo anchor(site_url('admin/people/contacts/view/'.$contact['id']), $contact['first_name'].' '.$contact['last_name']).' <span style="color: #777;">'.$contact['title'].'</span>'; ?></p>

			<?php //endforeach; ?>

			<?php //endif; ?>
			<!-- /Contacts -->

		</aside>
		<!-- /Right Portion -->

	<div class="clearfix"></div>
	</section>

</div>	