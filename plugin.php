<?php
/*
  Plugin Name: Stealth Login Page
  Plugin URI: http://www.petersenmediagroup.com/plugins/stealth-login-page
  Version: 0.1.0
  Author: Jesse Petersen
  Author URI: http://www.petersenmediagroup.com
  Description: Protect your /wp-admin and wp-login.php pages from being accessed without editing .htaccess
 */
/*
  Copyright 2013 Jesse Petersen

  Thanks to Andrew Norcross (@norcross) for the redirect code and Billy Fairbank (@billyfairbank) for the idea to turn it into a plugin.

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
		wp_die( __( "Sorry, you are not allowed to access this page directly.", 'slp' ) );
}

define( 'SLP_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'SLP_SETTINGS_FIELD', 'slp-settings' );

register_activation_hook( __FILE__, 'slp_activation_check' );

add_action( 'init', 'slp_init', 15 );

/** Loads required files when needed */
function slp_init() {

	/** Load textdomain for translation */
	load_plugin_textdomain( 'slp', false, basename( dirname( __FILE__ ) ) . '/languages/' );

}

add_action('admin_menu', 'slp_plugin_menu');
function slp_plugin_menu() {
	global $wp_version;

	// Modern WP?
	if (version_compare($wp_version, '3.0', '>=')) {
	add_options_page('Stealth Login Page', 'Stealth Login Page', 'manage_options', 'stealth-login-page', 'slp_admin');
	    return;
	}

	// Older WPMU?
	if (function_exists("get_current_site")) {
	    add_submenu_page('wpmu-admin.php', 'Stealth Login Page', 'Stealth Login Page', 9, 'stealth-login-page', 'slp_admin');
	    return;
	}

	// Older WP
	add_options_page('Stealth Login Page', 'Stealth Login Page', 9, 'stealth-login-page', 'slp_admin');
}

    $data = array(
	'redirect_url'		=> '',
	'question'			=> '',
	'answer'			=> '',
	);

	$slp_settings = get_option('slp_settings');

function slp_admin() {
	// Check that the user is allowed to update options
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
	echo '<div class="wrap">
	<h2>Stealth Login Page Options</h2>
	<form method="post" action="options.php">
	<input type="hidden" name="redirect" value="true" />
		<table class="form-table">
			<tr valign="top">
				<p>Those attempting to gain access to your login form will be automatcally redirected to a customizble URL. Enter that URL below.</p>
				<th scope="row">
					<label for="redirect_url">
						URL to redirect unauthorized attempts to:
					</label>
				</th>
				<td>
	<input type="text" name="redirect_url" value="' . htmlentities($slp_settings['redirect_url']) . '" size="60" />
				</td>
			</tr>
			<tr valign="top">
				<p>The first part of the new URL string to reach your login<br />
				form is the "question." It is just an arbitrary word<br />
				or code. Complexity will not matter much at this time.</p>
				<th scope="row">
					<label for="question">
						String used for the "question" (limit: 30 characters):
					</label>
				</th>
				<td>
	<input type="text" name="question" value="' . htmlentities($slp_settings['question']) . '" size="30" />
				</td>
			</tr>
			<tr valign="top">
				<p>The second part of the new URL string to reach your login<br />
				form is the "answer." It is also just an arbitrary word<br />
				or code.</p>
				<th scope="row">
					<label for="answer">
						String used for the "answer" (limit: 30 characters):
					</label>
				</th>
				<td>
	<input type="text" name="answer" value="' . htmlentities($slp_settings['answer']) . '" size="30" />
				</td>
			</tr>
		</table>
	</form>
	</div>';
}

/*
* Check the URL of the WordPress login page for a specific query string.
*
* assumes login string is
* http://yoursite/wp-login.php?question=answer
*/
add_action( 'login_init', 'slp_login_stringcheck' );
function slp_login_stringcheck() {
 
	// set the location a failed attempt goes to
	$redirect = $slp_settings('redirect_url');
 
	// missing query string all together
	if (!isset ($_GET['question']) )
		wp_redirect( esc_url_raw ($redirect), 302 );
 
	// incorrect value for query string
	if ($_GET['question'] !== 'answer' )
		wp_redirect( esc_url_raw ($redirect), 302 );
 
}