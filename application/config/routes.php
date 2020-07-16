<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
 */
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

$route['backoffice'] = ADMIN_PATH . '/login';
$route['backoffice/'] = ADMIN_PATH . '/login';
$route['backoffice/logout'] = ADMIN_PATH . '/login/logout';

$route['backmin'] = BACKMIN_PATH . '/dashboard';
$route['backmin/'] = BACKMIN_PATH . '/dashboard';
$route['backmin/logout'] = BACKMIN_PATH . '/dashboard/logout';

$route['katalog'] = '/kategori/katalog/3';
$route['katalog/3/(:num)'] = '/kategori/katalog/$1/$2';
$route['halaman/hubungi-kami'] = '/halaman/hubungi_kami';
$route['halaman/syarat-ketentuan'] = '/halaman/syaratketentuan';
$route['halaman/tata-cara-pembayaran-bri-virtual-account'] = '/halaman/tatacarapembayaran';
$route['testimoni'] = '/testimoni/index';
$route['testimoni/(:num)'] = '/testimoni/index/$1';
$route['generate/report'] = '/home/generateReportPublic';
$route['api/detail_paket'] = '/api/paket/detail_paket';
$route['processEmailSpool'] = '/home/processMail';
$route['mitra/registrasi'] = '/mitraregistrasi/index';
$route['botdetect/captcha_handler/index'] = 'botdetect/captcha_handler/index';
$route['botdetect/captcha_resource/get/(:any)'] = 'botdetect/captcha_resource/get/$1';
