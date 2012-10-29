<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Calendars Plugin
 *
 * Display calendar and event information.
 * 
 * @author		Ryan Thompson - AI Web Systems, Inc.
 * @package		PyroCMS\Shared\Modules\Calendars\Plugins
 */
class Plugin_Calendars extends Plugin {

	public function __construct()
	{
		$this->lang->load('calendars/calendars');
	}

	/**
	 * Events
	 *
	 * Return an array of events per the attributes
	 *
	 * Usage:
	 *
	 * {{ calendars:events 	calendar 			= "my-calendar"
	 * 						starting 			= "1351311807"
	 * 						end		 			= "1351315407"
	 * 						year 				= "2013"
	 * 						month	 			= "7"
	 * 						day 				= "19"
	 * 						category	 		= "My Caategory|My Other Category"
	 * 	}}
	 * 		{{ title }}
	 * 	{{ /calendars:events }}
	 *
	 * 	If form validation doesn't pass the error messages will be displayed next to the corresponding form element.
	 */
	public function events()
	{

		// Load classes
		$this->load->driver('streams');
		$this->load->model('calendars/calendar_m');
		

		// Get attributes
		$attributes = $this->attributes();

		$params = array(
			'stream' => 'events',
			'namespace' => 'calendars',
			'limit' => $this->attribute('limit', 10),
			'date_by' => $this->attribute('date_by', 'starting'),
			'order_by' => $this->attribute('order_by', 'starting'),
			'sort' => $this->attribute('sort', 'desc'),
			);
		
		
		/*
		 * Limit to a specific caledar by slug
		--------------------------------------------*/
		if ( isset($attributes['calendar']) )
		{
			
			// Load the calendar
			$calendar = $this->db->select()->where('slug', $attributes['calendar'])->limit(1)->get('calendars_calendars')->row(0);

			$params['where'] = '`calendar` = ' . $calendar->id;
		}


		/*
		 * Limit to a specific year, month or day
		--------------------------------------------*/
		if ( isset($attributes['year']) ) $params['year'] = $attributes['year'];
		if ( isset($attributes['month']) ) $params['month'] = $attributes['month'];
		if ( isset($attributes['day']) ) $params['day'] = $attributes['day'];


		$events = $this->streams->entries->get_entries($params, $pagination = array());

		return $events['entries'];
	}
}