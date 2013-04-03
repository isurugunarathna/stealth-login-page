<?php
/*
  Plugin Name: Stealth Login Page
  Plugin URI: http://www.petersenmediagroup.com/plugins/stealth-login-page
  Version: 1.0.0
  Author: Jesse Petersen
  Author URI: http://www.petersenmediagroup.com
  Description: Protect your /wp-admin and wp-login.php pages from being accessed without editing .htaccess
  Text Domain: stealth-login-page
  Domain Path: /languages/
 */
/*
  Copyright 2013 Jesse Petersen

  Thanks to Andrew Norcross (@norcross) for the redirect code (https://gist.github.com/norcross/4342231) and Billy Fairbank (@billyfairbank) for the idea to turn it into a plugin. Last minute thanks for 'mindctrl' (https://github.com/mindctrl) hopping on GitHub and adding more advanced features and correcting my mistakes.

  Licenced under the GNU GPL:

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( 'Sorry, you are not allowed to access this page directly.', 'stealth-login-page' ) );
}

add_action( 'init', 'slp_load_plugin_translations', 1 );
/**
 * Load translations for this plugin
 */
function slp_load_plugin_translations() {
  
  load_plugin_textdomain( 'stealth-login-page', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

// Global Variables ---------------------- //
$slp_prefix = 'slp_';
$slp_plugin_name = 'Stealth Login Page';
// retrieve plugin settings from options table
$slp_options  = get_option('slp_settings');

// Includes ------------------------------ //
include('includes/settings-page.php'); // loads the admin settings page
if ( $slp_options['enable'] )
  include('includes/display-functions.php'); // loads the output functions