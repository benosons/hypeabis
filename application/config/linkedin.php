<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------
| LinkedIn API Configuration
|--------------------------------------------------------
*/
$config['linkedin_api_key']       = '86ufcztathjnp6';
$config['linkedin_api_secret']    = 'SSgJla7ffRd0EsRo';
$config['linkedin_redirect_url']  = base_url() . 'user/linkedinLoginCallback';
$config['linkedin_redirect_url2'] = base_url() . 'user_profile/linkedinLoginCallback';
$config['linkedin_scope']         = 'r_liteprofile r_emailaddress';