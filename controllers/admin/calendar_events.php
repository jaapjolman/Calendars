<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * CMS Calendars Module
 *
 * @author 		Ryan Thompson - AI Web Systems, Inc.
 * @subpackage 	Calendars Module
 * @category 	Modules
 */
class Calendar_events extends Admin_Controller
{
	
	// Set the current section
	protected $section  = 'events';

	// Tabs for the form
	protected $_tabs = '';
	

	public function __construct()
	{
		parent::__construct();
		
		// Load all the required classes
		$this->load->model(array('calendar_event_m', 'calendar_m'));
		$this->lang->load('calendars');
		$this->load->helper('calendars');
		$this->load->driver('streams');


		// Make the tabs
		$this->_tabs = array(
			array(
				'title' => lang('calendars:tab:event'),
				'id' => 'event-tab',
				'fields' => array('str_id', 'calendar', 'title', 'location', 'notes', 'all_day', 'starting', 'ending', 'busy'),
				),
			array(
				'title' => lang('calendars:tab:content'),
				'id' => 'content-tab',
				'fields' => array('event_image', 'description'),
				),
			array(
				'title' => lang('calendars:tab:repeating'),
				'id' => 'repeating-tab',
				'fields' => array('repeating', 'repeat_span', 'repeats_every', 'repeating_ends'),
				),
			);


		// Always load these
		$this->template
			->append_css('module::admin.css')
			->append_js('module::admin.js');
	}

	/**
	 * List all existing albums
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{

		// Not ready for this..
		redirect(site_url('admin/calendars'));
		return false;

		// Set the title
		$this->template->title(lang('calendars:label:events'));

		// Set some extras
		$extra = array(
			'title'		=> lang('calendars:label:events'),
			'buttons'	=> array(
				array(
					'label' 	=> lang('global:view'),
					'url' 		=> 'admin/calendars/events/view/-entry_id-',
					),
				array(
					'label' 	=> lang('global:edit'),
					'url' 		=> 'admin/calendars/events/edit/-entry_id-',
					),
				array(
					'label'		=> lang('global:delete'),
					'url' 		=> 'admin/calendars/events/delete/-entry_id-',
					'confirm'	=> true,
					),
				),
			'filters'	=> array('title', 'location', 'description'),
			);

		// Build it aaaahhp!
		$this->streams->cp->entries_table('events', 'calendars', $this->settings->get('records_per_page'), 'admin/calendars/events/index', true, $extra);
	}

	/**
	 * Create an new event
	 *
	 * @access public
	 * @return void
	 */
	public function create($starting = false, $ending = false)
	{

		// 00:00?
		if ( date('H:i', $starting) == '00:00' ) $starting = $starting + 32400;
		if ( date('H:i', $ending) == '00:00' ) $ending = $ending + 36000;

		// No defaults?
		if ( $starting ) $starting = date('Y-m-d H:i:s', $starting); else $starting = date('Y-m-d H:i:s', strtotime('9am'));
		if ( $ending ) $ending = date('Y-m-d H:i:s', $ending); else $ending = date('Y-m-d H:i:s', strtotime('10am'));


		// Set the title
		$this->template->title(lang('calendars:title:new_event'));


		/* Start normal Streams_Core stuff
		----------------------------------------------------------------------------*/

		// Set some shit
		$extra = array(
			'return'			=> 'admin/calendars/events/view/-id-',
			'title'				=> lang('calendars:title:new_event'),
		);

		// Build it
		$this->streams->cp->entry_form('events', 'calendars', $mode = 'new', null, true, $extra, array(), $this->_tabs, $hidden = array('str_id'), $defaults = array('str_id' => rand_string(10), 'starting' => $starting, 'ending' => $ending));
	}
	
	/**
	 * Edit an event
	 *
	 * @access public
	 * @return void
	 */
	public function edit($id)
	{
		
		// Set the title
		$this->template->title(lang('calendars:title:edit_event'));

		
		/* Start normal Streams_Core stuff
		----------------------------------------------------------------------------*/

		// Set some shit
		$extra = array(
			'return'			=> 'admin/calendars/events/view/-id-',
			'title'				=> lang('calendars:title:edit_event'),
		);

		// Build it
		$this->streams->cp->entry_form('events', 'calendars', $mode = 'edit', $id, true, $extra, $skip = array(), $this->_tabs, $hidden = array('str_id'));
	}

	/**
	 * View an event
	 *
	 * @access public
	 * @return void
	 */
	public function view($id = false)
	{

		// From "cancel"
		if ( ! $id ) redirect(site_url('admin/calendars'));

		// Load it up
		$event = $this->streams->entries->get_entry($id, 'events', 'calendars', false);

		// Get the calendar
		$calendar = $this->streams->entries->get_entry($event->calendar, 'calendars', 'calendars', false);
		
		// Set the title
		$this->template->title(lang('calendars:title:view_event'));

		// Build it
		$this->template->build('admin/events/view', array('event' => $event, 'calendar' => $calendar));
	}
	
	/**
	 * Delete a event
	 *
	 * @access public
	 * @return void
	 */
	public function delete($id)
	{
		// Update as complete
		$this->db->where('id', $id)->delete('calendars_events');

		redirect(site_url('admin/calendars'));
	}
}