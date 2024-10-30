<?php
/**
 * Plugin Name: Custom Post Type Layout
 * Plugin URI: https://viitorcloud.com/
 * Description: This simple plugin allows to generate shortcode for any post type
 * Version:2.0.0
 * Author: VIITORCLOUD
 * Author URI: https://viitorcloud.com/
 * License: GPL2
 *
 * @package Custom_post_type_layout
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

if ( ! defined( 'Custom_Post_Type_Layout' ) ) {
	define( 'Custom_Post_Type_Layout', __DIR__ ); // plugin dir.
}

if ( ! defined( 'Custom_Post_Type_Layout_URL' ) ) {
	define( 'Custom_Post_Type_Layout_URL', plugin_dir_url( __FILE__ ) ); // plugin url.
}
if ( ! defined( 'Custom_Post_Type_Layout_IMG_URL' ) ) {
	define( 'Custom_Post_Type_Layout_IMG_URL', Custom_Post_Type_Layout . '/images' ); // plugin images url.
}
if ( ! defined( 'Custom_Post_Type_Layout_TEXT_DOMAIN' ) ) {
	define( 'Custom_Post_Type_Layout_TEXT_DOMAIN', 'vc_cpt_layout' ); // text domain for doing language translation.
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Custom_Post_Type_Layout
 * @since 2.0.0
 */
load_plugin_textdomain( 'vc_cpt_layout', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
/**
 * Activation hook
 *
 * Register plugin activation hook.
 *
 * @package Custom_Post_Type_Layout
 *@since 2.0.0
 */
register_activation_hook( __FILE__, 'VC_Custom_Post_Type_Layout_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 *
 * @package Custom_Post_Type_Layout
 * @since 2.0.0
 */
register_deactivation_hook( __FILE__, 'VC_Custom_Post_Type_Layout_uninstall' );

/**
 * Plugin Setup Activation hook call back
 *
 * Initial setup of the plugin setting default options
 * and database tables creations.
 *
 * @package Custom_Post_Type_Layout
 * @since 2.0.0
 */
function VC_Custom_Post_Type_Layout_install() {

	global $wpdb;
	$primarykey = get_option( 'cbw_primary_key' );
	if ( empty( $primarykey ) ) {
		update_option( 'cbw_primary_key', '1' );
	}
}
/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 *
 * @package Custom_Post_Type_Layout
 * @since 2.0.0
 */
function VC_Custom_Post_Type_Layout_uninstall() {

	global $wpdb;
}

/**
 * Includes
 *
 * Includes all the needed files for plugin
 *
 * @package Custom_Post_Type_Layout
 * @since 2.0.0
 */

// require options file.
require 'custom-post-layout-options.php';
