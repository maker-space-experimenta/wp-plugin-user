<?php

/*
Plugin Name: Makerspace User Change Password
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Jonathan.Guenz
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


// the main plugin class
require_once dirname( __FILE__ ) . '/src/main.php';

Change_Password_Main::instance();

register_activation_hook( __FILE__, array('Change_Password_Main', 'activate' ) );
register_deactivation_hook( __FILE__, array('Change_Password_Main', 'deactivate' ) );
