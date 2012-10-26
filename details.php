<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Calendars extends Module {

	public $version = '1.4';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Calendars'
			),
			'description' => array(
				'en' => 'A full-featured and easy to use calendar application.'
			),
			'skip_xss' => true,
			'default_install' => false,
			'frontend' => true,
			'backend' => true,
			'menu' => 'structure',
			'sections' => array(
				
				'calendars' => array(
					'name' 	=> 'calendars:section:calendars',
					'uri' 	=> 'admin/calendars',
					'shortcuts' => array(
						'new_calendar' => array(
							'name' 	=> 'calendars:button:new_calendar',
							'uri' 	=> 'admin/calendars/create',
							'class' => 'add'
							),
						'new_event' => array(
							'name' 	=> 'calendars:button:new_event',
							'uri' 	=> 'admin/calendars/events/create',
							'class' => 'add'
							),
						),
					),// eof Calendars section

				'events' => array(
					'name' 	=> 'calendars:section:events',
					'uri' 	=> 'admin/calendars/events',
					'shortcuts' => array(
						'new_calendar' => array(
							'name' 	=> 'calendars:button:new_calendar',
							'uri' 	=> 'admin/calendars/create',
							'class' => 'add'
							),
						'new_event' => array(
							'name' 	=> 'calendars:button:new_event',
							'uri' 	=> 'admin/calendars/events/create',
							'class' => 'add'
							),
						),
					),// eof Events section

				),// Eof sections
		);
	}

	public function install()
	{

		// Load streams driver 'n all
		$this->load->driver('Streams');
		$this->lang->load('calendars/calendars');
		$this->load->helper('calendars/calendars');
		$this->load->model('files/file_folders_m');
		$this->load->model('files/file_m');
		$this->load->library('files/Files');



		// Create options for repeats_every dropdown
		$repeats_every_choices = '';
		for ( $i = 1; $i < 32; $i++ ) $repeats_every_choices .= ($i==1?NULL:"\n")."{$i} : {$i}";

		// Hours options
		$hours = ''; for($h=1;$h<25;$h++) $hours .= "{$h}=".($h>12?$h-12:$h).' '.($h>12?'PM':'AM').'|'; $hours = trim($hours, '|');



		/* Install settings
		----------------------------------------------------------------------------*/

		// Remove any old settings
		$this->db->delete('settings', array('module' => 'calendars'));

		$settings = array(
			array(
				'slug' => 'calendars_default_view',
				'title' => 'Default View',
				'description' => 'What calendar view would you like to load by default?',
				'`default`' => 'month',
				'`value`' => 'month',
				'type' => 'select',
				'`options`' => 'month=Month|agendaWeek=Week|agendaDay=Day|basicDay=Daily Agenda|basicWeek=Weekly Agenda',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'calendars'
				),
			array(	
				'slug' => 'calendars_first_hour',
				'title' => 'Beginning of Day',
				'description' => 'When does your day begin?',
				'`default`' => '9',
				'`value`' => '9',
				'type' => 'select',
				'`options`' => $hours,
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'calendars'
				),
			array(
				'slug' => 'calendars_weekends',
				'title' => 'Show Weekends?',
				'description' => 'Do you want to enable the display of Saturday / Sunday in calendar views?',
				'`default`' => '1',
				'`value`' => '1',
				'type' => 'select',
				'`options`' => '0=Disabled|1=Enabled',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'calendars'
				),
			array(
				'slug' => 'calendars_slot_minutes',
				'title' => 'Minutes Slot Size',
				'description' => 'How many minutes are each slot?',
				'`default`' => '15',
				'`value`' => '15',
				'type' => 'select',
				'`options`' => '5=5|10=10|15=15|30=30',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'calendars'
				),
			);// Eof settings
		
		// Try installing the settings
		foreach ($settings as $setting)
		{
			log_message('debug', '-- Settings: installing '.$setting['slug']);

			if ( ! $this->db->insert('settings', $setting))
			{
				log_message('debug', '-- -- could not install '.$setting['slug']);
				return false;
			}
		}



		/* Install System Folders
		----------------------------------------------------------------------------*/

		// Add the "Calendars Module" folder
		$folders['calendars_module'] = $this->files->create_folder(0, lang('calendars:folder:calendars_module'), 'local', '');

		// Add the "Events" folder
		$folders['events'] = $this->files->create_folder($folders['calendars_module']['data']['id'], lang('calendars:folder:events'), 'local', '');

		// Add the "Calendars" folder
		$folders['calendars'] = $this->files->create_folder($folders['calendars_module']['data']['id'], lang('calendars:folder:calendars'), 'local', '');
		


		/* Install Streams Data - Calendars
		----------------------------------------------------------------------------*/

		// Add calendars
		$this->streams->streams->add_stream('Calendars', 'calendars', 'calendars', 'calendars_', NULL);
		$this->streams->streams->update_stream('calendars', 'calendars', array('stream_prefix' => 'calendars_', 'view_options' => array('created')));

		// Add events
		$this->streams->streams->add_stream('Events', 'events', 'calendars', 'calendars_', NULL);
		$this->streams->streams->update_stream('events', 'calendars', array('stream_prefix' => 'calendars_', 'view_options' => array('starting', 'title', 'location', 'description')));

		// Get some info for later
		$streams['calendars'] = $this->streams->streams->get_stream('calendars', 'calendars');



		// Build the fields
		$fields = array(

			array(
				'name'			=> 'lang:calendars:str_id',
				'slug'			=> 'str_id',
				'namespace'		=> 'calendars',
				'type'			=> 'text',
				'extra'			=> array('exact_length' => 10),
				),
			array(
				'name'			=> 'lang:calendars:title',
				'slug'			=> 'title',
				'namespace'		=> 'calendars',
				'type'			=> 'text',
				),
			array(
				'name'			=> 'lang:calendars:notes',
				'slug'			=> 'notes',
				'namespace'		=> 'calendars',
				'type'			=> 'textarea',
				),
			array(
				'name'			=> 'lang:calendars:description',
				'slug'			=> 'description',
				'namespace'		=> 'calendars',
				'type'			=> 'wysiwyg',
				'extra'			=>	array('editor_type' => 'advanced'),
				),
			array(
				'name'			=> 'lang:calendars:bg_color',
				'slug'			=> 'bg_color',
				'namespace'		=> 'calendars',
				'type'			=> 'color_picker',
				'extra'			=> array('default_color' => '#3366CC'),
				),
			array(
				'name'			=> 'lang:calendars:text_color',
				'slug'			=> 'text_color',
				'namespace'		=> 'calendars',
				'type'			=> 'color_picker',
				'extra'			=> array('default_color' => '#FFFFFF'),
				),
			array(
				'name'			=> 'lang:calendars:privacy',
				'slug'			=> 'privacy',
				'namespace'		=> 'calendars',
				'type'			=> 'choice',
				'extra'		 	=> array('choice_type' => 'dropdown', 'choice_data' => lang('calendars:choice:privacy')),
				),
			array(
				'name'			=> 'lang:calendars:shared',
				'slug'			=> 'shared',
				'namespace'		=> 'calendars',
				'type'			=> 'choice',
				'extra'		 	=> array('choice_type' => 'checkboxes', 'choice_data' => lang('calendars:choice:shared')),
				),
			array(
				'name'			=> 'lang:calendars:gc_id',
				'slug'			=> 'gc_id',
				'namespace'		=> 'calendars',
				'type'			=> 'text',
				),
			array(
				'name'			=> 'lang:calendars:feed_url',
				'slug'			=> 'feed_url',
				'namespace'		=> 'calendars',
				'type'			=> 'text',
				),
			array(
				'name'			=> 'lang:calendars:calendar_id',
				'slug'			=> 'calendar_id',
				'namespace'		=> 'calendars',
				'type'			=> 'relationship',
				'extra'		 	=> array('choose_stream' => $streams['calendars']->id),
				),
			array(
				'name'			=> 'lang:calendars:location',
				'slug'			=> 'location',
				'namespace'		=> 'calendars',
				'type'			=> 'text',
				),
			array(
				'name'			=> 'lang:calendars:all_day',
				'slug'			=> 'all_day',
				'namespace'		=> 'calendars',
				'type'			=> 'choice',
				'extra'		 	=> array('choice_type' => 'checkboxes', 'choice_data' => lang('calendars:choice:all_day')),
				),
			array(
				'name'			=> 'lang:calendars:starting',
				'slug'			=> 'starting',
				'namespace'		=> 'calendars',
				'type'			=> 'datetime',
				'extra'		 	=> array('use_time' => 'yes', 'input_type' => 'datepicker'),
				),
			array(
				'name'			=> 'lang:calendars:ending',
				'slug'			=> 'ending',
				'namespace'		=> 'calendars',
				'type'			=> 'datetime',
				'extra'		 	=> array('use_time' => 'yes', 'input_type' => 'datepicker'),
				),
			array(
				'name'			=> 'lang:calendars:busy',
				'slug'			=> 'busy',
				'namespace'		=> 'calendars',
				'type'			=> 'choice',
				'extra'		 	=> array('choice_type' => 'checkboxes', 'choice_data' => "1 : Yes, show me as busy during this time.", 'default_value' => '1'),
				),
			array(
				'name'			=> 'lang:calendars:repeating',
				'slug'			=> 'repeating',
				'namespace'		=> 'calendars',
				'type'			=> 'choice',
				'extra'		 	=> array('choice_type' => 'checkboxes', 'choice_data' => lang('calendars:choice:repeating')),
				),
			array(
				'name'			=> 'lang:calendars:repeat_span',
				'slug'			=> 'repeat_span',
				'namespace'		=> 'calendars',
				'type'			=> 'choice',
				'extra'		 	=> array('choice_type' => 'radio', 'choice_data' => lang('calendars:choice:repeat_span'), 'default_value' => 'D'),
				),
			array(
				'name'			=> 'lang:calendars:repeats_every',
				'slug'			=> 'repeats_every',
				'namespace'		=> 'calendars',
				'type'			=> 'choice',
				'extra'		 	=> array('choice_type' => 'dropdown', 'choice_data' => $repeats_every_choices, 'default_value' => '2'),
				),
			array(
				'name'			=> 'lang:calendars:repeating_ends',
				'slug'			=> 'repeating_ends',
				'namespace'		=> 'calendars',
				'type'			=> 'datetime',
				'extra'		 	=> array('use_time' => 'no', 'input_type' => 'datepicker'),
				),
			array(
				'name'			=> 'lang:calendars:event_image',
				'slug'			=> 'event_image',
				'namespace'		=> 'calendars',
				'type'			=> 'image',
				'extra'			=> array('folder' => $folders['events']['data']['id']),
				),
			array(
				'name'			=> 'lang:calendars:calendar_image',
				'slug'			=> 'calendar_image',
				'namespace'		=> 'calendars',
				'type'			=> 'image',
				'extra'			=> array('folder' => $folders['calendars']['data']['id']),
				),
			);

		// Add all the fields
		$this->streams->fields->add_fields($fields);



		/* Calendars assignments
		-----------------------------------------------------------*/
		$this->streams->fields->assign_field('calendars', 'calendars', 'str_id', 			array('required' => true, 'unique' => true, 'instructions' => 'lang:calendars:instructions:str_id'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'title', 			array('required' => true, 'instructions' => 'lang:calendars:instructions:title'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'privacy', 			array('required' => true, 'instructions' => 'lang:calendars:instructions:privacy'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'notes',		 		array('instructions' => 'lang:calendars:instructions:notes'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'description', 		array('instructions' => 'lang:calendars:instructions:description'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'bg_color', 			array('instructions' => 'lang:calendars:instructions:bg_color'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'text_color', 		array('instructions' => 'lang:calendars:instructions:text_color'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'shared', 			array('instructions' => 'lang:calendars:instructions:shared'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'gc_id', 			array('instructions' => 'lang:calendars:instructions:gc_id'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'feed_url', 			array('instructions' => 'lang:calendars:instructions:feed_url'));
		$this->streams->fields->assign_field('calendars', 'calendars', 'calendar_image',	array('instructions' => 'lang:calendars:instructions:calendar_image'));



		/* Events assignments
		-----------------------------------------------------------*/
		$this->streams->fields->assign_field('calendars', 'events', 'str_id', 				array('required' => true, 'unique' => true));
		$this->streams->fields->assign_field('calendars', 'events', 'calendar_id', 			array('required' => true, 'instructions' => 'lang:calendars:instructions:calendar_id'));
		$this->streams->fields->assign_field('calendars', 'events', 'title', 				array('required' => true, 'instructions' => 'lang:calendars:instructions:title'));
		$this->streams->fields->assign_field('calendars', 'events', 'starting', 			array('required' => true, 'instructions' => 'lang:calendars:instructions:starting'));
		$this->streams->fields->assign_field('calendars', 'events', 'ending', 				array('required' => true, 'instructions' => 'lang:calendars:instructions:ending'));
		$this->streams->fields->assign_field('calendars', 'events', 'notes',	 			array('instructions' => 'lang:calendars:instructions:notes'));
		$this->streams->fields->assign_field('calendars', 'events', 'description', 			array('instructions' => 'lang:calendars:instructions:description'));
		$this->streams->fields->assign_field('calendars', 'events', 'location', 			array('instructions' => 'lang:calendars:instructions:location'));
		$this->streams->fields->assign_field('calendars', 'events', 'all_day', 				array('instructions' => 'lang:calendars:instructions:all_day'));
		$this->streams->fields->assign_field('calendars', 'events', 'busy', 				array('instructions' => 'lang:calendars:instructions:busy'));
		$this->streams->fields->assign_field('calendars', 'events', 'repeating', 			array('instructions' => 'lang:calendars:instructions:repeating'));
		$this->streams->fields->assign_field('calendars', 'events', 'repeat_span', 			array('instructions' => 'lang:calendars:instructions:repeat_span'));
		$this->streams->fields->assign_field('calendars', 'events', 'repeats_every', 		array('instructions' => 'lang:calendars:instructions:repeats_every'));
		$this->streams->fields->assign_field('calendars', 'events', 'repeating_ends', 		array('instructions' => 'lang:calendars:instructions:repeating_ends'));
		$this->streams->fields->assign_field('calendars', 'events', 'event_image',	 		array('instructions' => 'lang:calendars:instructions:event_image'));



		// Good to go
		return true;
	}

	public function uninstall()
	{
		/*	Remove Module Settings
		------------------------------------------------------------------*/

		// Delete calendars module settings
		$this->db->delete('settings', array('module' => 'calendars'));

		/*	Remove Streams Data
		------------------------------------------------------------------*/

		// Load the Streams Core
		$this->load->driver('Streams');

		// Remove streams and destruct everything
		$this->streams->utilities->remove_namespace('calendars');

		// Weeeeeeee
		return true;
	}


	public function upgrade($old_version)
	{
		// Load the Streams Core
		$this->load->driver('Streams');


		// The magic...
		switch ( $old_version )
		{

			// Default out
			default: break;				
		}

		return true;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "";
	}
}
/* End of file details.php */
