<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: WP-CLI Site URL
 * Description: Provides WP-CLI command for editing a site URL. See <code>$ wp site_url</code> for command descriptions.
 * Version:     1.0.0
 * Author:      Inpsyde GmbH
 * Author URL:  http://inpsyde.com
 * Network:     true
 */

namespace WpCliSiteUrl;

use WP_CLI;

add_action( 'wp_loaded', __NAMESPACE__ . '\init' );

/**
 * Register the WP-CLI hook
 *
 * @wp-hook wp_loaded
 */
function init() {

	if ( ! is_wp_cli() )
		return;

	$autoload = __DIR__ . '/vendor/autoload.php';
	if ( file_exists( $autoload ) && is_readable( $autoload ) )
		require_once $autoload;

	WP_CLI::add_command( WpCli\SiteUrl::COMMAND, WpCli\SiteUrl::class );
}

/**
 * Is WP CLI available
 *
 * @return bool
 */
function is_wp_cli() {

	return
		defined( 'WP_CLI' )
		&& WP_CLI
		&& class_exists( 'WP_CLI_Command' );
}
