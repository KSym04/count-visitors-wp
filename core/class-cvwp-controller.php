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

		// check if counter exist.
		$results = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}cvwp_total_counts SET `visits_count` = visits_count + 1 WHERE `post_id` = %s LIMIT 1", $post_id ) );

		// if counter not exist.
		if ( 0 === $results ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}cvwp_total_counts ( post_id, visits_count ) VALUES( %s, 1)", $post_id ) );
		}
	}

}

global $cvwp_controller;
$cvwp_controller = new CVWP_Controller();
