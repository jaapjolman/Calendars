<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
*/

// Maintain admin routes
$route['calendars/admin/events(:any)?'] 							= 'admin/calendar_events$1';
$route['calendars/admin(:any)?'] 									= 'admin/calendars$1';

// Public Routes
$route['calendars/events(:any)?'] 									= 'calendar_events$1';

?>