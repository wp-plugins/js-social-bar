<?php


//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

delete_option("js_social_bar_options");

// For site options in multisite  
delete_site_option("js_social_bar_options");
?>