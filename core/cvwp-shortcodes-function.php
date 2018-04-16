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
	global $wpdb, $post;

	// parse attributes.
	$a = shortcode_atts(
		array(
			'label' => '',
		), $atts, 'please_count_visits'
	);

	$count_visits = number_format( $wpdb->get_var( $wpdb->prepare( "SELECT `visits_count` FROM {$wpdb->prefix}cvwp_total_counts WHERE `post_id` = %s LIMIT 1", $post->ID ) ) );
	return sprintf( '<div class="count-visits-box"><span class="count-visits__label">%1$s</span> <span class="count-visits__counts">%2$s</span></div>', $a['label'], $count_visits );
}
add_shortcode( 'please_count_visits', 'please_count_visits_logic' );
