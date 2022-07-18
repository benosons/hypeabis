<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Google API Configuration
| -------------------------------------------------------------------
| 
| To get API details you have to create a Google Project
| at Google API Console (https://console.developers.google.com)
| 
|  client_id         string   Your Google API Client ID.
|  client_secret     string   Your Google API Client secret.
|  redirect_uri      string   URL to redirect back to after login.
|  application_name  string   Your Google application name.
|  api_key           string   Developer key.
|  scopes            string   Specify scopes
*/

//$config['google_client_id']         = "1080915340065-pcfm4th5h2lloq74mqa7pk1hrf2ak2jt.apps.googleusercontent.com";
//$config['google_client_secret']     = "52L5fID2SA84_QQvAUicLy_T";
$config['google_client_id']         = "405073400236-7m7jja8r7ijg72upsr4h2ps0575hm7o7.apps.googleusercontent.com";
$config['google_client_secret']     = "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";
$config['google_redirect_url']      = base_url() . 'user/googleLoginCallback';
$config['google_redirect_url2']     = base_url() . 'user_profile/googleLoginCallback';

//google shorten URL API..
$config['google_api_url']   = 'https://www.googleapis.com/urlshortener/v1/url';
$config['google_api_key']   = 'AIzaSyAGpnTe706KoaaTqJO1n0AoYrRlovpVLPI';
