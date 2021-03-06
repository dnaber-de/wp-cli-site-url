<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: WP-CLI Site URL
 * Description: Provides WP-CLI command for editing a site URL. See <code>$ wp site_url</code> for command descriptions.
 * Version:     dev-master
 * Author:      Inpsyde GmbH
 * Author URL:  http://inpsyde.com
 * Network:     true
 */

namespace WpCliSiteUrl;

use WP_CLI;
use WpCliSiteUrl\WpCli\SiteUrl;

const VERSION = 'dev-master';

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
	is_readable( $autoload ) and require_once $autoload;

	WP_CLI::add_command( SiteUrl::COMMAND, new SiteUrl() );
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
