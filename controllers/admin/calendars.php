<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * Calendars Module - Manage your calendars and events.
 *
 * @author 		Ryan Thompson - AI Web Systems, Inc.
 * @package 	Ryan Thompson - AI Web Systems, Inc.
 * @subpackage 	Calendars Module
 * @category 	Modules
 * @license 	DBAD
 */
class Calendars extends Admin_Controller
{
	
	// Set the section	
	protected $section = 'calendars';

	// Tabs for the form
	protected $_tabs = '';

	
	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('calendar_m');
		$this->lang->load('calendars');
		$this->load->helper('calendars');
		$this->load->driver('streams');


		// Make the tabs
		$this->_tabs = array(
			array(
				'title' => lang('calendars:tab:calendar'),
				'id' => 'calendar-tab',
				'fields' => array('title', 'slug', 'notes', 'bg_color', 'text_color'),
				),
			array(
				'title' => lang('calendars:tab:content'),
				'id' => 'content-tab',
				'fields' => array('calendar_image', 'description'),
				),
			array(
				'title' => lang('calendars:tab:sharing'),
				'id' => 'sharing-tab',
				'fields' => array('privacy', 'shared'),
				),
			array(
				'title' => lang('calendars:tab:other'),
				'id' => 'other-tab',
				'fields' => array('gc_id', 'feed_url'),
				),
			);


		// Always load these
		$this->template
			->append_css('module::admin.css')
			->append_js('module::admin.js');
	}

	/**
	 * Show the calendar
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
		// Only creators can see if not shared and what not
		$data['calendars'] = $this->streams->entries->get_entries(array(
			'stream' => 'calendars',
			'namespace' => 'calendars',
			'where' => "`created_by` = ".$this->current_user->id." OR `shared` = 1",
			)
		);
		
		// Load the view
		$this->template
			->append_js('module::engine.min.js')
			->append_js('module::gcal.min.js')
			->build('admin/calendars/index', $data);
	}

	/**
	 * Create a new calendar
	 *
	 * @access public
	 * @return void
	 */
	public function create()
	{
		// Set the title
		$this->template->title(lang('calendars:button:new_calendar'));

		// If this is a post, set the str_id
		if ($_POST)
		{

			// Set the str_id
			$_POST['str_id'] = rand_string(10);

			// Set default colors
			if ( empty($_POST['bg_color']) ) $_POST['bg_color'] = '3366cc';
			if ( empty($_POST['text_color']) ) $_POST['text_color'] = 'ffffff';
			if ( ! isset($_POST['sharing']) ) $_POST['sharing'] = NULL;
		}

		/* Start normal Streams_Core stuff
		----------------------------------------------------------------------------*/

		// Set some shit
		$extra = array(
			'return'			=> 'admin/calendars',
			'success_message'	=> lang('calendars:success:new_calendar'),
			'failure_message'	=> lang('calendars:error:new_calendar'),
			'title'				=> lang('calendars:button:new_calendar'),
		);

		// We will set these ourselves
		$skip = array('str_id');

		// Build it
		$this->streams->cp->entry_form('calendars', 'calendars', $mode = 'new', null, true, $extra, $skip, $this->_tabs);
	}
	
	/**
	 * Edit a calendar
	 *
	 * @access public
	 * @return void
	 */
	public function edit($id)
	{
		// Set the title
		$this->template->title(lang('calendars:edit_calendar'));
		

		/* Start normal Streams_Core stuff
		----------------------------------------------------------------------------*/

		// Set some shit
		$extra = array(
			'return'			=> 'admin/calendars',
			'success_message'	=> lang('calendars:success:edit_calendar'),
			'failure_message'	=> lang('calendars:error:edit_calendar'),
			'title'				=> lang('calendars:edit_calendar'),
		);

		// We will set these ourselves
		$skip = array('str_id');

		// Build it
		$this->streams->cp->entry_form('calendars', 'calendars', $mode = 'edit', $id, true, $extra, $skip, $this->_tabs);
	}
	
	/**
	 * View a calendar
	 *
	 * @access public
	 * @return void
	 */
	public function view($id)
	{
		$calendar = $this->calendar_m->get($id);
		
		$this->template
			->append_css('module::admin.css')
			->append_js('module::admin.js')
			->set('calendar',	$calendar)
			->build('admin/calendars/view');
	}
	
	/**
	 * Delete a calendar
	 *
	 * @access public
	 * @return void
	 */
	public function delete($id = FALSE)
	{
		// Update as complete
		if($id !== FALSE AND $this->db->where('id', $id)->delete('calendars_calendars') AND $this->db->where('calendar_id', $id)->delete('calendars_events'))
		{						
			// Everything went ok..
			$this->session->set_flashdata('success', lang('calendars:success:delete_calendar'));
	
			// Redirect back to the main page
			redirect('admin/calendars');
		}
		else
		{
			// Everything went osucks..
			$this->session->set_flashdata('success', lang('calendars:error:delete_calendar'));
	
			// Redirect back to the main page
			redirect('admin/calendars');
		}
	}
	
	
	//--------------------------------------------------------------------------------	VALIDATION CALLBACKS
	
	/**
	 * Check if date is valid if enabled
	 *
	 * @access 	public
	 * @param 	$calendar_due
	 * @return 	void
	 */
	public function validate_calendar_due($calendar_due)
	{
		// Get the array of inputs
		$calendar_due = $this->input->post('calendar_due');
		
		// Is it even enabled?
		if($calendar_due[0] == 1)
		{
			// It is, make sure there are no errors.
			$date = explode('-', $calendar_due[1]);
			
			// Check the year
			if(!isset($date[0]) OR $date[0] < 2002) return FALSE;
			
			// Check the month
			if(!isset($date[1]) OR $date[1] < 1 OR $date[1] > 12) return FALSE;
			
			// Check the day
			if(!isset($date[2]) OR $date[2] < 1 OR $date[2] > 32) return FALSE;
			
			return TRUE;
		}
		else
		{
			return TRUE;
		}
	}
	
	//--------------------------------------------------------------------------------	AJAX
	
	/**
	 * Return AJAX details for a calendar
	 *
	 * @access 	public
	 * @param 	$id of calendar
	 * @return 	void
	 */
	public function ajax_details($id)
	{
		$calendar = $this->calendar_m->get($id);
		
		$this->load->view('ajax/calendar_details', array('calendar' => $calendar));
	}
}

