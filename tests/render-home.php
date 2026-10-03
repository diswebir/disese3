<?php
/** Standalone template smoke-render. This is a mock, NOT a WordPress install. */
error_reporting( E_ALL );
define( 'ABSPATH', __DIR__ . '/' );
define( 'ES_THEME', __DIR__ . '/../my-custom-theme' );
$GLOBALS['es_hooks'] = array();
function add_action( $name, $callback, $priority = 10, $count = 1 ) { $GLOBALS['es_hooks'][ $name ][] = $callback; }
function add_filter( $name, $callback, $priority = 10, $count = 1 ) { add_action( $name, $callback ); }
function get_template_directory() { return ES_THEME; }
function get_template_directory_uri() { return '/my-custom-theme'; }
function get_option( $key, $fallback = false ) { return $fallback; }
function wp_get_attachment_image_url( $id, $size ) { return false; }
function absint( $x ) { return abs( (int) $x ); }
function sanitize_hex_color( $x ) { return $x; }
function home_url( $path = '/' ) { return $path; }
function get_post_type_archive_link( $type ) { return '/projects/'; }
function post_type_exists( $type ) { return false; }
function esc_html( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $value ) { return esc_html( $value ); }
function esc_url( $value ) { return esc_attr( $value ); }
function sanitize_email( $value ) { return $value; }
function language_attributes() { echo 'lang="fa-IR"'; }
function bloginfo( $what ) { echo 'UTF-8'; }
function get_bloginfo( $what ) { return 'عرفان صنعت'; }
function body_class() { echo 'class="home"'; }
function wp_body_open() {}
function wp_head() { echo '<link rel="stylesheet" href="/my-custom-theme/assets/css/theme.css">'; foreach ( $GLOBALS['es_hooks']['wp_head'] ?? array() as $hook ) { $hook(); } }
function wp_footer() { echo '<script src="/my-custom-theme/assets/js/theme.js"></script>'; }
function wp_nav_menu( $args ) { call_user_func( $args['fallback_cb'] ); }
function get_header() { require ES_THEME . '/header.php'; }
function get_footer() { require ES_THEME . '/footer.php'; }
function get_template_part( $slug ) { require ES_THEME . '/' . $slug . '.php'; }
function wp_date( $format ) { return date( $format ); }
class WP_Query { function __construct( $args ) {} function have_posts() { return false; } }
require ES_THEME . '/functions.php';
require ES_THEME . '/front-page.php';
