<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 

// Delete Database  
$devllo_events= get_posts(['post_type'=>'devllo_event','numberposts'=>-1]);// all posts
foreach($devllo_events as $event){
    wp_delete_post($event->ID,true);
}


// delete  options
$options = array(
	'devllo-map-api-key',
);
foreach ($options as $option) {
	if (get_option($option)) delete_option($option);
}