<?php
/**
 * Plugin Name: Count Visitors WP
 * Description: WordPress plugin utility used for counting user page visits on your WordPress site pages and post.
 * Version: 1.0.0
 * Author: Eteam.dk
 * Author URI: http://www.eteam.dk/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Plugin URI: http://www.eteam.dk/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Copyright: Eteam.dk
 * Text Domain: cvwp
 * Domain Path: /lang
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package CountVisitorsWP
 */

/**
 * Copyright Eteam.dk
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1335, USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Count_Visitors_WP' ) ) :

	/**
	 * Count_Visitors_WP class
	 */
	class Count_Visitors_WP {

		/**
		 * CountVisitorsWP version.
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 *  __construct
		 *
		 *  A dummy constructor to ensure Count Visitors WP is only initialized once
		 *
		 *  @date    04/12/18
		 *  @since   1.0.0
		 */
		public function __construct() {
			// Do nothing here.
		}

		/**
		 *  Initialize.
		 *
		 *  The real constructor to initialize Count Visitors WP
		 *
		 *  @type    function
		 *  @date    04/12/18
		 *  @since   1.0.0
		 */
		public function initialize() {
			// vars.
			$this->settings = [

				// info.
				'name'       => __( 'Count Visitors WP', 'cvwp' ),
				'version'    => $this->version,

				// parameters.
				'menu_slug'  => 'cvwp',
				'permission' => 'manage_options',

				// path.
				'file'       => __FILE__,
				'basename'   => plugin_basename( __FILE__ ),
				'path'       => plugin_dir_path( __FILE__ ),
				'dir'        => plugin_dir_url( __FILE__ ),

			];

			// defines.
			define( 'COUNT_VISITORS_WP_VERSION', $this->version );

			define( 'COUNT_VISITORS_WP_BASE_DIR', $this->settings['dir'] );
			define( 'COUNT_VISITORS_WP_CORE_DIR', $this->settings['dir'] . 'core/' );

			define( 'COUNT_VISITORS_WP_BASE_PATH', $this->settings['path'] );
			define( 'COUNT_VISITORS_WP_CORE_PATH', $this->settings['path'] . 'core/' );

			// set text domain.
			load_textdomain( 'cvwp', COUNT_VISITORS_WP_BASE_PATH . 'lang/cvwp-' . get_locale() . '.mo' );

			// functions.
			include 'core/cvwp-settings-function.php';
			include 'core/cvwp-shortcodes-function.php';
			include 'core/cvwp-helpers-function.php';
			include 'core/cvwp-admin-function.php';

			// classes.
			require_once 'core/class-cvwp-metabox.php';
			require_once 'core/class-cvwp-rest-api.php';
			require_once 'core/class-cvwp-controller.php';

			// setup admin page.
			add_action( 'admin_menu', [ $this, 'admin_page_url' ] );

			if ( ! is_admin() ) {
				add_action( 'wp_footer', [ $this, 'register_post_counter_field' ] );
			}
		}

		/**
		 *  Register post counter field.
		 *
		 *  Check the field if exist and counter is disabled on specific post or page
		 *
		 *  @type    function
		 *  @date    04/14/18
		 *  @since   1.0.0
		 */
		public function register_post_counter_field() {
			global $post, $cvwp_controller;
			return $cvwp_controller->register_counter( $post->ID );
		}

		/**
		 *  Register styles.
		 *
		 *  @type    function
		 *  @date    04/12/18
		 *  @since   1.0.0
		 */
		public function register_styles() {
			wp_register_style( 'count-visitor-wp', COUNT_VISITORS_WP_BASE_DIR . 'assets/css/main.min.css', [], COUNT_VISITORS_WP_VERSION );
			wp_enqueue_style( 'count-visitor-wp' );
		}

		/**
		 *  Register scripts.
		 *
		 *  @type    function
		 *  @date    04/12/18
		 *  @since   1.0.0
		 */
		public function register_scripts() {
			wp_register_script( 'count-visitor-wp', COUNT_VISITORS_WP_BASE_DIR . 'assets/js/base.min.js', [ 'jquery' ], COUNT_VISITORS_WP_VERSION );
			wp_enqueue_script( 'count-visitor-wp' );
		}

		/**
		 *  Admin page init.
		 *
		 *  @type    function
		 *  @date    04/22/18
		 *  @since   1.0.1
		 */
		public function admin_page_url() {
			add_options_page(
				esc_html__( 'Count Visitors WP / Settings', 'cvwp' ),
				esc_html__( 'Count Visitors WP', 'cvwp' ),
				$this->settings['permission'],
				$this->settings['menu_slug'],
				[ $this, 'admin_settings_page' ]
			);

			add_filter( 'plugin_action_links_' . $this->settings['basename'], [ $this, 'admin_settings_url' ] );
		}

		/**
		 *  Admin page settings.
		 *
		 *  @type    function
		 *  @date    04/22/18
		 *  @since   1.0.1
		 */
		public function admin_settings_page() {
			$this->admin_save_settings();
			include $this->settings['path'] . 'admin/admin.php';
		}

		/**
		 *  Admin page url settings.
		 *
		 *  @type    function
		 *  @date    04/22/18
		 *  @since   1.0.1
		 *  @param   string $url   Admin page link inside dashboard.
		 */
		public function admin_settings_url( $url ) {
			$settings_url  = menu_page_url( $this->settings['menu_slug'], false );
			$settings_link = '<a href="{$settings_url}">' . esc_html__( 'Settings', 'cvwp' ) . '</a>';
			array_unshift( $url, $settings_link );

			return $url;
		}

		/**
		 *  Admin page save settings
		 *
		 *  @type    function
		 *  @date    04/22/18
		 *  @since   1.0.1
		 */
		private function admin_save_settings() {
			// check user capability.
			if ( ! current_user_can( $this->settings['permission'] ) ) {
				return;
			}
		}

	}

	/**
	 *  CountVisitorsWP object
	 *
	 *  The main function responsible for returning the one true count_visitors_wp Instance to functions everywhere.
	 *  Use this function like you would a global variable, except without needing to declare the global.
	 *
	 *  Example: <?php $count_visitors_wp = count_visitors_wp(); ?>
	 *
	 *  @type    function
	 *  @date    04/12/18
	 *  @since   1.0.0
	 *  @return  object
	 */
	function count_visitors_wp() {
		global $count_visitors_wp;
		if ( ! isset( $count_visitors_wp ) ) {
			$count_visitors_wp = new Count_Visitors_WP();
			$count_visitors_wp->initialize();
		}
		return $count_visitors_wp;
	}

	// initialize.
	count_visitors_wp();

endif;

// activation hooks.
register_activation_hook( __FILE__, 'cvwp_install' );
register_activation_hook( __FILE__, 'cvwp_install_data' );
