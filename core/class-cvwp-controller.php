<?php
/**
 * Controller
 *
 * @package CountVisitorsWP/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CVWP_Controller class.
 */
class CVWP_Controller {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// nothing here.
	}

	/**
	 * Register counter.
	 *
	 * @since 1.0.0
	 * @param int $id  Post ID to be register.
	 */
	public function register_counter( $id ) {
		global $wpdb;

		// sanitize ID.
		$post_id = intval( $id );

		// get user ip.
		$user_ip = count_visitors_get_ip( true );

		// check and register a session first.
		$sessions = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}cvwp_sessions WHERE `post_id` = %s AND `ip` = %s AND (TIMESTAMPDIFF(HOUR, session_time, NOW()) <= 24) LIMIT 1", array( $post_id, $user_ip ) ) );
		if ( 0 === $sessions ) {
			// register session.
			$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}cvwp_sessions ( session_time, ip, post_id ) VALUES( %s, %s, %s )", array( current_time( 'mysql' ), $user_ip, $post_id ) ) );

			// update counter.
			$results = (int) $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}cvwp_total_counts SET `visits_count` = visits_count + 1 WHERE `post_id` = %s LIMIT 1", $post_id ) );
			if ( 0 === $results ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}cvwp_total_counts ( post_id, visits_count ) VALUES( %s, 1 )", $post_id ) );
			}
		}
	}

}

global $cvwp_controller;
$cvwp_controller = new CVWP_Controller();
