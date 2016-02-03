<?php


final class Ship_NGINX_FastCGI_cache_admin_bar {

    /**
     * @var mixed This will contain an instance of the class.
     */
    public static $instance = null;


    /**
     * We'll register all hooks here.
     */
    private function __construct() {
        add_action( 'admin_bar_menu', [ $this, 'add_flush_cache_link' ], 999 );
    }


    /**
     * Creates or returns an instance of this class.
     *
     * @return A single instance of this class.
     */
    public static function init() {
        if ( self::$instance === null ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Add a link to flush the cache for the current page.
     */
    public function add_flush_cache_link( $wp_admin_bar ) {
        if ( is_admin() ) {
            return;
        }

        $href = add_query_arg( [ 'ship_nginx_purge_cache' => 1 ], home_url( $_SERVER['REQUEST_URI'] ) );

        $wp_admin_bar->add_node([
            'id'    => 'ship_ngninx_fastcgi_cache_flush',
            'title' => __( 'Flush cache', 'sihp_nfcp' ),
            'href'  => esc_url( $href ),
        ]);
    }


}
