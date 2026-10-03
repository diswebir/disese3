<?php
defined( 'ABSPATH' ) || exit;
add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'erfan-sanat', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'woocommerce', array( 'thumbnail_image_width' => 420, 'single_image_width' => 720, 'product_grid' => array( 'default_columns' => 3, 'min_columns' => 2, 'max_columns' => 4 ) ) );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    register_nav_menus( array( 'primary' => 'منوی اصلی', 'footer' => 'منوی فوتر' ) );
    add_image_size( 'es-card', 720, 820, true );
} );
function es_asset( $path ) { return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' ); }
function es_image( $option, $fallback ) {
    $id = absint( es_opt( $option ) );
    return $id && wp_get_attachment_image_url( $id, 'full' ) ? wp_get_attachment_image_url( $id, 'full' ) : es_asset( 'images/' . $fallback );
}
function es_phone_url( $phone ) { return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ); }
function es_projects_url() { return get_post_type_archive_link( 'project' ) ?: home_url( '/projects/' ); }
function es_shop_url() { return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ); }
function es_blog_url() { $id = (int) get_option( 'page_for_posts' ); return $id ? get_permalink( $id ) : home_url( '/blog/' ); }
function es_excerpt( $length = 24 ) { return wp_trim_words( get_the_excerpt(), $length, '…' ); }
function es_breadcrumbs() {
    echo '<nav class="breadcrumbs container" aria-label="مسیر صفحه"><a href="' . esc_url( home_url( '/' ) ) . '">خانه</a><span aria-hidden="true">/</span>';
    if ( is_singular( 'project' ) ) { echo '<a href="' . esc_url( es_projects_url() ) . '">پروژه‌ها</a><span aria-hidden="true">/</span>'; }
    elseif ( is_singular( 'post' ) ) { echo '<a href="' . esc_url( es_blog_url() ) . '">دانشنامه</a><span aria-hidden="true">/</span>'; }
    echo '<span aria-current="page">' . esc_html( wp_get_document_title() ) . '</span></nav>';
}
/** Fallback navigation keeps an unconfigured installation usable. */
function es_fallback_menu() {
    $links = array( 'صفحه نخست' => home_url( '/' ), 'پروژه‌ها' => es_projects_url(), 'محصولات' => es_shop_url(), 'درباره ما' => home_url( '/#about' ), 'دانشنامه' => es_blog_url(), 'تماس با ما' => home_url( '/#contact' ) );
    echo '<ul class="nav-list">';
    foreach ( $links as $label => $url ) { echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>'; }
    echo '</ul>';
}
