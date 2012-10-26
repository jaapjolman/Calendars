<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * The galleries module enables users to create albums, upload photos and manage their existing albums.
 *
 * @author 			Ryan Thompson - AI Web Systems, Inc.
 * @package 		CMS
 * @subpackage 	Gallery Module
 * @category 		Modules
 * @license 		Apache License v2.0
 */
class Calendar_m extends MY_Model {

	//--------------------------------------------------------------------------------
	
	/**
	 * Return events for a calendar in a given time period
	 *
	 * @access 	public
	 * @param 	$id of a calendar to get events for
	 * @return 	void
	 */
	public function get_events($calendar, $start, $end)
	{

		// Simplify the ID
		$id = $calendar->id;

		$events = array();


		/*	Non-Repeating Events
		/*-------------------------------------*/
		
		// Start the where statement
		$where = array(
			"`calendar_id` = {$id}",
			"(`repeating` = '' OR `repeating` IS NULL)",
			"`starting` <= '".date('Y-m-d H:i:s', $end)."'",
			"`starting` >= '".date('Y-m-d H:i:s', $start)."'",
			);


		// Get em
		$data['events'] = $this->streams->entries->get_entries(
			array(
				'stream' => 'events',
				'namespace' => 'calendars',
				'date_by' => 'starting',
				'where' => $where,
				)
			);

		
		// Prep events for calendar engine
		foreach($data['events']['entries'] as $k=>&$event)
		{

			// Build the entry for JSON in case
			$entry['id'] = $event['id'];
			$entry['allDay'] = (bool) $event['all_day'];
			$entry['title'] = $event['title'];
			$entry['description'] = $event['description'];
			$entry['location'] = $event['location'];

			$entry['start'] = $event['starting'];
			$entry['end'] = $event['ending'];

			$entry['backgroundColor'] = $calendar->bg_color;
			$entry['borderColor'] = $calendar->bg_color;
			$entry['textColor'] = $calendar->text_color;

			$entry['calendar_id'] = $calendar->str_id;
			
			$events[] = $entry;
		}
		
		
		
		/*	Repeating Events
		/*-------------------------------------*/
		
		// Start the where statement
		$where = array(
			"`calendar_id` = {$id}",
			"`repeating` = 1",
			//"`starting` <= '".date('Y-m-d H:i:s', $end)."'",
			//"`starting` >= '".date('Y-m-d H:i:s', $start)."'",
			);


		// Get em
		$data['events'] = $this->streams->entries->get_entries(
			array(
				'stream' => 'events',
				'namespace' => 'calendars',
				'date_by' => 'starting',
				'where' => $where,
				)
			);
		
		
		// Prep events for calendar engine
		foreach($data['events']['entries'] as $k=>&$event)
		{
			// Repeating events are not drag / drop yet
			$entry['editable'] = false;
			
			// Set the series' ID
			$entry['series_id'] = $event['id'];
			
			// Build the entry
			$entry['id'] = $event['id'];
			$entry['allDay'] = (bool) $event['all_day'];
			$entry['title'] = $event['title'];
			$entry['description'] = $event['description'];
			$entry['location'] = $event['location'];

			$entry['start'] = $event['starting'];
			$entry['end'] = $event['ending'];

			$entry['backgroundColor'] = $calendar->bg_color;
			$entry['borderColor'] = $calendar->bg_color;
			$entry['textColor'] = $calendar->text_color;

			$entry['calendar_id'] = $calendar->str_id;
						
			/*	While we are on this event.. let's recurse through it's repetition and add them to the JSON
			/*--------------------------------------------------------------------------------------------------*/
						
			
			// Is there a non-valid answer here? AKA It goes on forever? This equals Dec 1st, 1969 or something
			if( $event['repeating_ends'] == '943941600' )
			{
				
				/*	Dialy - Never Ending
				/*--------------------------------------------------------------------------------------------------*/												
				while(TRUE)
				{
					$entry['series_id'] = $event['id'].':'.$event['starting'];
					
					// Test if the time is valid
					if($event['starting'] > $end) break;

					// Make the time for the calendar
					$entry['start'] = $event['starting'];
					$entry['end'] 	= $event['ending'];
					
					// Add it to JSON
					if ( $event['starting'] >= $start ) $events[] = $entry;
					
					// Increment
					switch($event['repeats']['value'])
					{
						
						case 'Daily':
							$event['starting'] = $event['starting'] + $event['repeats_every']['value'] * 86400;
							$event['ending'] = $event['ending'] + $event['repeats_every']['value'] * 86400;
							break;

						case 'Weekly':
							$event['starting'] = strtotime('+'.$event['repeats_every']['value'].' Week', $event['starting']);
							$event['ending'] = strtotime('+'.$event['repeats_every']['value'].' Week', $event['ending']);
							break;

						case 'Monthly':
							$event['starting'] = strtotime('+'.$event['repeats_every']['value'].' Month', $event['starting']);
							$event['ending'] = strtotime('+'.$event['repeats_every']['value'].' Month', $event['ending']);
							break;

						case 'Yearly':
							$event['starting'] = strtotime('+'.$event['repeats_every']['value'].' Year', $event['starting']);
							$event['ending'] = strtotime('+'.$event['repeats_every']['value'].' Year', $event['ending']);
							break;
					}
				}
			
			}
			else
			{
				
				/*	Dialy - Ending On...
				/*--------------------------------------------------------------------------------------------------*/									
				while(TRUE)
				{
					$entry['series_id'] = $event['id'].':'.$event['starting'];
					
					// Test if the time is valid
					if($event['starting'] > $event['repeating_ends']) break;

					// Make the time for the calendar
					$entry['start'] = date('D, d M Y H:i:s', $event['starting']);
					$entry['end'] 	= date('D, d M Y H:i:s', $event['ending']);
					
					// Add it to JSON is START is within range
					if ( $event['starting'] >= $start ) $events[] = $entry;
					
					// Increment
					switch($event['repeats']['value'])
					{
						
						case 'Daily':
							$event['starting'] = $event['starting'] + $event['repeats_every']['value'] * 86400;
							$event['ending'] = $event['ending'] + $event['repeats_every']['value'] * 86400;
							break;

						case 'Weekly':
							$event['starting'] = strtotime('+'.$event['repeats_every']['value'].' Week', $event['starting']);
							$event['ending'] = strtotime('+'.$event['repeats_every']['value'].' Week', $event['ending']);
							break;

						case 'Monthly':
							$event['starting'] = strtotime('+'.$event['repeats_every']['value'].' Month', $event['starting']);
							$event['ending'] = strtotime('+'.$event['repeats_every']['value'].' Month', $event['ending']);
							break;

						case 'Yearly':
							$event['starting'] = strtotime('+'.$event['repeats_every']['value'].' Year', $event['starting']);
							$event['ending'] = strtotime('+'.$event['repeats_every']['value'].' Year', $event['ending']);
							break;
					}
				}
				
			}
		}

		return $events;
	}
}