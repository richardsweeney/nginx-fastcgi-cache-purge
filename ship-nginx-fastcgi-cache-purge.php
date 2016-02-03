<?php
/**
 * Plugin Name: Ship NGINX FastCGI cache purge
 * Version: 0.1-alpha
 * Description: PLUGIN DESCRIPTION HERE
 * Author: YOUR NAME HERE
 * Author URI: YOUR SITE HERE
 * Plugin URI: PLUGIN SITE HERE
 * Text Domain: ship-nginx-fastcgi-cache-purge
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


Ship_NGINX_FastCGI_cache_purge::init();
Ship_NGINX_FastCGI_cache_admin_bar::init();
