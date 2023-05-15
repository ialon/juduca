<?php
/* 
Plugin Name: Bookingo - Course Booking System for WordPress
Plugin URI: https://1.envato.market/bookingo-course-booking-system-for-wp
Description: Bookingo - Course Booking System is a powerful WordPress booking plugin for a small service industry, schools and trainers. The plugin will be perfect as a booking for a swimming school, language or driving school, wherever we deal with a repeating pattern of classes.
Author: QuanticaLabs
Version: 1.6
Author URI: https://1.envato.market/bookingo-course-booking-system-for-wp
*/
	
load_plugin_textdomain('bookingo',false,dirname(plugin_basename(__FILE__)).'/languages/');

require_once('include.php');

$Plugin=new BGCBSPlugin();
$WooCommerce=new BGCBSWooCommerce();

register_activation_hook(__FILE__,array($Plugin,'pluginActivation'));

add_action('init',array($Plugin,'init'));
add_action('after_setup_theme',array($Plugin,'afterSetupTheme'));
add_filter('woocommerce_locate_template',array($WooCommerce,'locateTemplate'),1,3);