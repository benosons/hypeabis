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
$route['default_controller'] = 'page';
$route['404_override'] = 'page_404';
$route['translate_uri_dashes'] = FALSE;

$route['page/search-articles'] = 'page/contents';
$route['page/search-articles/(:any)'] = 'page/contents/$1';
$route['search'] = 'page/search';
$route['search/(:any)'] = 'page/search/$1';
$route['page/(articles|popular|polls|quizzes|hypephoto|hypeshop)'] = 'page/contents';
$route['page/(articles|popular|polls|quizzes|hypephoto|hypeshop)/(:any)'] = 'page/contents/$1';

$route['hypeshop'] = 'content2/index/$1';
$route['hypephoto'] = 'content2/index/$1';
// $route['hypevirtual'] = 'hypevirtual/index/$1';
// $route['hypevirtual/(:any)/(:any)'] = 'hypevirtual/show/$1/$2';
// $route['hypevirtual/(:any)/(:any)/(:any)'] = 'hypevirtual/show/$1/$2/$3';

//route artikel
$route['read/(:any)/(:any)'] = 'content/article/$1/$2';
$route['read/(:any)/(:any)/(:any)'] = 'content/article/$1/$2/$3';
$route['(read-sponsored|poll|quiz|hypephoto|hypeshop)/(:any)/(:any)'] = 'content2/article/$2/$3';
$route['(read-sponsored|poll|quiz|hypephoto|hypeshop)/(:any)/(:any)/(:any)'] = 'content2/article/$2/$3/$4';
$route['kompetisi/(:any)/(:any)/syarat-dan-ketentuan'] = 'competition/temsAndConditions/$1/$2';
$route['kompetisi/(:any)/(:any)'] = 'competition/show/$1/$2';
$route['kompetisi/(:any)/(:any)/(:any)'] = 'competition/show/$1/$2/$3';
$route['kompetisi/(:any)/(:any)/(:any)/(:any)'] = 'competition/show/$1/$2/$3/$4';
$route['hypevent'] = 'hypevent/show/';
$route['hypevent/(:any)'] = 'hypevent/show/$1';
$route['hypevirtual'] = 'hypevirtual/index/';
$route['hypevirtual/(:any)/(:any)/(:any)'] = 'hypevirtual/show/$1/$2/$3';

//route kategori
$route['category/(:any)/(:any)'] = 'content/category/$1/$2';
$route['category/(:any)/(:any)/(:any)'] = 'content/category/$1/$2/$3';
// $route['category/(:any)/(:any)/(:any)'] = 'content/category/hypephoto/$1/$2';

//route author
$route['author/(:any)/(:any)/(follow|unfollow)'] = 'authors/toggleFollow/$1/$2';
$route['author/(:any)/(:any)'] = 'authors/index/$1/$2';
$route['author/(:any)/(:any)/(:any)'] = 'authors/index/$1/$2/$3';
$route['author/(:any)/(:any)/(:any)/(:any)'] = 'authors/index/$1/$2/$3/$4';

//admin
$route['hypeadmin/(:any)'] = 'adminarea/index/$1';

//kontak
$route['contact/(us|lapor-gangguan|kolaborasi-bisnis)'] = 'contact/index/$1';

//route page
$route['page/tentang-kami']        = 'page/content/1/tentang-kami';
$route['page/info-iklan']          = 'page/content/2/info-iklan';
$route['page/kebijakan-privasi']   = 'page/content/3/kebijakan-privasi';
$route['page/terms-condition']     = 'page/content/4/terms-condition';
$route['page/panduan-media-siber'] = 'page/content/5/panduan-media-siber';
$route['page/panduan-foto']        = 'page/content/6/panduan-foto';
$route['page/panduan-komunitas']   = 'page/content/7/panduan-komunitas';

//sitemap & rss
$route['sitemap\.xml']  = 'page/sitemap';
$route['sitemap-(:any)\.xml']  = 'page/sitemapIndex';
$route['rss\.xml']  = 'page/rss';
$route['rss-(:any)\.xml']  = 'page/rssByDate';

$route['admin_polling'] = 'polling';
$route['user_polling'] = 'polling';
$route['admin_polling/(:any)'] = 'polling/$1';
$route['user_polling/(:any)'] = 'polling/$1';
$route['admin_polling/(:any)/(:any)'] = 'polling/$1/$2';
$route['user_polling/(:any)/(:any)'] = 'polling/$1/$2';

$route['admin_quiz'] = 'quiz';
$route['user_quiz'] = 'quiz';
$route['admin_quiz/(:any)'] = 'quiz/$1';
$route['user_quiz/(:any)'] = 'quiz/$1';
$route['admin_quiz/(:any)/(:any)'] = 'quiz/$1/$2';
$route['user_quiz/(:any)/(:any)'] = 'quiz/$1/$2';

$route['admin_shoppable'] = 'shoppable';
$route['user_shoppable'] = 'shoppable';
$route['admin_shoppable/(:any)'] = 'shoppable/$1';
$route['user_shoppable/(:any)'] = 'shoppable/$1';
$route['admin_shoppable/(:any)/(:any)'] = 'shoppable/$1/$2';
$route['user_shoppable/(:any)/(:any)'] = 'shoppable/$1/$2';

$route['(admin|user)_photo'] = 'photo';
$route['admin_photo/(:any)'] = 'photo/$1';
$route['user_photo/(:any)'] = 'photo/$1';
$route['admin_photo/(:any)/(:any)'] = 'photo/$1/$2';
$route['user_photo/(:any)/(:any)'] = 'photo/$1/$2';
