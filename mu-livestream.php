<?php
/**
 * MU Livestream
 *
 * This plugin was built to allow to display livestreams from Vimeo on the MU Livestream site.
 *
 * @package  MU Livestream
 *
 * Plugin Name:  MU Livestream
 * Plugin URI: https://www.marshall.edu
 * Description: This plugin was built to allow to display livestreams from Vimeo on the MU Livestream site.
 * Version: 1.0
 * Author: Christopher McComas
 */

use Carbon\Carbon;

// require WP_PLUGIN_DIR . '/mu-livestream/vendor/autoload.php';

// use Carbon\Carbon;

require plugin_dir_path( __FILE__ ) . '/acf-fields.php';
// require plugin_dir_path( __FILE__ ) . '/display-custom.php';
// require plugin_dir_path( __FILE__ ) . '/editor.php';
require plugin_dir_path( __FILE__ ) . '/shortcodes.php';

if ( ! class_exists( 'ACF' ) ) {
	return new WP_Error( 'broke', __( 'Advanced Custom Fields is required for this plugin.', 'my_textdomain' ) );
}

/**
 * Register a custom post type called 'mu-livestream'
 *
 * @see get_post_type_labels() for label keys.
 */
function mu_livestream_post_type() {
	$labels = array(
		'name'                  => _x( 'Videos', 'Post type general name', 'mu-livestream' ),
		'singular_name'         => _x( 'Video', 'Post type singular name', 'mu-livestream' ),
		'menu_name'             => _x( 'Videos', 'Admin Menu text', 'mu-livestream' ),
		'name_admin_bar'        => _x( 'Video', 'Add New on Toolbar', 'mu-livestream' ),
		'add_new'               => __( 'Add New', 'mu-livestream' ),
		'add_new_item'          => __( 'Add New Video', 'mu-livestream' ),
		'new_item'              => __( 'New Video', 'mu-livestream' ),
		'edit_item'             => __( 'Edit Video', 'mu-livestream' ),
		'view_item'             => __( 'View Video', 'mu-livestream' ),
		'all_items'             => __( 'All Videos', 'mu-livestream' ),
		'search_items'          => __( 'Search Videos', 'mu-livestream' ),
		'parent_item_colon'     => __( 'Parent Videos:', 'mu-livestream' ),
		'not_found'             => __( 'No Videos found.', 'mu-livestream' ),
		'not_found_in_trash'    => __( 'No Videos found in Trash.', 'mu-livestream' ),
		'featured_image'        => _x( 'Video Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'mu-livestream' ),
		'set_featured_image'    => _x( 'Set image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'mu-livestream' ),
		'remove_featured_image' => _x( 'Remove image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'mu-livestream' ),
		'use_featured_image'    => _x( 'Use as image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'mu-livestream' ),
		'archives'              => _x( 'Video archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'mu-livestream' ),
		'insert_into_item'      => _x( 'Insert into Video', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'mu-livestream' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Video', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'mu-livestream' ),
		'filter_items_list'     => _x( 'Filter Videos list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4', 'mu-livestream' ),
		'items_list_navigation' => _x( 'Videos list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4', 'mu-livestream' ),
		'items_list'            => _x( 'Videos list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4', 'mu-livestream' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'livestream' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'custom-fields', 'page-attributes', 'revisions' ),
		'taxonomies'         => array( 'channel' ),
		'menu_icon'          => 'dashicons-video-alt3',
	);

	register_post_type( 'mu-livestream', $args );
}

/**
 * Add custom channel taxonomy.
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
function mu_add_channel_taxonomy() {

	$labels = array(
		'name'              => _x( 'Channels', 'taxonomy general name', 'mu-livestream' ),
		'singular_name'     => _x( 'Channel', 'taxonomy singular name', 'mu-livestream' ),
		'search_items'      => __( 'Search Channels', 'mu-livestream' ),
		'all_items'         => __( 'All Channels', 'mu-livestream' ),
		'parent_item'       => __( 'Parent Channel', 'mu-livestream' ),
		'parent_item_colon' => __( 'Parent Channel:', 'mu-livestream' ),
		'edit_item'         => __( 'Edit Channel', 'mu-livestream' ),
		'update_item'       => __( 'Update Channel', 'mu-livestream' ),
		'add_new_item'      => __( 'Add New Channel', 'mu-livestream' ),
		'new_item_name'     => __( 'New Channel Name', 'mu-livestream' ),
		'menu_name'         => __( 'All Channels', 'mu-livestream' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'channel' ),
	);

	register_taxonomy( 'channel', array( 'mu-livestream' ), $args );
}

/**
 * Flush rewrites whenever the plugin is activated.
 */
function mu_livestream_activate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mu_livestream_activate' );

/**
 * Flush rewrites whenever the plugin is deactivated, also unregister 'mu-livestream' post type and 'channel' taxonomy.
 */
function mu_livestream_deactivate() {
	unregister_post_type( 'mu-livestream' );
	unregister_taxonomy( 'channel' );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mu_livestream_deactivate' );

/**
 * Redirect any requests to channel taxonomoy pages to homepage.
 */
function mu_livestream_redirect_channel_taxonomy() {
	if ( is_tax( 'channel' ) ) {
		wp_redirect( get_site_url() );
		exit();
	}
}
add_action( 'template_redirect', 'mu_livestream_redirect_channel_taxonomy' );

/**
 * Remove YoastSEO metaboxes from Profiles
 */
function remove_yoast_metabox_videos() {
	remove_meta_box( 'wpseo_meta', 'mu-livestream', 'normal' );
}
add_action( 'add_meta_boxes', 'remove_yoast_metabox_videos', 11 );

/**
 * Allow for YouTube and LiveStream to use wp_safe_redirect
 *
 * @param array $hosts The array of default hosts allowed.
 * @return array
 */
function mu_livestream_allowed_redirect_hosts( $hosts ) {
	$safe_hosts = array(
		'www.youtube.com',
		'youtube.com',
		'www.vimeo.com',
		'vimeo.com',
	);
	return array_merge( $hosts, $safe_hosts );
};
add_filter( 'allowed_redirect_hosts', 'mu_livestream_allowed_redirect_hosts' );

/**
 * Redirect attachment pages
 */
function mu_livestream_redirect() {
	if ( is_singular( 'mu-livestream' ) ) {
		global $post;

		if ( Carbon::parse( get_field( 'mu_livestream_end', $post->ID ) ) < Carbon::now() && get_field( 'mu_livestream_archive_url', $post->ID ) ) {
			wp_redirect( esc_url( get_field( 'mu_livestream_archive_url', $post->ID ) ), 301 );
			exit;
		} else {
			wp_redirect( esc_url( 'https://vimeo.com/event/' . get_field( 'mu_livestream_live_event_id', $post->ID ) ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'mu_livestream_redirect' );

// Custom columns, start date time/end date time

/**
 * Edit columns displayed in the Dashboard for Program Page post types
 *
 * @param array $columns The array of columns.
 * @return array
 */
function mu_edit_livestream_columns( $columns ) {
	unset( $columns['wpseo-score'] );
	unset( $columns['wpseo-score-readability'] );
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );
	unset( $columns['wpseo-links'] );
	unset( $columns['wpseo-linked'] );
	unset( $columns['date'] );
	unset( $columns['modified'] );
	$columns['mu_livestream_start_time'] = __( 'Start Time', 'your_text_domain' );
	$columns['mu_livestream_end_time']   = __( 'End Time', 'your_text_domain' );
	$columns['date']                     = 'Date';
	$columns['modified']                 = 'Modified';
	return $columns;
}
add_filter( 'manage_edit-mu-livestream_columns', 'mu_edit_livestream_columns' );

/**
 * Getting the data to display for each column.
 *
 * @param string  $column The string name of the column.
 * @param integer $post_id The integer Post ID.
 */
function mu_livestream_custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'mu_livestream_start_time':
			echo esc_attr( get_field( 'mu_livestream_start', $post_id ) );
			break;

		case 'mu_livestream_end_time':
			echo esc_attr( get_field( 'mu_livestream_end', $post_id ) );
			break;
	}
}
add_action( 'manage_mu-livestream_posts_custom_column', 'mu_livestream_custom_columns', 10, 2 );

add_action( 'init', 'mu_livestream_post_type' );
add_action( 'init', 'mu_add_channel_taxonomy' );
