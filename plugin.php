<?php
/**
 * Plugin Name: Fabs Player Plugin
 * Plugin URI: http://www.owlwatch.com
 * Description: A music player based on jplayer
 * Author: Mark Fabrizio
 * Version: 0.4.9
 * Author URI: http://www.owlwatch.com
 */

require_once( dirname(__FILE__).'/functions.php');
require_once( dirname(__FILE__).'/template.php');
require_once( dirname(__FILE__).'/widgets.php');

if( !is_admin()){
    wp_enqueue_style('fabs-player', plugins_url('/style.css', __FILE__));
}