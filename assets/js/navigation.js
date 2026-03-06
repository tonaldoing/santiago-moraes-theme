/**
 * Mobile menu toggle, submenu accordion, and scroll-to-top.
 *
 * @package Santiago_Moraes
 */

( function () {
	'use strict';

	// =====================================================================
	// Mobile Menu Toggle
	// =====================================================================
	var toggle = document.getElementById( 'menu-toggle' );
	var mobileMenu = document.getElementById( 'mobile-menu' );

	if ( toggle && mobileMenu ) {
		var openIcon = toggle.querySelector( '.menu-toggle__open' );
		var closeIcon = toggle.querySelector( '.menu-toggle__close' );

		function closeMobileMenu() {
			mobileMenu.classList.remove( 'mobile-menu--open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			mobileMenu.setAttribute( 'aria-hidden', 'true' );
			if ( openIcon && closeIcon ) {
				openIcon.style.display = 'block';
				closeIcon.style.display = 'none';
			}
			document.body.style.overflow = '';
		}

		toggle.addEventListener( 'click', function () {
			var isOpen = mobileMenu.classList.toggle( 'mobile-menu--open' );

			toggle.setAttribute( 'aria-expanded', isOpen );
			mobileMenu.setAttribute( 'aria-hidden', ! isOpen );

			if ( openIcon && closeIcon ) {
				openIcon.style.display = isOpen ? 'none' : 'block';
				closeIcon.style.display = isOpen ? 'block' : 'none';
			}

			document.body.style.overflow = isOpen ? 'hidden' : '';
		} );

		// Close on link click (but not on submenu toggle buttons).
		mobileMenu.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				closeMobileMenu();
			} );
		} );
	}

	// =====================================================================
	// Mobile Submenu Accordion
	// =====================================================================
	var submenuToggles = document.querySelectorAll( '.mobile-menu .submenu-toggle' );

	submenuToggles.forEach( function ( btn ) {
		btn.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			e.stopPropagation();

			var parentLi = btn.closest( '.menu-item-has-children' );
			if ( ! parentLi ) return;

			var subMenu = parentLi.querySelector( ':scope > .sub-menu' );
			if ( ! subMenu ) return;

			var isOpen = subMenu.classList.contains( 'sub-menu--open' );

			// Accordion behavior: close other open submenus at the same level.
			var siblingItems = parentLi.parentElement.querySelectorAll(
				':scope > .menu-item-has-children'
			);
			siblingItems.forEach( function ( sibling ) {
				if ( sibling === parentLi ) return;
				var sibSub = sibling.querySelector( ':scope > .sub-menu' );
				var sibBtn = sibling.querySelector( ':scope > .submenu-toggle' );
				if ( sibSub && sibSub.classList.contains( 'sub-menu--open' ) ) {
					sibSub.classList.remove( 'sub-menu--open' );
					sibSub.style.maxHeight = '0';
					if ( sibBtn ) sibBtn.setAttribute( 'aria-expanded', 'false' );
				}
			} );

			// Toggle this submenu.
			if ( isOpen ) {
				subMenu.classList.remove( 'sub-menu--open' );
				subMenu.style.maxHeight = '0';
				btn.setAttribute( 'aria-expanded', 'false' );
			} else {
				subMenu.classList.add( 'sub-menu--open' );
				subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
				btn.setAttribute( 'aria-expanded', 'true' );
			}
		} );
	} );

	// =====================================================================
	// Desktop: keyboard accessibility for dropdowns
	// =====================================================================
	var desktopParents = document.querySelectorAll(
		'.main-nav .menu-item-has-children'
	);

	desktopParents.forEach( function ( item ) {
		// Show on focus-within (for keyboard nav).
		item.addEventListener( 'focusin', function () {
			item.classList.add( 'menu-item--focus' );
		} );
		item.addEventListener( 'focusout', function () {
			// Small delay to allow focus to move to submenu items.
			setTimeout( function () {
				if ( ! item.contains( document.activeElement ) ) {
					item.classList.remove( 'menu-item--focus' );
				}
			}, 10 );
		} );
	} );

	// =====================================================================
	// Header Scroll — transparent → solid on scroll (front page)
	// =====================================================================
	var header = document.getElementById( 'site-header' );

	if ( header && header.classList.contains( 'site-header--transparent' ) ) {
		var onScroll = function () {
			if ( window.scrollY > 50 ) {
				header.classList.add( 'site-header--scrolled' );
			} else {
				header.classList.remove( 'site-header--scrolled' );
			}
		};

		// Check initial state (page might load already scrolled).
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	// =====================================================================
	// Scroll to Top
	// =====================================================================
	var scrollBtn = document.getElementById( 'scroll-top' );

	if ( scrollBtn ) {
		window.addEventListener( 'scroll', function () {
			if ( window.scrollY > 400 ) {
				scrollBtn.classList.add( 'scroll-top--visible' );
			} else {
				scrollBtn.classList.remove( 'scroll-top--visible' );
			}
		}, { passive: true } );

		scrollBtn.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

	// =====================================================================
	// Smooth Scroll for Anchor Links
	// =====================================================================
	document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( anchor ) {
		anchor.addEventListener( 'click', function ( e ) {
			var targetId = this.getAttribute( 'href' );
			if ( targetId === '#' ) return;

			var target = document.querySelector( targetId );
			if ( target ) {
				e.preventDefault();
				var headerHeight = document.getElementById( 'site-header' );
				headerHeight = headerHeight ? headerHeight.offsetHeight : 0;
				var top = target.getBoundingClientRect().top + window.scrollY - headerHeight;
				window.scrollTo( { top: top, behavior: 'smooth' } );
			}
		} );
	} );
} )();
