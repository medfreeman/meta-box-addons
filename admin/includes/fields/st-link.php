<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_St_Link_Field' ) ) {
	class RWMB_St_Link_Field extends RWMB_Field
	{
		
		/**
		 * Enqueue scripts and styles
		 *
		 * @return	void
		 */
		static function admin_enqueue_scripts( )
		{
			wp_enqueue_script( 'rwmb-st-link', plugins_url( '../../assets/js/st-link.js', __FILE__ ), array(), RWMB_VER );
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
		static function html( $meta, $field ) {
			$value = ' value="'.$meta.'"';
			$name  = ' name="'.$field['field_name'].'"';
			$id    = isset( $field['clone'] ) && $field['clone'] ? '' : 'id="'.$field['id'].'"';
			
			$size     = isset( $field['size'] ) ? $field['size'] : '30';
			$std      = isset( $field['disabled'] ) ? $field['disabled'] : false;
			$disabled = disabled( $std, true, false );

			$html .= '<input type="text" class="rwmb-link"' . $name . $id . $value . $disabled . ' size="' . $size . '" />';

			$html .= '<a href="' . $meta . '" class="rwmb_view_link button" target="_blank" disabled="disabled">' . __( 'Open Link', Meta_Box_addons::get_instance()->get_plugin_slug() ) . '</a>';
			
			return $html;
		}
		
		static function save( $new, $old, $post_id, $field ) {
			if ( is_array( $new ) ) {
				$new = array_map( array( self, 'get_correct_url' ), $new );
			} else {
				$new = self::get_correct_url( $new );
			}
			parent::save( $new, $old, $post_id, $field );
		}
		
		static function get_correct_url( $url ) {
			$url_has_http = preg_match( '@^https?://@', $url );
			$part = ( ! $url_has_http )  ? 'http://' : '' ;
			$is_url = (bool) filter_var( $part.$url, FILTER_VALIDATE_URL );
			if ( $is_url && ! $url_has_http ) {
				return  $part . $url;
			}
			return $url;
		}
	}
}
