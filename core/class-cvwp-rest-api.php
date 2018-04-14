<?php
/**
 * CVWP Rest API
 *
 * @package CountVisitorsWP/Classes
 * @version 1.0.0
 */

namespace CountVisitorsWP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CVWP_Rest_Api class.
 */
class CVWP_Rest_Api {

	/**
	 * Register WP API namespace.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $namespace = 'cvwp/v2';

	/**
	 * Register WP API bases.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public $rest_bases = array( '/increment', '/view' );

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'initialize' ) );
	}

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		foreach ( $this->rest_bases as $rb ) {
			register_rest_route(
				$this->namespace, $rb . '/(?P<post_ids>([a-zA-Z0-9_]\,?)+)', array(
					'methods'  => 'GET',
					'callback' => array( $this, str_replace( '/', '', $rb ) . '_stats' ),
				)
			);
		}
	}

	/**
	 * Update post counts.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request    Request object.
	 */
	public function update_counts( WP_REST_Request $request ) {
		// code here...
	}

	/**
	 * Get post counts.
	 *
	 * @since 1.0.0
	 * @param WP_REST_Request $request    Request object.
	 */
	public function get_counts( WP_REST_Request $request ) {
		// code here...
	}

}

global $cvwp_rest_api;
$cvwp_rest_api = new CVWP_Rest_Api();
