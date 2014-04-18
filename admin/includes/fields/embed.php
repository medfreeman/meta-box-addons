<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Embed_Field' ) ) {
	class RWMB_Embed_Field extends RWMB_Field
	{
		
		/**
		 * Enqueue scripts and styles
		 *
		 * @return	void
		 */
		static function admin_enqueue_scripts( )
		{
			wp_enqueue_script( 'rwmb-embed', plugins_url( '../../assets/js/embed.js' , __FILE__ ), RWMB_VER );
		}
		
		/**
		 * Add actions
		 *
		 * @return void
		 */
		static function add_actions()
		{
			// Get embed code via Ajax
			add_action( 'wp_ajax_rwmb_show_embed', array( __CLASS__, 'wp_ajax_show_embed' ) );
		}

		/**
		 * Ajax callback for getting video embed
		 *
		 * @return void
		 */
		static function wp_ajax_show_embed() {
			$field_id = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$url = isset( $_POST['url'] ) ? $_POST['url'] : '';
			//check_admin_referer( "rwmb-show-embed_{$field_id}" );
			
			$result = self::get_embed_html( $_POST['url'] );
			
			header( 'Content-type: application/json' );
			
			if ( $result ) {
				echo json_encode( array( 'response' => $result ) );
			} else {
				echo json_encode( array( 'error' => __( 'Error: Link Not Embeddible.', Meta_Box_addons::get_instance()->get_plugin_slug() ) ) );
			}
			
			exit;
		}

		/**
		 * Get field HTML
		 *
		 * @param string $html
		 * @param mixed  $meta
		 * @param array  $field
		 *
		 * @return string
		 */
		static function html( $meta, $field )
		{
			$name     = " name='{$field['field_name']}'";
			$id       = isset( $field['clone'] ) && $field['clone'] ? '' : " id='{$field['id']}'";
			$val      = " value='{$meta}'";
			$size     = isset( $field['size'] ) ? $field['size'] : '30';
			$std      = isset( $field['disabled'] ) ? $field['disabled'] : false;
			$disabled = disabled( $std, true, false );

			$html = "<input type='text' class='rwmb-embed'{$name}{$id}{$val}{$disabled} size='{$size}' />";
			//$html 	 .= wp_nonce_field( "rwmb-show-embed_{$field['id']}", "nonce-show-embed_{$field['id']}", false, false );

			$html .= '<a href="#" class="rwmb_view_embed button" >' . __( 'Show embed', Meta_Box_addons::get_instance()->get_plugin_slug() ) . '</a>';
			$html .= '<a href="#" class="rwmb_remove_embed button hidden">' . __( 'Hide embed', Meta_Box_addons::get_instance()->get_plugin_slug() ) . '</a>';
			
			$html .= '<div class="rwmb_embed_container">';
			$html .= '</div>';
			
			return $html;
		}
		
		function get_embed_html( $url ) {
			require_once( ABSPATH . WPINC . '/class-oembed.php' );
			$oembed = _wp_oembed_get_object();
			return $oembed->get_html( $url );		
		}
	}
}
