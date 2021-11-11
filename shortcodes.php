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
			'title'   => "Today's Events",
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
					'value'   => Carbon::now()->setTimezone( 'America/Detroit' )->format( 'Y-m-d' ), // phpcs:ignore
					'type'    => 'DATE',
					'compare' => '=',
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
			$html .= '<a href="' . get_the_permalink() . '" class="text-gray-700 group no-underline">';
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
					'key'     => 'mu_livestream_start',
					'value'   => Carbon::now()->setTimezone( 'America/Detroit' )->format( 'Y-m-d' ), // phpcs:ignore
					'type'    => 'DATE',
					'compare' => '!=',
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
			$html .= '<a href="' . get_the_permalink() . '" class="text-gray-700 group no-underline block ">';

			$html .= '<div class="relative ">';
			$html .= '<div class="absolute inset-0 z-10 bg-gradient-to-t from-transparent to-transparent group-hover:from-black-40 flex items-center justify-center transition-all duration-150 ease-in-out">';
			$html .= '<svg class="transition-all duration-150 ease-in-out h-16 w-16 opacity-0 group-hover:opacity-100 fill-current text-gray-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path></svg>';
			$html .= '</div>';
			$html .= '<div class="relative">';
			$html .= '<img src="' . esc_url( get_field( 'mu_livestream_thumbnail', get_the_ID() )['url'] ) . '" class="rounded-t" />';
			$html .= '</div>';
			$html .= '</div>';

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
					'key'     => 'mu_livestream_archive_event_id',
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
			$html .= '<a href="' . get_the_permalink() . '" class="text-gray-700 group no-underline">';
			$html .= '<img src="' . esc_url( get_field( 'mu_livestream_thumbnail', get_the_ID() )['url'] ) . '" class="rounded-t" />';
			$html .= '<div class="bg-gray-100 px-6 py-4 rounded-b">';
			$html .= '<div class="text-xl font-semibold group-hover:underline">' . esc_attr( get_the_title() ) . '</div>';
			$html .= '<div class="text-sm uppercase font-medium mt-1">' . esc_attr( Carbon::parse( get_field( 'mu_livestream_end', get_the_ID() ) )->format( 'l, F j, Y' ) ) . '</div>';
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
