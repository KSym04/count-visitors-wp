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

	// labels.
	$cvwp_config       = $wpdb->prefix . 'cvwp_config';
	$cvwp_total_counts = $wpdb->prefix . 'cvwp_total_counts';

	// check collate.
	$charset_collate = $wpdb->get_charset_collate();
	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= "COLLATE $wpdb->collate";
		}
	}

	// set config db.
	$wpdb->query(
		$wpdb->prepare("
		CREATE TABLE IF NOT EXISTS `%s` (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		`config_name` varchar(255) NOT NULL,
		`config_value` varchar(255) NOT NULL
		UNIQUE KEY id (id)) %s;",
			$cvwp_total_counts,
			$charset_collate
		)
	);

	// set total counts db.
	$wpdb->query(
		$wpdb->prepare("
		CREATE TABLE IF NOT EXISTS `%s` (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		`post_id` varchar(255) NOT NULL,
		`post_count` int DEFAULT '0' NOT NULL
		UNIQUE KEY id (id)) %s;",
			$cvwp_total_counts,
			$charset_collate
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
