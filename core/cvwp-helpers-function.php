<?php
/**
 * Helpers
 *
 * @package Count Visitors WP
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get visitor original IP address.
 *
 * @since 1.0.0
 *
 * @param  bool $validate   True if wants some validations.
 * @return string           String formatted IP address of user.
 */
function count_visitors_get_ip( $validate = true ) {
	// ipkeys.
	$ipkeys = [
		'REMOTE_ADDR',
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
	];

	$ip = [];
	foreach ( $ipkeys as $keyword ) {
		if ( ! empty( $_SERVER[ $keyword ] ) ) {
			$server_keyword = sanitize_text_field( wp_unslash( $_SERVER[ $keyword ] ) );
			if ( $validate ) {
				if ( count_visitors_validate_ip( $server_keyword ) ) {
					$ip[] = $server_keyword;
				}
			} else {
				$ip[] = $server_keyword;
			}
		}
	}

	$ip = ( empty( $ip ) ? 'Unknown' : implode( ', ', $ip ) );
	return $ip;
}

/**
 * Validate IP address.
 *
 * @since 1.0.0
 *
 * @param   string $ip     String IP address of user.
 * @return  bool            Return status validity of IP address.
 */
function count_visitors_validate_ip( $ip ) {
	if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
		return true;
	} else {
		return false;
	}
}
