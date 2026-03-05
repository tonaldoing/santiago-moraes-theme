/**
 * Sticky Spotify Player — minimize/expand toggle with sessionStorage.
 *
 * @package Santiago_Moraes
 */
( function () {
	'use strict';

	var STORAGE_KEY  = 'sm_player_minimized';
	var player       = document.getElementById( 'sticky-player' );
	var closeBtn     = document.getElementById( 'sticky-player-close' );
	var fab          = document.getElementById( 'sticky-player-fab' );

	if ( ! player || ! closeBtn || ! fab ) {
		return;
	}

	// Check if player was minimized in this session.
	var isMinimized = sessionStorage.getItem( STORAGE_KEY ) === '1';

	if ( isMinimized ) {
		minimize();
	} else {
		expand();
	}

	// Close / minimize button.
	closeBtn.addEventListener( 'click', function () {
		minimize();
		sessionStorage.setItem( STORAGE_KEY, '1' );
	} );

	// FAB — expand the player.
	fab.addEventListener( 'click', function () {
		expand();
		sessionStorage.setItem( STORAGE_KEY, '0' );
	} );

	function minimize() {
		player.style.display = 'none';
		player.setAttribute( 'aria-hidden', 'true' );
		fab.style.display = 'flex';
		document.body.classList.remove( 'has-sticky-player' );
	}

	function expand() {
		player.style.display = 'block';
		player.removeAttribute( 'aria-hidden' );
		fab.style.display = 'none';
		document.body.classList.add( 'has-sticky-player' );
	}
} )();
