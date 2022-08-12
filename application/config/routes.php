<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login';

$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['forgot_password/change_pass/changePassProcess'] = 'forgot_password/changePassProcess';

$route['forgot_password/requestPasswordReset'] = 'forgot_password/requestPasswordReset';
$route['forgot_password/change_pass/(:any)/(:any)'] = 'forgot_password/change_pass/$1/$2';

$route['profile'] = 'Settings';
$route['support'] = 'Help/about_us';
$route['profile/membership'] = 'Settings/membership_account';
$route['profile/notifications'] = 'Settings/notification_settings';
$route['profile/password'] = 'Settings/security-settings';
$route['about-us'] = 'Help/about_us';
$route['contact-us'] = 'Help/contact_us';
$route['how-it-works'] = 'Help/how_it_works';
$route['faq'] = 'Help/faq';
$route['documentation'] = 'Help/documentation';