<?php
/**
 * Meta_Box_addons
 *
 * @package   Meta_Box_addons
 * @author    Mehdi Lahlou <mehdi.lahlou@free.fr>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins
 * @copyright 2014 Mehdi Lahlou
 */

/**
 * Meta_Box_addons class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-meta-box-addons-admin.php`
 *
 * @package Meta_Box_addons
 * @author  Mehdi Lahlou <mehdi.lahlou@free.fr>
 */
class Meta_Box_addons {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'meta-box-addons';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_filter( 'meta', array( $this, 'rwmb_extend_meta_helper' ), 10, 4 );
		add_filter( 'oembed_fetch_url', array( $this, 'rwmb_force_oembed_dimensions_request' ), 10, 3 );
		add_filter( 'oembed_result', array( $this, 'rwmb_force_oembed_dimensions_result' ), 10, 3 );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}
	
	/**
	 * Extends rwmb_meta_box helper function to support new fields.
	 *
	 * @since    1.0.0
	 */
	public function rwmb_extend_meta_helper( $meta, $key, $args, $post_id ) {
		if ( in_array( $args['type'], array( 'text', 'textarea' ) ) ) {
			if ( isset( $args['qtranslate'] ) && true == $args['qtranslate'] ) {
				if ( is_array( $meta ) ) {
					$meta = array_map( '__', $meta );
				} else {
					$meta = __( $meta );
				}
			}
			if ( isset( $args['nl2p'] ) && true == $args['nl2p'] ) {
				if ( is_array( $meta ) ) {
					$meta = array_map( array( $this, 'nl2p' ), $meta );
				} else {
					$meta = $this->nl2p( $meta );
				}
			}
		} else if ( 'embed' == $args['type'] ) {
			$args['requester'] = 'rwmb';
			if ( is_array( $meta ) ) {
				array_walk( $meta, array( $this, 'get_oembed_code' ), $args );
			} else {
				$this->get_oembed_code( $meta, null, $args );
			}			
		} else if ( 'st_link' == $args['type'] ) {
			$attrs = isset( $args['target'] ) ? ' target="' . $args['target'] . '"' : '';
			if ( is_array( $meta ) ) {
				array_walk( $meta, array( $this, 'get_link_from_url' ), $attrs );
				if ( isset( $args['format'] ) && 'list' == $args['format'] ) {
					$meta = implode( isset( $args['separator'] ) ? ' ' . $args['separator'] . ' ' : ' | ', $meta );
				}
			} else {
				$this->get_link_from_url( $meta, null, $attrs );
			}
		} else if ( in_array( $args['type'], array( 'image', 'plupload_image', 'thickbox_image', 'image_advanced' ) ) && isset( $args['format'] ) && 'html' == $args['format'] ) {
			$meta = array_map( array( $this, 'get_image_html' ), $meta );
		}
		return $meta;
	}
	
	/**
	 * Forces oembed to apply set dimensions (force aspect ratio) when width and height are specified and oembed request comes from rwmb.
	 *
	 * @since    1.0.0
	 */
	public function rwmb_force_oembed_dimensions_request( $provider, $url, $args ) {
		if ( isset( $args['requester'] ) && 'rwmb' == $args['requester'] ) {
			if ( isset( $args['width'] ) && isset( $args['height'] ) ) {
				$provider = add_query_arg( 'width', (int) $args['width'], $provider );
				$provider = add_query_arg( 'height', (int) $args['height'], $provider );
			}
		}
		return $provider;
	}
	
	public function rwmb_force_oembed_dimensions_result( $data, $url, $args ) {
		if ( isset( $args['requester'] ) && 'rwmb' == $args['requester'] ) {
			if ( isset( $args['width'] ) && isset( $args['height'] ) ) {
				$data = preg_replace( array( '/ width="\d+"/', '/ height="\d+"/' ), array( ' width="' . $args['width'] . '"', ' height="' . $args['height'] . '"' ), $data );
			}
		}
		return $data;
	}
	
	/**
	 * Gets oembed code (set via reference because of array_walk).
	 *
	 * @since    1.0.0
	 */
	private function get_oembed_code( &$url, $key, $args ) {
		require_once( ABSPATH . WPINC . '/class-oembed.php' );
		$oembed = _wp_oembed_get_object();
		$url    = $oembed->get_html( $url, $args );
	}
	
	/**
	 * Returns image html from image information.
	 *
	 * @since    1.0.0
	 */
	private function get_image_html( $image ) {
		return '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $image['alt'] . '" />';
	}
	
	/**
	 * Gets link from an url (set via reference because of array_walk).
	 *
	 * @since    1.0.0
	 */
	private function get_link_from_url( &$url, $key, $attrs ) {
		$text = rtrim( $this->remove_http( $url ) , '/' );
		$url  = '<a href="' . $url . '"' . $attrs . '>' . $text . '</a>';
	}
	
	/**
	 * Removes protocol from an url (http and https).
	 *
	 * @since    1.0.0
	 */
	private function remove_http( $url ) {
		$disallowed = array( 'http://', 'https://' );
		foreach ( $disallowed as $d ) {
			if ( strpos( $url, $d ) === 0 ) {
				return str_replace( $d, '', $url );
			}
		}
		return $url;
	}
	
	private function nl2p( $string, $line_breaks = false, $xml = true ) {
		$string = str_replace( array( '<p>', '</p>', '<br>', '<br />' ), '', $string );

		// It is conceivable that people might still want single line-breaks
		// without breaking into a new paragraph.
		if ( $line_breaks == true ) {
			return '<p>' . preg_replace( array( "/([\n]{2,})/i", "/([^>])\n([^<])/i" ), array( "</p>\n<p>", '$1<br' . ( $xml == true ? ' /' : '' ) . '>$2' ), trim( $string ) ) . '</p>';
		} else {
			return '<p>' . preg_replace( array( "/([\n]{2,})/i", "/([\r\n]{3,})/i", "/([^>])\n([^<])/i" ), array( "</p>\n<p>", "</p>\n<p>", '$1<br' . ( $xml == true ? ' /' : '' ) . '>$2' ), trim( $string ) ).'</p>';
		}
	}

}
