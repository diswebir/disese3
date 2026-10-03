<?php
defined( 'ABSPATH' ) || exit;
add_action( 'init', function () {
    register_post_type( 'project', array(
        'labels' => array( 'name' => 'پروژه‌ها', 'singular_name' => 'پروژه', 'add_new_item' => 'افزودن پروژه', 'edit_item' => 'ویرایش پروژه' ),
        'public' => true, 'has_archive' => 'projects', 'rewrite' => array( 'slug' => 'project', 'with_front' => false ),
        'menu_icon' => 'dashicons-lightbulb', 'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
    ) );
    register_taxonomy( 'project_cat', 'project', array(
        'labels' => array( 'name' => 'دسته‌بندی پروژه‌ها', 'singular_name' => 'دسته پروژه' ),
        'public' => true, 'hierarchical' => true, 'show_in_rest' => true,
        'rewrite' => array( 'slug' => 'project-category', 'hierarchical' => true, 'with_front' => false ),
    ) );
    register_taxonomy( 'project_location', 'project', array(
        'labels' => array( 'name' => 'موقعیت پروژه‌ها', 'singular_name' => 'شهر پروژه' ),
        'public' => true, 'hierarchical' => false, 'show_in_rest' => true,
        'rewrite' => array( 'slug' => 'project-location', 'with_front' => false ),
    ) );
} );
add_action( 'after_switch_theme', function () {
    /* Project rewrite rules must work even when the demo importer is never run. */
    flush_rewrite_rules();
} );
/* WordPress post is the native knowledge base; WooCommerce owns product/product_cat and /shop/. */
add_action( 'pre_get_posts', function ( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) { $query->set( 'post_type', 'post' ); }
} );
