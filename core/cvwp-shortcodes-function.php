<?php
/**
 * Shortcodes
 *
 * @package Count Visitors WP
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode to display page visits count.
 *
 * @since 1.0.0
 *
 * @param  string $atts   Shortcode attributes.
 * @return string         Formatted HTML content.
 */
function please_count_visits_logic( $atts ) {
	$content = null;

	// parse attributes.
	$atts = shortcode_atts(
		array(
			'label' => '',
		), $atts, 'please_count_visits'
	);

	return $content;
}
add_shortcode( 'please_count_visits', 'please_count_visits_logic' );
