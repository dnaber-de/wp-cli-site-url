<?php # -*- coding: utf-8 -*-

namespace WpCliSiteUrl\WpCli;

use WP_CLI;

/**
 * Retrieve and update a URL of a site in the network. Updating the URL
 * of the main site in the network is not supported.
 *
 * @package WpCliSiteUrl\WpCli
 */
class SiteUrl {

	const COMMAND = 'site-url';

	/**
	 * Get the complete URL of a site by ID
	 *
	 * @synopsis <ID>
	 *
	 * @param array $args
	 * @param array $assoc_args
	 */
	public function get( array $args = [], array $assoc_args = [] ) {

		$site_id = (int) $args[ 0 ];
		$site    = get_blog_details( $site_id );
		if ( ! $site ) {
			WP_CLI::error( "A site with ID {$site_id} does not exist" );
		}

		// trailing-slash it, as URLs ends always with a trailing slash in context of the wp_blogs table
		WP_CLI::line( trailingslashit( $site->siteurl ) );
		exit( 0 );
	}

	/**
	 * Update the URL of a site
	 *
	 * @synopsis <ID> <NEW_URL> [--force]
	 *
	 * @param array $args
	 * @param array $assoc_args
	 */
	public function update( array $args = [], array $assoc_args = [] ) {

		$site_id = (int) $args[ 0 ];
		$site = get_blog_details( $site_id, TRUE );
		if ( ! $site ) {
			WP_CLI::error( "A site with ID {$site_id} does not exist" );
		}
		if ( !isset($assoc_args['force']) && is_main_site( $site_id ) ) {
			WP_CLI::error( "The given site is the main site of the network. Please use --force argument if you know what you're doing." );
		}
		$new_url = $args[ 1 ];
		if ( ! filter_var( $new_url, FILTER_VALIDATE_URL ) ) {
			WP_CLI::error( "{$new_url} is not a valid url" );
		}

		/**
		 * Parse the new URL components
		 */
		$url_components  = parse_url( $new_url );
		$existing_scheme = parse_url( $site->siteurl, PHP_URL_SCHEME );
		$scheme = isset( $url_components[ 'scheme' ] )
			? $url_components[ 'scheme' ]
			: $existing_scheme;
		$host = isset( $url_components[ 'host' ]  )
			? $url_components[ 'host' ]
			: '';
		$path = isset( $url_components[ 'path' ] )
			? trailingslashit(  $url_components[ 'path' ] )
			: '/';

		// WP core does not accept ports in the URL so we don't too
		$site_details             = get_object_vars( $site );
		$site_details[ 'domain' ] = $host;
		$site_details[ 'path' ]   = $path;

		/**
		 * Update the site details (goes to the wp_blogs table)
		 */
		update_blog_details( $site_id, $site_details );

		// update 'home' and 'siteurl' in the options table
		switch_to_blog( $site_id );
		$existing_home = trailingslashit( get_option( 'home' ) );
		$new_home      = esc_url_raw( $scheme . '://' . $host . $path );
		$new_home      = untrailingslashit( $new_home );

		// check if the actual 'home' value matches the old site URL
		if ( $site->domain === parse_url( $existing_home, PHP_URL_HOST )
		  && $site->path === parse_url( $existing_home, PHP_URL_PATH )
		) {
			update_option( 'home', $new_home );
		}

		$existing_site_url = trailingslashit( get_option( 'siteurl' ) );
		if ( $site->domain === parse_url( $existing_site_url, PHP_URL_HOST )
		  && $site->path === parse_url( $existing_site_url, PHP_URL_PATH )
		) {
			update_option( 'siteurl', $new_home );
		}

		/**
		 * WP core deletes rewrite rules during the URL updating process
		 *
		 * @see wp-admin/network/site-info.php
		 */
		delete_option( 'rewrite_rules' );

		restore_current_blog();
		$new_home = trailingslashit( $new_home ); // append trailing slash for success report to avoid confusion
		WP_CLI::success( "Update site URL to {$new_home}" );
	}
}
