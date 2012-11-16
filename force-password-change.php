<?php
/*
Plugin Name: Force Password Change
Plugin URI:  https://github.com/lumpysimon/wp-force-password-change
Description: Require users to change their password on first login.
Author:      Simon Blackbourn @ Lumpy Lemon
Version:     0.1
Author URI:  https://twitter.com/lumpysimon



	About this plugin
	-----------------

	This plugin forces newly-registered users to change their password when they first log in.
	Until they have done this, they will be redirected to the Admin -> Edit Profile page,
	with a notice informing them to change their password.

	As of version 0.1, this is a hastily knocked-up plugin in response to
	an enquiry from a client and a question on WordPress Answers:
	http://wordpress.stackexchange.com/questions/72788/wordpress-force-users-to-change-password-on-first-login

	There may well be bugs and it can almost certainly be improved.



	About me
	--------

	I'm Simon Blackbourn, co-founder of Lumpy Lemon,
	a small & friendly UK-based WordPress design & development company
	specialising in custom-built WordPress CMS sites for about 7 years.
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



class forcePasswordChange {



	// just a bunch of functions called from various hooks
	function __construct() {

		add_action( 'user_register',     array( $this, 'registered' ) );
		add_action( 'profile_update',    array( $this, 'updated' ), 10, 2 );
		add_action( 'template_redirect', array( $this, 'redirect' ) );
		add_action( 'current_screen',    array( $this, 'redirect' ) );
		add_action( 'admin_notices',     array( $this, 'notice' ) );

	}



	// add a user meta field when a new user is registered
	function registered( $user_id ) {

		add_user_meta( $user_id, 'force-password-change', 1 );

	}



	// delete the user meta field when a user changes their password
	function updated( $user_id, $old_data ) {

		$user_data = get_userdata( $user_id );

		if ( $user_data->user_pass != $old_data->user_pass ) {
			delete_user_meta( $user_id, 'force-password-change' );
		}

	}



	// if:
	// - we're logged in,
	// - we're not an admin user,
	// - the user meta field is present,
	// - we're on the front-end or any admin screen apart from the edit profile page,
	// then redirect to the edit profile page
	function redirect() {

		global $current_user;

		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( 'profile' == $screen->base )
				return;
		}

		if ( ! is_user_logged_in() )
			return;

		if ( current_user_can( 'activate_plugins' ) )
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
			echo '<div class="error"><p>Please change your password in order to continue using this website</p></div>';
		}


	}



}



$force_password_change = new forcePasswordChange;



?>