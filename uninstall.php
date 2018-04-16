<?php
/**
 * Uninstall Plugin
 *
 * @package Count Visitor WP
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

global $wpdb;

// plugin options.
delete_option( 'cvwp_version' );

// plugin databases.
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'cvwp_config' );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'cvwp_total_counts' );
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'cvwp_sessions' );
