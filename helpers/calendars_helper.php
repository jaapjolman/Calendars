<?php defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	 * Calendars helper
	 *
	 * @package			CMS
	 * @subpackage	Calendars Module
	 * @category		Helper
	 * @author			Ryan Thompson - AI Web Systems, Inc.
	 */

	/**
	 * Make slug
	 */
	if(!function_exists('slugify'))
	{
		function slugify($string)
		{	
			$string = trim($string);
			$string = strtolower($string);
			$string = preg_replace('/[\s-]+/', '-', $string);
			$string = preg_replace("/[^0-9a-zA-Z-]/", '', $string);
			
			return $string;
		}
	}
	
	/**
	 * Create a random hash string based on microtime
	 *
	 * @author 	AI Web Systems, Inc.
	 * @access 	public
	 * @param 	int $length
	 * @return 	string
	*/
	if(!function_exists('rand_string'))
	{
		function rand_string($length = 10)
		{
			$chars = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz';
			$max = strlen($chars)-1;
			$string = '';
			mt_srand((double)microtime() * 1000000);
			while (strlen($string) < $length)
			{
				$string .= $chars{mt_rand(0, $max)};
			}
			return $string;
		}
	}

	/**
	 * Sort assoc array USORT callback
	 *
	 * @author 	AI Web Systems, Inc.
	 * @access 	public
	 * @param 	mixed $a
	 * @param 	mixed $b
	 * @return 	mixed
	*/
	if(!function_exists('sort_helper'))
	{
		function sort_helper($a, $b)
		{
			$a = (array) $a;
			$b = (array) $b;

			return $a['start'] - $b['start'];
		}
	}

	