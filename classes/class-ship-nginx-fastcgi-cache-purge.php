<?php


final class Ship_NGINX_FastCGI_cache_purge {

    /**
     * @var mixed This will contain an instance of the class.
     */
    public static $instance = null;


    /**
     * We'll register all hooks here.
     */
    private function __construct() {
        add_action( 'edit_post', [ $this, 'purge_cache_by_post_id' ] );
        add_action( 'template_redirect', [ $this, 'manually_purge_cache' ] );
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
     * Purge a page from the NGINX FastCGI cache.
     *
     * @param int $post_id WP_Post ID.
     */
    public function purge_cache_by_post_id( $post_id ) {
        $post = get_post( $post_id );
        if ( ! $post ) {
            return;
        }

        if ( 'publish' !== get_post_status( $post_id ) ) {
            return;
        }

        $public_post_types = get_post_types([ 'public' => true ]);
        $post_type = get_post_type( $post_id );
        if ( ! in_array( $post_type, $public_post_types ) ) {
            return;
        }

        $url = get_permalink( $post_id );
        if ( $url ) {
            $this->purge_cache_via_url( $url );
        }
    }


    /**
     * Manually purge the cache if the correct GET params are set.
     */
    public function manually_purge_cache() {
        if ( isset( $_GET['purge_post_id'] ) ) {
            $post_id = (int) $_GET['purge_post_id'];

            $result = $this->purge_cache_by_post_id( $post_id );
        }
        elseif ( isset( $_GET['ship_nginx_purge_cache'] ) ) {
            $uri = parse_url( $_SERVER['REQUEST_URI'] );
            $url = home_url( $uri['path'] );

            $path = $this->purge_cache_via_url( $url );
        }

    }


    /**
     * Get the path to the cached file from the URL.
     *
     * Nginx stores it's cached files via a path/filename generated
     * from a hash of the request URL. The path to the cached files
     * will look something like:
     *
     * 2/50/aea70a210cb6b98c3b8bce657ce7f502
     *
     * @param string $url URL to parse.
     *
     * @return string path to the cached page.
     */
    protected function get_cache_path( $url ) {
        if ( ! $url || empty( $url ) ) {
            return false;
        }

        $url = esc_url( $url );
        $url = parse_url( $url );
        $hash = md5( $url['scheme'] . 'GET' . $url['host'] . $url['path'] );
        $path = trailingslashit( NGINX_CACHE_PATH );

        return $path . substr( $hash, -1 ) . '/' . substr( $hash, -3, 2 ) . '/' . $hash;
    }


    /**
     * Flush the cache by URL.
     *
     * @param $string $url URL of the page to flush from the cache.
     *
     * @return bool True on success or false.
     */
    public function purge_cache_via_url( $url ) {
        if ( ! $url || empty( $url ) ) {
            return false;
        }

        $path = $this->get_cache_path( $url );
        if ( $path ) {
            return $this->flush_cache( $path );
        }

        return false;
    }


    /**
     * Flush the cache from the path to the cached file.
     *
     * @param string $path Path to the cached file to flush.
     *
     * @return bool True on success or false.
     */
    protected function flush_cache( $path ) {
        if ( file_exists( $path ) ) {
            return unlink( $path );
        }

        return false;
    }

}
