<?php
/*

*/

/* Prevent direct access to the plugin */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Stealth_Login_Page' ) ) :

/**
 * Main Stealth_Login_Page Class
 *
 * @since 4.0
 */
final class Stealth_Login_Page {

  /**
   * @var Stealth_Login_Page The one true Stealth_Login_Page
   * @since 4.0
   */
  private static $instance;

  /**
   * Main Stealth_Login_Page Instance
   *
   * Ensures that only one instance of Stealth_Login_Page exists in memory at
   * any one time. Also prevents needing to define globals all over the place.
   *
   * @since 4.0
   * @static
   * @staticvar array $instance
   * @uses Stealth_Login_Page::setup_globals() Setup the globals needed
   * @uses Stealth_Login_Page::includes() Include the required files
   * @uses Stealth_Login_Page::setup_actions() Setup the hooks and actions
   * @see SLP()
   * @return The one true Stealth_Login_Page
   */
  public static function instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Stealth_Login_Page ) ) {
      self::$instance = new Stealth_Login_Page;
      self::$instance->setup_constants();
      self::$instance->includes();
      self::$instance->load_textdomain();
    }
    return self::$instance;
  }

  /**
   * Throw error on object clone
   *
   * The whole idea of the singleton design pattern is that there is a single
   * object therefore, we don't want the object to be cloned.
   *
   * @since 4.0
   * @access protected
   * @return void
   */
  public function __clone() {
    // Cloning instances of the class is forbidden
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'slp' ), '1.6' );
  }

  /**
   * Disable unserializing of the class
   *
   * @since 4.0
   * @access protected
   * @return void
   */
  public function __wakeup() {
    // Unserializing instances of the class is forbidden
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'slp' ), '1.6' );
  }

  /**
   * Setup plugin constants
   *
   * @access private
   * @since 4.0
   * @return void
   */
  private function setup_constants() {
    // Plugin version
    if ( ! defined( 'SLP_VERSION' ) )
      define( 'SLP_VERSION', '4.0' );

    // Plugin Folder Path
    if ( ! defined( 'SLP_PLUGIN_DIR' ) )
      define( 'SLP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    // Plugin Folder URL
    if ( ! defined( 'SLP_PLUGIN_URL' ) )
      define( 'SLP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    // Plugin Root File
    if ( ! defined( 'SLP_PLUGIN_FILE' ) )
      define( 'SLP_PLUGIN_FILE', __FILE__ );
  }

  /**
   * Include required files
   *
   * @access private
   * @since 4.0
   * @return void
   */
  private function includes() {
    global $slp_options, $custom_wp_config;

    require_once SLP_PLUGIN_DIR . 'includes/admin/settings/settings.php';

    require_once SLP_PLUGIN_DIR . 'includes/install.php';
    require_once SLP_PLUGIN_DIR . 'includes/actions.php';
    require_once SLP_PLUGIN_DIR . 'includes/globals.php';
    require_once SLP_PLUGIN_DIR . 'includes/admin/logs/log.php';

    if( is_admin() ) {
      require_once SLP_PLUGIN_DIR . 'includes/admin/logs/display-log.php';
      require_once SLP_PLUGIN_DIR . 'includes/admin/settings/settings.php';
      require_once SLP_PLUGIN_DIR . 'includes/admin/welcome.php';
    } 
      else {
    }
  }

  /**
   * Loads the plugin language files
   *
   * @access public
   * @since 4.0
   * @return void
   */
  public function load_textdomain() {
    // Set filter for plugin's languages directory
    $slp_lang_dir = dirname( plugin_basename( SLP_PLUGIN_FILE ) ) . '/languages/';
    $slp_lang_dir = apply_filters( 'slp_languages_directory', $slp_lang_dir );

    // Traditional WordPress plugin locale filter
    $locale        = apply_filters( 'plugin_locale',  get_locale(), 'slp' );
    $mofile        = sprintf( '%1$s-%2$s.mo', 'slp', $locale );

    // Setup paths to current locale file
    $mofile_local  = $slp_lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/slp/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
      // Look in global /wp-content/languages/slp folder
      load_textdomain( 'slp', $mofile_global );
    } elseif ( file_exists( $mofile_local ) ) {
      // Look in local /wp-content/plugins/stealth-login-page/languages/ folder
      load_textdomain( 'slp', $mofile_local );
    } else {
      // Load the default language files
      load_plugin_textdomain( 'slp', false, $slp_lang_dir );
    }
  }
}

endif; // End if class_exists check

/**
 * The main function responsible for returning the one true Stealth_Login_Page
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $slp = SLP(); ?>
 *
 * @since 4.0
 * @return object The one true Stealth_Login_Page Instance
 */
function SLP() {
  return Stealth_Login_Page::instance();
}

// Get SLP Running
SLP();