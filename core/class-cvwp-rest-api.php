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
	public $rest_bases = array( '/view' );

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
		foreach ( $this->rest_bases as $rest_base ) {
			register_rest_route(
				$this->namespace, $rest_base . '/(?P<id>[\d]+)', array(
					'methods'  => 'GET',
					'callback' => array( $this, str_replace( '/', '', $rest_base ) . '_counts' ),
				)
			);
		}
	}

	/**
	 * View post counts.
	 *
	 * @since 1.0.0
	 * @param object $request  Request object.
	 */
	public function view_counts( $request ) {
		$id = $request->get_param( 'id' );

		$json_data = array(
			'success' => true,
			'message' => $id,
		);

		return wp_send_json( $json_data );
	}

}

global $cvwp_rest_api;
$cvwp_rest_api = new CVWP_Rest_Api();
