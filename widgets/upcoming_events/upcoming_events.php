<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Newsletter Subscribe Widget
 *
 * @author 	Stephen Cozart - PyroCMS Development Team
 * @package 	PyroCMS
 * @subpackage 	calendars
 * @category	Widgets
 */
class Widget_Upcoming_events extends Widgets
{
	
	public $title = 'Upcoming Events';
	public $description = 'Add a simple list of upcoming events to your website.';
	public $author = 'Ryan Thompson - AI Web Systems, Inc.';
	public $website = 'http://aiwebsystems.com';
	public $version = '1.0';
	
	/**
	 * array for storing widget options in the database.
	 * 
	 * upcoming_span for how long out to show upcoming events for.
	 * calendars for what calendar events to show.
	 */
	public $fields = array(
		array(
			'field'   => 'upcoming_span',
			'label'   => 'time span',
			'rules'   => 'trim|xss_clean'
		),
		array(
			'field'   => 'calendars',
			'label'   => 'calendars',
			'rules'   => 'trim|xss_clean'
		),
	);
	
	//run the widget
	public function run($options)
	{
		$this->load->model('modules/module_m');
		
		//check that the module is installed AND enabled
		$calendars = $this->module_m->get('calendars');
	
		//Prevent the widget from displaying if disabled or not installed		
		if($calendars === FALSE OR empty($calendars))
		{
			return FALSE;
		}
		
		// Load classes
		$this->lang->load('calendars/calendars');
		$this->load->model('calendars/calendar_m');
		$this->load->driver('Streams');
		$this->load->helper('calendars/calendars');

		// Admin?
		if ( defined('ADMIN_THEME') )
		{
			$this->asset->add_path('calendars', $calendars['path'].'/');
			$this->template->append_css('calendars::admin.css');
		}
		
		// Fix missing
		if(!isset($options['upcoming_span']))
		{
			$options['upcoming_span'] = '+1 week';
		}

		if(!isset($options['calendars']))
		{
			$options['calendars'] = array();
		}



		// Get the calendars
		$options['calendars'] = $this->streams->entries->get_entries(array(
			'stream' => 'calendars',
			'namespace' => 'calendars',
			'where' => "`created_by` = ".$this->current_user->id." OR `sharing` LIKE '%shared%'",
			)
		);



		// Determine start / end points and events
		$start = date('U', strtotime(date('m/d/Y')));
		$end = strtotime($options['upcoming_span'], $start);
		$options['events'] = array();

		// Get the events
		foreach ( $options['calendars']['entries'] as $calendar )
		{

			// Get
			$events = $this->calendar_m->get_events((object) $calendar, $start, $end+86400);

			// Add em
			if ( ! empty($events) )
			{
				$options['_events'] = $events;
				foreach ( $options['_events'] as $event ) $options['events'][] = (object) $event;
			}
		}

		// Order events by start
		usort($options['events'], "sort_helper");

		return $options;
		
	}
}
/* End of file newsletter_subscribe.php */
