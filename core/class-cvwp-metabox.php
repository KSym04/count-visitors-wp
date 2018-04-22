<?php
/**
 * CVWP Metabox
 *
 * @package CountVisitorsWP/Classes
 * @version 1.0.0
 */

namespace CountVisitorsWP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CVWP_MetaBox class.
 */
class CVWP_MetaBox {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'load-post.php', [ $this, 'init_metabox' ] );
			add_action( 'load-post-new.php', [ $this, 'init_metabox' ] );
		}
	}

	/**
	 * Meta box initialization.
	 *
	 * @since 1.0.0
	 */
	public function init_metabox() {
		add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
		add_action( 'save_post', [ $this, 'save_metabox' ], 10, 2 );
	}

	/**
	 * Adds the meta box.
	 *
	 * @since 1.0.0
	 */
	public function add_metabox( $post_type = [] ) {
		add_meta_box(
			'cvwp-meta-box',
			__( 'Visit Counts', 'cvwp' ),
			[ $this, 'render_metabox' ],
			$post_type, // where to appear? Example: post, page and etc.
			'side',
			'high'
		);
	}

	/**
	 * Renders the meta box.
	 *
	 * @since 1.0.0
	 * @param WP_Post $post    Post object.
	 */
	public function render_metabox( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'cvwp_nonce_action', 'cvwp_nonce' );
		echo do_shortcode( '[please_count_visits]' );
	}

	/**
	 * Handles saving the meta box.
	 *
	 * @since 1.0.0
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @return null
	 */
	public function save_metabox( $post_id, $post ) {
		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
	}

}

new CVWP_MetaBox();
