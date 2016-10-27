<?php

namespace Ship_Nginx_Fastcgi;


final class Cache_Admin_Bar {

	/**
	 * @var mixed This will contain an instance of the class.
	 */
	public static $instance = null;

	/**
	 * @var string
	 */
	private $menu_id = 'ship_ngninx_fastcgi_cache_flush';


	/**
	 * We'll register all hooks here.
	 */
	private function __construct() {
		add_action( 'admin_bar_menu', [ $this, 'add_flush_cache_link' ], 999 );
	}


	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return Cache_Admin_Bar
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Add a link to flush the cache for the current page.
	 *
	 * @param $wp_admin_bar
	 */
	public function add_flush_cache_link( \WP_Admin_Bar $wp_admin_bar ) {
		if ( is_admin() ) {
			return;
		}

		$wp_admin_bar->add_menu( [
			'id'    => $this->menu_id,
			'title' => __( 'Purge cache', 'sihp_nfcp' ),
			'href'  => $this->get_cache_purge_url( 'ship_nginx_purge_cache' ),
		] );
		$wp_admin_bar->add_menu( [
			'parent' => $this->menu_id,
			'id'     => 'ship_ngninx_fastcgi_cache_flush_all',
			'title'  => __( 'Purge all caches', 'sihp_nfcp' ),
			'href'   => $this->get_cache_purge_url( 'ship_nginx_purge_cache_all' ),
		] );
	}

	/**
	 * Get a URL to add the purge cache flag
	 *
	 * @param $query_arg
	 *
	 * @return string
	 */
	private function get_cache_purge_url( $query_arg ) {
		return esc_url( add_query_arg( [ $query_arg => 1 ], home_url( $_SERVER[ 'REQUEST_URI' ] ) ) );
	}


}
