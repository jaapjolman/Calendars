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
class Calendar_event_m extends MY_Model {
	
	public function __construct()
	{		
		parent::__construct();

		/**
		 * If the sample module's table was named "samples"
		 * then MY_Model would find it automatically. Since
		 * I named it "sample" then we just set the name here.
		 */
		
		// Set the table
		$this->_table = 'events';
	}
}