<?php
/**
 * Meta Box addons
 *
 * Meta Box plugins addons (new field types, helpers)
 *
 * @package   Meta_Box_addons
 * @author    Mehdi Lahlou <mehdi.lahlou@free.fr>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins
 * @copyright 2014 Mehdi Lahlou
 *
 * @wordpress-plugin
 * Plugin Name:       Meta Box addons
 * Plugin URI:        https://github.com/medfreeman/meta-box-addons
 * Description:       Meta Box plugins adons (new field types, helpers) - Requires meta-box plugin
 * Version:           1.0.1
 * Author:            Mehdi Lahlou
 * Author URI:        
 * Text Domain:       meta-box-addons
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/medfreeman/meta-box-addons.git
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-meta-box-addons.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Meta_Box_addons', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Meta_Box_addons', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Meta_Box_addons', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-meta-box-addons-admin.php' );
	add_action( 'plugins_loaded', array( 'Meta_Box_addons_Admin', 'get_instance' ) );

}
