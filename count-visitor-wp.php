<?php
/*
Plugin Name: Count Visitor WP
Description: WordPress plugin utility used for counting user page visits on your WordPress site pages and post.  
Version: 1.0.0
Author: DopeThemes
Author URI: http://www.dopethemes.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
Plugin URI: http://www.dopethemes.com/downloads/count-visitor-wp/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
Copyright: DopeThemes
Text Domain: cvwp
Domain Path: /lang
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

/*
    Copyright DopeThemes

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1335, USA
*/

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists('count_visitor_wp') ) :


class count_visitor_wp {

	// vars
	var $version = '1.0.0';

	/*
	*  __construct
	*
	*  A dummy constructor to ensure Count Visitor WP is only initialized once
	*
	*  @type	function
	*  @date	04/12/18
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	function __construct() {

		/* Do nothing here */

	}

	/*
	*  initialize
	*
	*  The real constructor to initialize Count Visitor WP
	*
	*  @type	function
	*  @date	04/12/18
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	function initialize() {

		// vars
		$this->settings = array(

			// info
			'name' 		=> __( 'Count Visitor WP', 'cvwp' ),
			'version' 	=> $this->version,

			// path
			'file' 		=> __FILE__,
			'basename' 	=> plugin_basename( __FILE__ ),
			'path' 		=> plugin_dir_path( __FILE__ ),
			'dir' 		=> plugin_dir_url( __FILE__ )

		);

		// defines
		define( 'COUNT_VISITOR_WP_VERSION', $this->version );

		define( 'COUNT_VISITOR_WP_BASE_DIR', $this->settings['dir'] );
		define( 'COUNT_VISITOR_WP_CORE_DIR', $this->settings['dir'] . 'core/' );

		define( 'COUNT_VISITOR_WP_BASE_PATH', $this->settings['path'] );
		define( 'COUNT_VISITOR_WP_CORE_PATH', $this->settings['path'] . 'core/' );

		// set text domain
		load_textdomain( 'cvwp', COUNT_VISITOR_WP_BASE_PATH . 'lang/cvwp-' . get_locale() . '.mo' );

		// includes
		include( 'core/helpers.php' );
		include( 'core/widgets.php' );
		include( 'core/shortcodes.php' );

	}

	/*
	*  register_styles
	*
	*  @type	function
	*  @date	04/12/18
	*  @since	1.0.0
	*/

	function register_styles() {

		// register
		wp_register_style( 'count-visitor-wp', COUNT_VISITOR_WP_BASE_DIR . 'assets/css/main.min.css', array(), COUNT_VISITOR_WP_VERSION );

		// init
		wp_enqueue_style( 'count-visitor-wp' );

	}

	/*
	*  register_scripts
	*
	*  @type	function
	*  @date	04/12/18
	*  @since	1.0.0
	*/

	function register_scripts() {

		// register
		wp_register_script( 'count-visitor-wp', COUNT_VISITOR_WP_BASE_DIR . 'assets/js/base.min.js', array( 'jquery' ), COUNT_VISITOR_WP_VERSION );

		// init
		wp_enqueue_script( 'count-visitor-wp' );

	}

}

/*
*  count_visitor_wp
*
*  The main function responsible for returning the one true count_visitor_wp Instance to functions everywhere.
*  Use this function like you would a global variable, except without needing to declare the global.
*
*  Example: <?php $count_visitor_wp = count_visitor_wp(); ?>
*
*  @type	function
*  @date	04/12/18
*  @since	1.0.0
*
*  @param	N/A
*  @return	(object)
*/

function count_visitor_wp() {

	global $count_visitor_wp;

	if( ! isset($count_visitor_wp) ) {

		$count_visitor_wp = new count_visitor_wp();

		$count_visitor_wp->initialize();

	}

	return $count_visitor_wp;

}

// initialize
count_visitor_wp();


endif; // class_exists check