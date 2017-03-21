<?php
/**
 * Plugin Name: Custom Content Portfolio Extras
 * Plugin URI: http://themehybrid.com/plugins/custom-content-portfolio
 * Description: Adds CARES-specific extras to Justin Tadlock's Custom Content Portfolio plugin.
 * Version: 0.1
 * Author: David Cavins
 * Author URI: http://justintadlock.com
 *
 * The Custom Content Portfolio plugin was created to solve the problem of theme developers continuing
 * to incorrectly add custom post types to handle portfolios within their themes.  This plugin allows
 * any theme developer to build a "portfolio" theme without having to code the functionality.  This
 * gives more time for design and makes users happy because their data isn't lost when they switch to
 * a new theme.  Oh, and, this plugin lets creative folk put together a portfolio of their work on
 * their site.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   CustomContentPortfolioExtras
 * @version   0.1.0
 * @since     0.1.0
 * @author    David Cavins
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class Custom_Content_Portfolio_Extras {

	/**
	 * PHP5 constructor method.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 2 );

		/* Internationalize the text strings used. */
		// add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 7 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( &$this, 'admin' ), 12 );

		/* Register activation hook. */
		// register_activation_hook( __FILE__, array( &$this, 'activation' ) );
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function constants() {

		/* Set constant path to the plugin directory. */
		define( 'CCPE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the plugin directory URI. */
		define( 'CCPE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the includes directory. */
		define( 'CCPE_INCLUDES', CCPE_DIR . trailingslashit( 'includes' ) );

		/* Set the constant path to the includes directory. */
		define( 'CCPE_TEMPLATES', CCPE_DIR . trailingslashit( 'templates' ) );

		/* Set the constant path to the admin directory. */
		define( 'CCPE_ADMIN', CCPE_DIR . trailingslashit( 'admin' ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function includes() {

		require_once( CCPE_INCLUDES . 'functions.php' );
		require_once( CCPE_INCLUDES . 'meta.php' );

	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public function admin() {

		if ( is_admin() )
			require_once( CCPE_ADMIN . 'admin.php' );
	}

}

//Only create if the parent plugin exists
// if ( class_exists('Custom_Content_Portfolio') )
	new Custom_Content_Portfolio_Extras();