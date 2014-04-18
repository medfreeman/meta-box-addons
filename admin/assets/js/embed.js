jQuery( document ).ready( function($)
{
	// Get Embed
	$( '.rwmb-embed' ).each( function()
	{
		$(this).siblings( '.rwmb_view_embed' ).click (function()
		{
				var $this		= $(this).prevAll('.rwmb-embed'),
					field_id	= $this.attr('id'),
					container	= $this.siblings('.rwmb_embed_container'),
					data		= {
						action  : 'rwmb_show_embed',
						/*_wpnonce: $('#nonce-show-embed_' + field_id).val(),*/
						field_id: field_id,
						url		: $this.val()
					};
				$.ajax( ajaxurl, { 
					data: data, 
					type: 'POST',
					dataType: 'json', 
					success: function( data, textStatus, jqXHR){
						if ( typeof data.error !== 'undefined' ) {
							alert( data.error );
						} else {
							container.html( data.response );	
							container.siblings(".rwmb_remove_embed").show();
						}
					}
				});
				return false;
		} );
		$(this).siblings( '.rwmb_remove_embed' ).click (function()
		{
			var $this = $(this);
			container = $this.siblings('.rwmb_embed_container');
			container.empty();
			$this.hide();
			return false;
		} );
	} );
} );