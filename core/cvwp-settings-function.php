<?php
/**
 * Settings
 *
 * @package Count Visitors WP
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $cvwp_version;
$cvwp_version = count_visitors_wp()->version;

/**
 * Check database update.
 *
 * @since 1.0.0
 */
function cvwp_update_db_check() {
	global $cvwp_version;
	if ( get_site_option( 'cvwp_version' ) !== $cvwp_version ) {
		cvwp_install();
	}
}
add_action( 'plugins_loaded', 'cvwp_update_db_check' );

/**
 * Initialize installation of the plugin.
 *
 * @since 1.0.0
 */
function cvwp_install() {
	global $wpdb;
	global $cvwp_version;

	// set db table names.
	$cvwp_config_tbl = $wpdb->prefix . 'cvwp_config';

	// check collate.
	$charset_collate = $wpdb->get_charset_collate();
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}

	// add version on database.
	update_option( 'cvwp_version', $cvwp_version );
}

/**
 * Install plugin data.
 *
 * @since 1.0.0
 */
function cvwp_install_data() {
	global $wpdb;

	// config defaults.
	$cvwp_config = $wpdb->prefix . 'cvwp_config';

	$welcome_name = 'Count Visitors WP';
	$welcome_text = 'Congratulations, you just completed the installation!';

	$wpdb->insert(
		$cvwp_config,
		array(
			'time'         => current_time( 'mysql' ),
			'config_name'  => $welcome_name,
			'config_value' => $welcome_text,
		)
	);
}
