<?php
  defined('START') or die;

 //routing engine
 $request_uri = '';
 
 //split the URI on slashes
 $request_uri = $_SERVER['REQUEST_URI'];
 
 //Explode the string based on question mark
 $if_q_mark_exploded = explode('?', $_SERVER['REQUEST_URI']);
 
 //the required part, i.e., the previous part after exploding the string which gets content before question mark
 $q_mark_before_content = $if_q_mark_exploded[0];
 
 //Explode based on slash
 $seo_url_params = explode('/', $q_mark_before_content);
 
 //Count the number of url params
 $r_e_var_count = count($seo_url_params);
 
 //Define a URL Param Prefix with 20 iterations to pre-define 20 Routine Engine Variables
 $routing_eng_var_ = "routing_eng_var_";    
 extract($seo_url_params, EXTR_PREFIX_ALL, 'routing_eng_var');
 foreach($seo_url_params as $k => $v)
 {
	$routing_eng_var_.$k = (null !== $$routing_eng_var_.$k) ? trim(filter_var($$routing_eng_var_.$k, FILTER_SANITIZE_STRING)) : '';
 }
 
 //Check if there is questionmark in the $_SERVER['REQUEST_URI']
 $q_mark_pos = strpos($_SERVER['REQUEST_URI'], "?");
 if ($q_mark_pos === false) {
	//If questionmark doesn't exist, then the Complete Route value is
    $route_check_input = $q_mark_before_content;
 } else {
	//If questionmark does exist, then the Complete Route value is  
    $route_check_input = $_SERVER['REQUEST_URI'];
 }
 //useful urls
  /*
 http://www.alistapart.com/articles/succeed/
 http://stackoverflow.com/questions/266719/url-routing-handling-spaces-and-illegal-characters-when-creating-friendly-urls
 http://www.roscripts.com/Pretty%5FURLs%5F-%5Fa%5Fguide%5Fto%5FURL%5Frewriting-168.html
 http://blog.defaultroute.com/2010/11/25/custom-page-routing-in-wordpress/
 http://codeigniter.com/forums/viewthread/186025/
 http://stackoverflow.com/questions/3410134/how-to-create-dynamic-friendly-urls-using-php
 http://kunststube.net/isset/
     */
?>