<?php
defined( 'ABSPATH' ) || exit;
add_action( 'wp_enqueue_scripts', function () {
    $base = get_template_directory();
    wp_enqueue_style( 'es-theme', es_asset( 'css/theme.css' ), array(), filemtime( $base . '/assets/css/theme.css' ) );
    if ( 'light' === es_opt( 'color_mode' ) ) { wp_enqueue_style( 'es-light', es_asset( 'css/light.css' ), array( 'es-theme' ), filemtime( $base . '/assets/css/light.css' ) ); }
    if ( function_exists( 'is_product' ) && is_product() ) {
        wp_enqueue_style( 'es-product', es_asset( 'css/product.css' ), array( 'light' === es_opt( 'color_mode' ) ? 'es-light' : 'es-theme' ), filemtime( $base . '/assets/css/product.css' ) );
    }
    wp_enqueue_script( 'es-theme', es_asset( 'js/theme.js' ), array(), filemtime( $base . '/assets/js/theme.js' ), true );
} );
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'toplevel_page_es-theme' !== $hook && 'post.php' !== $hook && 'post-new.php' !== $hook ) { return; }
    $base = get_template_directory();
    wp_enqueue_style( 'es-admin', es_asset( 'css/admin.css' ), array(), filemtime( $base . '/assets/css/admin.css' ) );
    wp_enqueue_media();
    wp_enqueue_script( 'es-admin', es_asset( 'js/admin.js' ), array(), filemtime( $base . '/assets/js/admin.js' ), true );
} );
