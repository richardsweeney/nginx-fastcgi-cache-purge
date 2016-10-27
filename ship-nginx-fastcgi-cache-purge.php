<?php
/**
 * Plugin Name: Ship NGINX FastCGI cache purge
 * Version: 0.1
 * Description: A plugin to flush the NGINX FastCGI cache
 * Author: The Shipyard Crew
 * Author URI: https://theshipyard.se/
 * Text Domain: sihp_nfcp
 * Domain Path: /languages
 * @package Ship NGINX FastCGI cache purge
 */

if ( ! defined( 'NGINX_CACHE_PATH' ) ) {
    define( 'NGINX_CACHE_PATH', '/var/run/nginx-cache' );
}


// Add t10ns
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'sihp_nfcp', false, basename( dirname( __FILE__ ) ) . '/languages/' );
});


// Register the autoloader
spl_autoload_register( function( $classname ) {
    $classname = explode( '\\', $classname );
    $classfile = sprintf( '%sclasses/class-%s.php',
        plugin_dir_path( __FILE__ ),
        str_replace( '_', '-', strtolower( end( $classname ) ) )
    );
    if ( file_exists( $classfile ) ) {
        include_once( $classfile );
    }
});


\Ship_Nginx_Fastcgi\Cache_Purge::init();
\Ship_Nginx_Fastcgi\Cache_Admin_Bar::init();
