/**
 * Santiago Moraes — Block Editor Registration
 *
 * Provides edit/save functions for all 8 custom SSR blocks.
 * Blocks are registered server-side from block.json; this script
 * hooks into blocks.registerBlockType to inject the editor UI.
 *
 * No build step — uses wp.* globals directly.
 *
 * @package Santiago_Moraes
 */

/* global wp */
( function () {
	'use strict';

	var el                = wp.element.createElement;
	var Fragment          = wp.element.Fragment;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var InnerBlocks       = wp.blockEditor.InnerBlocks;
	var useBlockProps     = wp.blockEditor.useBlockProps;
	var MediaUpload       = wp.blockEditor.MediaUpload;
	var PanelBody         = wp.components.PanelBody;
	var SelectControl     = wp.components.SelectControl;
	var TextControl       = wp.components.TextControl;
	var ToggleControl     = wp.components.ToggleControl;
	var RangeControl      = wp.components.RangeControl;
	var Button            = wp.components.Button;
	var ServerSideRender  = wp.serverSideRender;

	// -----------------------------------------------------------------
	// Map of block name → { edit, save } components
	// -----------------------------------------------------------------
	var blockEditors = {};

	// Helper: wraps ServerSideRender in a div with block props.
	function ssrEdit( blockName ) {
		return function ( props ) {
			return el( 'div', useBlockProps(),
				el( ServerSideRender, {
					block: blockName,
					attributes: props.attributes,
				} )
			);
		};
	}

	// =================================================================
	// 1. sm/hero-section
	// =================================================================
	blockEditors[ 'sm/hero-section' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;

			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Configuración del Hero', initialOpen: true },
						el( SelectControl, {
							label: 'Estilo de fondo',
							value: a.bgStyle,
							options: [
								{ label: 'Oscuro', value: 'dark' },
								{ label: 'Claro', value: 'light' },
							],
							onChange: function ( v ) { s( { bgStyle: v } ); },
						} ),
						el( TextControl, {
							label: 'Línea 1 del título',
							value: a.headingLine1,
							onChange: function ( v ) { s( { headingLine1: v } ); },
						} ),
						el( TextControl, {
							label: 'Línea 2 (acento)',
							value: a.headingLine2,
							onChange: function ( v ) { s( { headingLine2: v } ); },
						} ),
						el( TextControl, {
							label: 'Descripción',
							value: a.heroDescription,
							onChange: function ( v ) { s( { heroDescription: v } ); },
						} ),
						el( 'div', { className: 'components-base-control' },
							el( 'label', { className: 'components-base-control__label' }, 'Imagen' ),
							el( MediaUpload, {
								onSelect: function ( media ) {
									s( { imageId: media.id, imageUrl: media.url } );
								},
								allowedTypes: [ 'image' ],
								value: a.imageId,
								render: function ( ref ) {
									return el( Button, {
										onClick: ref.open,
										variant: 'secondary',
									}, a.imageId ? 'Cambiar imagen' : 'Seleccionar imagen' );
								},
							} )
						),
						a.imageUrl ? el( 'img', {
							src: a.imageUrl, alt: '',
							style: { maxWidth: '100%', marginTop: '8px', borderRadius: '4px' },
						} ) : null,
						el( TextControl, {
							label: 'Botón 1 — texto', value: a.button1Text,
							onChange: function ( v ) { s( { button1Text: v } ); },
						} ),
						el( TextControl, {
							label: 'Botón 1 — URL', value: a.button1Url,
							onChange: function ( v ) { s( { button1Url: v } ); },
						} ),
						el( TextControl, {
							label: 'Botón 2 — texto', value: a.button2Text,
							onChange: function ( v ) { s( { button2Text: v } ); },
						} ),
						el( TextControl, {
							label: 'Botón 2 — URL', value: a.button2Url,
							onChange: function ( v ) { s( { button2Url: v } ); },
						} )
					)
				),
				el( 'div', useBlockProps(),
					el( ServerSideRender, { block: 'sm/hero-section', attributes: a } )
				)
			);
		},
		save: function () { return null; },
	};

	// =================================================================
	// 2. sm/section (InnerBlocks)
	// =================================================================
	blockEditors[ 'sm/section' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;

			var bgMap   = { dark: '#010101', light: '#FFFFFF', cream: '#F7F3F0' };
			var txtMap  = { dark: '#F7F3F0', light: '#1B110D', cream: '#1B110D' };
			var padMap  = { compact: '32px 20px', normal: '50px 20px', wide: '80px 20px' };
			var maxMap  = { narrow: '720px', normal: '1140px', full: 'none' };

			var wrapStyle = {
				backgroundColor: bgMap[ a.bgColor ] || '#FFF',
				color: txtMap[ a.bgColor ] || '#1B110D',
				padding: padMap[ a.padding ] || '50px 20px',
				maxWidth: maxMap[ a.width ] || '1140px',
				marginLeft: 'auto',
				marginRight: 'auto',
			};

			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Configuración de Sección', initialOpen: true },
						el( SelectControl, {
							label: 'Color de fondo', value: a.bgColor,
							options: [
								{ label: 'Claro', value: 'light' },
								{ label: 'Oscuro', value: 'dark' },
								{ label: 'Crema', value: 'cream' },
							],
							onChange: function ( v ) { s( { bgColor: v } ); },
						} ),
						el( SelectControl, {
							label: 'Padding', value: a.padding,
							options: [
								{ label: 'Compacto', value: 'compact' },
								{ label: 'Normal', value: 'normal' },
								{ label: 'Amplio', value: 'wide' },
							],
							onChange: function ( v ) { s( { padding: v } ); },
						} ),
						el( SelectControl, {
							label: 'Ancho', value: a.width,
							options: [
								{ label: 'Angosto (720px)', value: 'narrow' },
								{ label: 'Normal (1140px)', value: 'normal' },
								{ label: 'Completo', value: 'full' },
							],
							onChange: function ( v ) { s( { width: v } ); },
						} )
					)
				),
				el( 'div', useBlockProps( { style: wrapStyle } ),
					el( InnerBlocks, {} )
				)
			);
		},
		save: function () {
			return el( InnerBlocks.Content, {} );
		},
	};

	// =================================================================
	// 3. sm/platform-links
	// =================================================================
	blockEditors[ 'sm/platform-links' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;
			var links = a.links || [];

			var opts = [
				{ label: 'Spotify', value: 'spotify' },
				{ label: 'Bandcamp', value: 'bandcamp' },
				{ label: 'YouTube', value: 'youtube' },
				{ label: 'SoundCloud', value: 'soundcloud' },
				{ label: 'Apple Music', value: 'apple' },
				{ label: 'Vinilo', value: 'vinyl' },
			];

			function upd( i, k, v ) {
				var nl = links.slice();
				nl[ i ] = Object.assign( {}, nl[ i ] );
				nl[ i ][ k ] = v;
				s( { links: nl } );
			}
			function add() { s( { links: links.concat( [ { platform: 'spotify', url: '' } ] ) } ); }
			function rm( i ) { var nl = links.slice(); nl.splice( i, 1 ); s( { links: nl } ); }

			var panels = links.map( function ( lnk, i ) {
				return el( PanelBody, { key: i, title: 'Enlace ' + ( i + 1 ), initialOpen: i === 0 },
					el( SelectControl, {
						label: 'Plataforma', value: lnk.platform, options: opts,
						onChange: function ( v ) { upd( i, 'platform', v ); },
					} ),
					el( TextControl, {
						label: 'URL', value: lnk.url,
						onChange: function ( v ) { upd( i, 'url', v ); },
					} ),
					el( Button, {
						isDestructive: true, variant: 'secondary',
						onClick: function () { rm( i ); },
						style: { marginTop: '8px' },
					}, 'Eliminar' )
				);
			} );

			return el( Fragment, {},
				el( InspectorControls, {},
					panels,
					el( PanelBody, { title: 'Agregar' },
						el( Button, { variant: 'primary', onClick: add }, '+ Agregar plataforma' )
					)
				),
				el( 'div', useBlockProps(),
					el( ServerSideRender, { block: 'sm/platform-links', attributes: a } )
				)
			);
		},
		save: function () { return null; },
	};

	// =================================================================
	// 4. sm/upcoming-shows
	// =================================================================
	blockEditors[ 'sm/upcoming-shows' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;
			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Configuración', initialOpen: true },
						el( RangeControl, {
							label: 'Cantidad de shows', value: a.count, min: 1, max: 12,
							onChange: function ( v ) { s( { count: v } ); },
						} ),
						el( ToggleControl, {
							label: 'Mostrar "Ver todos"', checked: a.showViewAll,
							onChange: function ( v ) { s( { showViewAll: v } ); },
						} )
					)
				),
				el( 'div', useBlockProps(),
					el( ServerSideRender, { block: 'sm/upcoming-shows', attributes: a } )
				)
			);
		},
		save: function () { return null; },
	};

	// =================================================================
	// 5. sm/latest-songs
	// =================================================================
	blockEditors[ 'sm/latest-songs' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;
			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Configuración', initialOpen: true },
						el( RangeControl, {
							label: 'Cantidad de canciones', value: a.count, min: 1, max: 12,
							onChange: function ( v ) { s( { count: v } ); },
						} ),
						el( ToggleControl, {
							label: 'Mostrar badge de acordes', checked: a.showChordsBadge,
							onChange: function ( v ) { s( { showChordsBadge: v } ); },
						} )
					)
				),
				el( 'div', useBlockProps(),
					el( ServerSideRender, { block: 'sm/latest-songs', attributes: a } )
				)
			);
		},
		save: function () { return null; },
	};

	// =================================================================
	// 6. sm/contact-form
	// =================================================================
	blockEditors[ 'sm/contact-form' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;
			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Configuración', initialOpen: true },
						el( SelectControl, {
							label: 'Tipo de formulario', value: a.formType,
							options: [
								{ label: 'Completo (nombre, email, asunto, mensaje)', value: 'full' },
								{ label: 'Simple (email + mensaje)', value: 'simple' },
							],
							onChange: function ( v ) { s( { formType: v } ); },
						} ),
						el( ToggleControl, {
							label: 'Mostrar redes sociales', checked: a.showSocial,
							onChange: function ( v ) { s( { showSocial: v } ); },
						} )
					)
				),
				el( 'div', useBlockProps(),
					el( ServerSideRender, { block: 'sm/contact-form', attributes: a } )
				)
			);
		},
		save: function () { return null; },
	};

	// =================================================================
	// 7. sm/social-links
	// =================================================================
	blockEditors[ 'sm/social-links' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;
			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Configuración', initialOpen: true },
						el( SelectControl, {
							label: 'Tamaño', value: a.size,
							options: [
								{ label: 'Pequeño', value: 'small' },
								{ label: 'Mediano', value: 'medium' },
								{ label: 'Grande', value: 'large' },
							],
							onChange: function ( v ) { s( { size: v } ); },
						} ),
						el( SelectControl, {
							label: 'Alineación', value: a.alignment,
							options: [
								{ label: 'Izquierda', value: 'left' },
								{ label: 'Centro', value: 'center' },
								{ label: 'Derecha', value: 'right' },
							],
							onChange: function ( v ) { s( { alignment: v } ); },
						} )
					)
				),
				el( 'div', useBlockProps(),
					el( ServerSideRender, { block: 'sm/social-links', attributes: a } )
				)
			);
		},
		save: function () { return null; },
	};

	// =================================================================
	// 8. sm/album-grid
	// =================================================================
	blockEditors[ 'sm/album-grid' ] = {
		edit: function ( props ) {
			var a = props.attributes;
			var s = props.setAttributes;
			return el( Fragment, {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Configuración', initialOpen: true },
						el( SelectControl, {
							label: 'Columnas', value: String( a.columns ),
							options: [
								{ label: '2 columnas', value: '2' },
								{ label: '3 columnas', value: '3' },
							],
							onChange: function ( v ) { s( { columns: parseInt( v, 10 ) } ); },
						} ),
						el( ToggleControl, {
							label: 'Incluir demos', checked: a.showDemos,
							onChange: function ( v ) { s( { showDemos: v } ); },
						} ),
						el( ToggleControl, {
							label: 'Mostrar año', checked: a.showYear,
							onChange: function ( v ) { s( { showYear: v } ); },
						} )
					)
				),
				el( 'div', useBlockProps(),
					el( ServerSideRender, { block: 'sm/album-grid', attributes: a } )
				)
			);
		},
		save: function () { return null; },
	};

	// =================================================================
	// Hook: inject edit/save into server-registered blocks
	// =================================================================
	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'santiago-moraes/inject-editor',
		function ( settings, name ) {
			if ( blockEditors[ name ] ) {
				settings.edit = blockEditors[ name ].edit;
				settings.save = blockEditors[ name ].save;
			}
			return settings;
		}
	);

} )();
