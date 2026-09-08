(function ($) {
	'use strict';

	var frame;
	var currentField = null;

	function setPreview( $field, attachment ) {
		var thumb = ( attachment.sizes && attachment.sizes.thumbnail )
			? attachment.sizes.thumbnail.url
			: attachment.url;

		$field.find( '#album-cover-id' ).val( attachment.id );
		$field.find( '.sm-cover-field__preview' ).html( '<img src="' + thumb + '" style="max-width:150px;height:auto;" alt="">' );
		$field.find( '.sm-cover-field__upload' ).text( smTaxonomyMedia.change );
		$field.find( '.sm-cover-field__remove' ).show();
	}

	$( document ).on( 'click', '.sm-cover-field__upload', function ( e ) {
		e.preventDefault();

		currentField = $( this ).closest( '.sm-cover-field' );

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media( {
			title: smTaxonomyMedia.title,
			button: { text: smTaxonomyMedia.button },
			multiple: false,
			library: { type: 'image' }
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			if ( currentField ) {
				setPreview( currentField, attachment );
			}
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.sm-cover-field__remove', function ( e ) {
		e.preventDefault();

		var $field = $( this ).closest( '.sm-cover-field' );

		$field.find( '#album-cover-id' ).val( '' );
		$field.find( '.sm-cover-field__preview' ).empty();
		$field.find( '.sm-cover-field__upload' ).text( smTaxonomyMedia.upload );
		$( this ).hide();
	} );
})( jQuery );