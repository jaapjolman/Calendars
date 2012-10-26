<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * CMS Calendars Module
 *
 * @author 		Ryan Thompson - AI Web Systems, Inc.
 * @subpackage 	Calendars Module
 * @category 	Modules
 */
class Calendar_events extends Public_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		// Load all the required classes
		$this->load->model(array('calendar_event_m', 'calendar_m'));
		$this->lang->load('calendars');
		$this->load->helper('calendars');
		$this->load->driver('streams');
	}
	
	//--------------------------------------------------------------------------------	AJAX
	
	/**
	 * Return AJAX source for events
	 *
	 * @access 	public
	 * @param 	$id of a calendar to get events for
	 * @return 	void
	 */
	public function ajax_event_source($id)
	{

		// Get the calendar to load source
		$calendar = $this->streams->entries->get_entry($id, 'calendars', 'calendars');
		
		// Get params
		$start 	= $this->input->get('start');
		$end 	= $this->input->get('end');
				
		// Get em all
		$json = $this->calendar_m->get_events($calendar, $start, $end);
		
		if(!empty($json)) echo json_encode($json); else echo '[{}]';

	}// eof ajax_event_source

	/**
	 * Adjust an event by AJAX
	 *
	 * @access 	public
	 * @param 	$id of a calendar to get events for
	 * @return 	void
	 */
	public function ajax_adjust($id, $time_starting, $time_ending)
	{
		// Make JS time a unix one
		$time_starting 	= $time_starting / 1000;
		$time_ending 		= $time_ending / 1000;
		
		// Get the times - Do this so it preserves PHPs timezone
		$time_starting = date('Y-m-d H:i:s', $time_starting);
		$time_ending = date('Y-m-d H:i:s', $time_ending);
		
		// Save em
		$this->db->where('id', $id)->update('calendars_events', array('starting'=>$time_starting, 'ending'=>$time_ending));
	}
}

