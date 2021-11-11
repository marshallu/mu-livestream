<?php
/**
 * Shortcodes for the MU Livestream plugin
 *
 * @package MU Livestream
 */

require WP_PLUGIN_DIR . '/mu-livestream/vendor/autoload.php';

use Carbon\Carbon;


/**
 * Shortcode to display upcoming livestreams
 *
 * @param array  $atts Shortcode attributes.
 * @param string $content Shortcode content.
 *
 * @return string Shortcode output.
 */
function mu_livestream_live( $atts, $content = null ) {
	$data = shortcode_atts(
		array(
			'ids'     => false,
			'title'   => 'Live Now',
			'channel' => false,
		),
		$atts
	);

	$livestream_query = new WP_Query(
		array(
			'post_type'      => 'mu-livestream',
			'posts_per_page' => 12,
			'meta_key'       => 'mu_livestream_start', // phpcs:ignore
			'orderby'        => 'meta_value',
			'meta_type'      => 'DATETIME',
			'order'          => 'ASC',
			'meta_query'     => array( // phpcs:ignore
				'relation' => 'AND',
				array(
					'key'     => 'mu_livestream_end',
					'value'   => Carbon::now()->setTimezone( 'America/Detroit' )->format( 'Y-m-d H:i:s' ), // phpcs:ignore
					'type'    => 'DATETIME',
					'compare' => '>=',
				),
				array(
					'key'     => 'mu_livestream_start',
					'value'   => Carbon::now()->setTimezone( 'America/Detroit' )->format( 'Y-m-d H:i:s' ), // phpcs:ignore
					'type'    => 'DATETIME',
					'compare' => '<=',
				),
				array(
					'key'     => 'mu_livestream_live_event_id',
					'compare' => 'EXISTS',
				),
			),
		)
	);

	$html = '<div>';
	if ( $livestream_query->have_posts() ) {
		$html .= '<h2>' . esc_attr( $data['title'] ) . '</h2>';
		$html .= '<div class="flex flex-wrap lg:-mx-6">';

		while ( $livestream_query->have_posts() ) {
			$livestream_query->the_post();
			$html .= '<div class="w-full lg:w-1/3 lg:px-6 mb-6 flex">';
			$html .= '<div>';
			$html .= '<a href="https://vimeo.com/event/' . esc_attr( get_field( 'mu_livestream_live_event_id', get_the_ID() ) ) . '" class="text-gray-700 group no-underline">';
			$html .= '<img src="' . esc_url( get_field( 'mu_livestream_thumbnail', get_the_ID() )['url'] ) . '" class="rounded-t" />';
			$html .= '<div class="bg-gray-100 px-6 py-4 rounded-b">';
			$html .= '<div class="text-xl font-semibold group-hover:underline">' . esc_attr( get_the_title() ) . '</div>';
			$html .= '<div class="text-sm uppercase font-medium mt-1">' . esc_attr( Carbon::parse( get_field( 'mu_livestream_start', get_the_ID() ) )->format( 'l, F j, Y g:i A' ) ) . '</div>';
			$html .= '</div>';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
	}

	$html .= '</div>';
	return $html;
}
add_shortcode( 'mu_livestream_live', 'mu_livestream_live' );

/**
 * Shortcode to display upcoming livestreams
 *
 * @param array  $atts Shortcode attributes.
 * @param string $content Shortcode content.
 *
 * @return string Shortcode output.
 */
function mu_livestream_upcoming( $atts, $content = null ) {
	$data = shortcode_atts(
		array(
			'ids'     => false,
			'title'   => 'Upcoming Events',
			'channel' => false,
		),
		$atts
	);

	$livestream_query = new WP_Query(
		array(
			'post_type'      => 'mu-livestream',
			'posts_per_page' => 12,
			'meta_key'       => 'mu_livestream_start', // phpcs:ignore
			'orderby'        => 'meta_value',
			'meta_type'      => 'DATETIME',
			'order'          => 'ASC',
			'meta_query'     => array( // phpcs:ignore
				'relation' => 'AND',
				array(
					'key'     => 'mu_livestream_start',
					'value'   => Carbon::now()->setTimezone( 'America/Detroit' )->format( 'Y-m-d H:i:s' ), // phpcs:ignore
					'type'    => 'DATETIME',
					'compare' => '>=',
				),
				array(
					'key'     => 'mu_livestream_live_event_id',
					'compare' => 'EXISTS',
				),
			),
		)
	);

	$html = '<div>';
	if ( $livestream_query->have_posts() ) {
		$html .= '<h2>' . esc_attr( $data['title'] ) . '</h2>';
		$html .= '<div class="flex flex-wrap lg:-mx-6">';

		while ( $livestream_query->have_posts() ) {
			$livestream_query->the_post();
			$html .= '<div class="w-full lg:w-1/3 lg:px-6 mb-6 flex">';
			$html .= '<div>';
			$html .= '<a href="https://vimeo.com/event/' . esc_attr( get_field( 'mu_livestream_live_event_id', get_the_ID() ) ) . '" class="text-gray-700 group no-underline">';
			$html .= '<img src="' . esc_url( get_field( 'mu_livestream_thumbnail', get_the_ID() )['url'] ) . '" class="rounded-t" />';
			$html .= '<div class="bg-gray-100 px-6 py-4 rounded-b">';
			$html .= '<div class="text-xl font-semibold group-hover:underline">' . esc_attr( get_the_title() ) . '</div>';
			$html .= '<div class="text-sm uppercase font-medium mt-1">' . esc_attr( Carbon::parse( get_field( 'mu_livestream_start', get_the_ID() ) )->format( 'l, F j, Y g:i A' ) ) . '</div>';
			$html .= '</div>';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
	}

	$html .= '</div>';
	return $html;
}
add_shortcode( 'mu_livestream_upcoming', 'mu_livestream_upcoming' );

/**
 * Shortcode to display past livestreams
 *
 * @param array  $atts Shortcode attributes.
 * @param string $content Shortcode content.
 *
 * @return string Shortcode output.
 */
function mu_livestream_past( $atts, $content = null ) {
	$data = shortcode_atts(
		array(
			'ids'     => false,
			'title'   => 'Past Events',
			'channel' => false,
		),
		$atts
	);

	$livestream_query = new WP_Query(
		array(
			'post_type'      => 'mu-livestream',
			'posts_per_page' => 12,
			'meta_key'       => 'mu_livestream_start', // phpcs:ignore
			'orderby'        => 'meta_value',
			'meta_type'      => 'DATETIME',
			'order'          => 'DESC',
			'meta_query'     => array( // phpcs:ignore
				'reation' => 'AND',
				array(
					'key'     => 'mu_livestream_end',
					'value'   => Carbon::now()->setTimezone( 'America/Detroit' )->format( 'Y-m-d H:i:s' ), // phpcs:ignore
					'type'    => 'DATETIME',
					'compare' => '<=',
				),
				array(
					'key'     => 'mu_livestream_archive_url',
					'value'   => '',
					'compare' => '!=',
				),
			),
		)
	);

	$html  = '<div>';
	$html .= '<h2>' . esc_attr( $data['title'] ) . '</h2>';
	if ( $livestream_query->have_posts() ) {
		$html .= '<div class="flex flex-wrap lg:-mx-6">';

		while ( $livestream_query->have_posts() ) {
			$livestream_query->the_post();
			$html .= '<div class="w-full lg:w-1/3 lg:px-6 mb-6">';
			$html .= '<div>';
			$html .= '<a href="' . esc_url( get_field( 'mu_livestream_archive_url', get_the_ID() ) ) . '" class="text-gray-700 group no-underline">';
			$html .= '<img src="' . esc_url( get_field( 'mu_livestream_thumbnail', get_the_ID() )['url'] ) . '" class="rounded-t" />';
			$html .= '<div class="bg-gray-100 px-6 py-4 rounded-b">';
			$html .= '<div class="text-xl font-semibold group-hover:underline">' . esc_attr( get_the_title() ) . '</div>';
			$html .= '<div class="text-sm uppercase font-medium mt-1">' . esc_attr( Carbon::parse( get_field( 'mu_livestream_start', get_the_ID() ) )->diffForHumans() ) . '</div>';
			$html .= '</div>';
			$html .= '</a>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
	} else {
		$html .= '<p>No past livestreams found.</p>';
	}
	$html .= '</div>';
	return $html;
}
add_shortcode( 'mu_livestream_past', 'mu_livestream_past' );
