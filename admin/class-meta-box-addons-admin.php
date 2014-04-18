<?php
/**
 * Meta_Box_addons
 *
 * @package   Meta_Box_addons_Admin
 * @author    Mehdi Lahlou <mehdi.lahlou@free.fr>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins
 * @copyright 2014 Mehdi Lahlou
 */

/**
 * Meta_Box_addons_Admin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @package Meta_Box_addons_Admin
 * @author  Mehdi Lahlou <mehdi.lahlou@free.fr>
 */
class Meta_Box_addons_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		
		if ( class_exists( 'RW_Meta_Box' ) ) {
			// Include field classes
			foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/fields/*.php' ) as $file ) {
				require_once $file;
			}
		}

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */
		
		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = Meta_Box_addons::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'admin_init', array( $this, 'meta_box_check_activation_and_version' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 *
	 * Checks if meta-box is activated. Add error message action hook if not.
	 *
	 */
	public function meta_box_check_activation_and_version() {
		if ( ! is_plugin_active( 'meta-box/meta-box.php' ) ) {
			add_action( 'admin_notices', array( $this, 'meta_box_disabled_admin_notice' ) );
		} else if ( ! defined( 'RWMB_VER' ) || version_compare( RWMB_VER, '4.3.6', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'meta_box_version_too_low_admin_notice' ) );
		}
	}
	
	/**
	 *
	 * Shows an error message if meta-box is not activated.
	 *
	 */
	public function meta_box_disabled_admin_notice() {
		echo '<div id="message" class="error"><p><strong>' . __( 'You need to install and enable meta-box for this plugin to function.', $this->plugin_slug ) . '</strong></p></div>';
	}
	
	/**
	 *
	 * Shows an error message if meta-box version is too low.
	 *
	 */
	public function meta_box_version_too_low_admin_notice() {
		echo '<div id="message" class="error"><p><strong>' . __( 'You need to install at least meta-box version 4.3.6 for this plugin to function.', $this->plugin_slug ) . defined( 'RWMB_VER' ) ?  ' ' . __( 'You have only version', $this->plugin_slug ) . ' ' . RWMB_VER . ' ' . __( 'installed.', $this->plugin_slug ) : ''  . '</strong></p></div>';
	}

}
