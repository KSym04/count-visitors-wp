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

	// cvwp_config table.
	$wpdb->query(
		$wpdb->prepare(
			"
			CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cvwp_config (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			`time` datetime DEFAULT %s NOT NULL,
			`config_name` varchar(255) NOT NULL,
			`config_value` varchar(255) NOT NULL,
			PRIMARY KEY (id)) DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate};",
			array( '0000-00-00 00:00:00' )
		)
	);

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

	// title.
	$check_welcome_title = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}cvwp_config where config_name = %s", 'welcome_title' ) );
	if ( 0 === $check_welcome_title ) {
		$welcome_title = 'Count Visitors WP';
		$wpdb->insert(
			$wpdb->prefix . 'cvwp_config',
			array(
				'time'         => current_time( 'mysql' ),
				'config_name'  => 'welcome_title',
				'config_value' => esc_sql( $welcome_title ),
			)
		);
	}

	// message.
	$check_welcome_msg = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}cvwp_config where config_name = %s", 'welcome_msg' ) );
	if ( 0 === $check_welcome_msg ) {
		$welcome_msg = 'Congratulations, you just completed the installation!';
		$wpdb->insert(
			$wpdb->prefix . 'cvwp_config',
			array(
				'time'         => current_time( 'mysql' ),
				'config_name'  => 'welcome_msg',
				'config_value' => esc_sql( $welcome_msg ),
			)
		);
	}
}
