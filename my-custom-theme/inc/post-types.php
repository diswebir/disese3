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
    /* Private contact requests: visible only to site administrators, never on the public REST API. */
    register_post_type( 'es_inquiry', array(
        'labels' => array( 'name' => 'درخواست‌های مشاوره', 'singular_name' => 'درخواست مشاوره', 'edit_item' => 'مشاهده درخواست' ),
        'public' => false, 'show_ui' => true, 'show_in_rest' => false, 'exclude_from_search' => true,
        'menu_icon' => 'dashicons-email-alt', 'menu_position' => 59,
        'supports' => array( 'title', 'editor' ),
        'map_meta_cap' => false,
        'capabilities' => array(
            'edit_post' => 'manage_options', 'read_post' => 'manage_options', 'delete_post' => 'manage_options',
            'edit_posts' => 'manage_options', 'edit_others_posts' => 'manage_options', 'publish_posts' => 'manage_options',
            'read_private_posts' => 'manage_options', 'delete_posts' => 'manage_options', 'delete_others_posts' => 'manage_options',
            'create_posts' => 'do_not_allow',
        ),
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
