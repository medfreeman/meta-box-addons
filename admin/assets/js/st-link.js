/*global jQuery:false */
(function( $, undefined ) {
	"use strict";
  
	// Get Link
	$( '.rwmb-link' ).each( function()
	{
		var $this = $( this );
		
		if ( isUrl( $this.val() ) ) {
			$this.siblings( '.rwmb_view_link' ).removeAttr( 'disabled' );
		} else {
			$this.siblings( '.rwmb_view_link' ).on( 'click', disableLink );
		}
    
		$this.bind( 'keyup blur', function() {
			var $link = $(this).siblings( '.rwmb_view_link' );
			$link.attr( 'href', this.value );
				
			if ( isUrl( this.value ) ) {
				$link.removeAttr( 'disabled' );
				$link.off( 'click', disableLink );
			} else {
				$link.attr( 'disabled', 'disabled' );
				$link.on( 'click', disableLink );
			}
		});

	} );
	
	function isUrl( s ) {
		var regexp = /(mailto|ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		return regexp.test( s );
	}
	
	function disableLink() { 
		return false; 
	}
})( jQuery );
