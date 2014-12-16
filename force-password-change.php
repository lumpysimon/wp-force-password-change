<?php
/*
Plugin Name:  Force Password Change
Description:  Require users to change their password on first login.
Version:      0.6
License:      GPL v2 or later
Plugin URI:   https://github.com/lumpysimon/wp-force-password-change
Author:       Simon Blackbourn
Author URI:   https://twitter.com/lumpysimon
Author Email: simon@lumpylemon.co.uk
Text Domain:  force-password-change
Domain Path:  /languages/



	About this plugin
	-----------------

	This plugin redirects newly-registered users to the Admin -> Edit Profile page when they first log in.
	Until they have changed their password, they will not be able to access either the front-end or other admin pages.
	An admin notice is also displayed informing them that they must change their password.

	New administrators must also change their password, but as a safety measure they can also access the Admin -> Plugins page.

	Please report any bugs on the WordPress support forum at http://wordpress.org/support/plugin/force-password-change or via GitHub at https://github.com/lumpysimon/wp-force-password-change/issues

	Development takes place at https://github.com/lumpysimon/wp-force-password-change (all pull requests will be considered)



	About me
	--------

	I'm Simon Blackbourn, co-founder of Lumpy Lemon, a small & friendly UK-based
	WordPress design & development company specialising in custom-built WordPress CMS sites.
	I work mainly, but not exclusively, with not-for-profit organisations.

	Find me on Twitter, Skype & GitHub: lumpysimon



	License
	-------

	Copyright (c) Lumpy Lemon Ltd. All rights reserved.

	Released under the GPL license:
	http://www.opensource.org/licenses/gpl-license.php

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.



*/



$force_password_change = new force_password_change;



class force_password_change {



	// just a bunch of functions called from various hooks
	function __construct() {

		add_action( 'init',                    array( $this, 'init' ) );
		add_action( 'user_register',           array( $this, 'registered' ) );
		add_action( 'personal_options_update', array( $this, 'updated' ) );
		add_action( 'template_redirect',       array( $this, 'redirect' ) );
		add_action( 'current_screen',          array( $this, 'redirect' ) );
		add_action( 'admin_notices',           array( $this, 'notice' ) );

	}



	// load localisation files
	function init() {

		load_plugin_textdomain(
			'force-password-change',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
			);

	}



	// add a user meta field when a new user is registered
	function registered( $user_id ) {

		add_user_meta( $user_id, 'force-password-change', 1 );

	}



	// delete the user meta field when a user successfully changes their password
	function updated( $user_id ) {

		$pass1 = $pass2 = '';

		if ( isset( $_POST['pass1'] ) )
			$pass1 = $_POST['pass1'];

		if ( isset( $_POST['pass2'] ) )
			$pass2 = $_POST['pass2'];

		if (
			$pass1 != $pass2
			or
			empty( $pass1 )
			or
			empty( $pass2 )
			or
			false !== strpos( stripslashes( $pass1 ), "\\" )
			)
			return;

		delete_user_meta( $user_id, 'force-password-change' );

	}



	// if:
	// - we're logged in,
	// - the user meta field is present,
	// - we're on the front-end or any admin screen apart from the edit profile page or plugins page,
	// then redirect to the edit profile page
	function redirect() {

		global $current_user;

		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( 'profile' == $screen->base )
				return;
			if ( 'plugins' == $screen->base )
				return;
		}

		if ( ! is_user_logged_in() )
			return;

		wp_get_current_user();

		if ( get_user_meta( $current_user->ID, 'force-password-change', true ) ) {
			wp_redirect( admin_url( 'profile.php' ) );
			exit; // never forget this after wp_redirect!
		}

	}



	// if the user meta field is present, display an admin notice
	function notice() {

		global $current_user;

		wp_get_current_user();

		if ( get_user_meta( $current_user->ID, 'force-password-change', true ) ) {
			printf(
				'<div class="error"><p>%s</p></div>',
				__( 'Please change your password in order to continue using this website', 'force-password-change' )
				);
		}

	}



} // class
