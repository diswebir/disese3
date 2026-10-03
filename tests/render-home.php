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
function get_option( $key, $fallback = false ) { return 'es_theme_options' === $key && getenv( 'ES_RENDER_LIGHT' ) ? array( 'color_mode' => 'light' ) : $fallback; }
function get_page_by_path( $path ) { return 'contact' === $path ? (object) array( 'ID' => 42, 'post_status' => 'publish' ) : null; }
function get_permalink( $id = null ) { return getenv( 'ES_RENDER_PRODUCT' ) && 42 !== (int) $id ? '/products/' . ( $id ?: 'artam' ) . '/' : '/contact/'; }
function admin_url( $path = '' ) { return '/wp-admin/' . $path; }
function wp_nonce_field( $action, $name = '_wpnonce' ) { echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="preview-nonce">'; }
function wp_unslash( $value ) { return $value; }
function sanitize_key( $value ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( $value ) ); }
function have_posts() { static $once = false; if ( getenv( 'ES_RENDER_PRODUCT' ) && ! $once ) { $once = true; return true; } return false; }
if ( getenv( 'ES_RENDER_PRODUCT' ) ) { require __DIR__ . '/mock-product.php'; }
function wp_get_attachment_image_url( $id, $size ) { if ( ! getenv( 'ES_RENDER_PRODUCT' ) ) { return false; } return '/my-custom-theme/assets/images/' . ( 104 === $id ? 'light-sphere.jpg' : ( 105 === $id ? 'light-tunnel.jpg' : ( 106 === $id ? 'light-element.jpg' : 'light-tree.jpg' ) ) ); }
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
function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags ); }
function body_class() { echo getenv( 'ES_RENDER_PRODUCT' ) ? 'class="single-product woocommerce-page"' : ( getenv( 'ES_RENDER_CONTACT' ) ? 'class="page page-template-template-contact"' : 'class="home"' ); }
function wp_body_open() {}
function wp_head() { echo '<title>' . ( getenv( 'ES_RENDER_CONTACT' ) ? 'تماس با ما | ' : ( getenv( 'ES_RENDER_PRODUCT' ) ? 'درخت نوری آرتام | ' : '' ) ) . 'عرفان صنعت</title><link rel="stylesheet" href="/my-custom-theme/assets/css/theme.css">'; if ( getenv( 'ES_RENDER_LIGHT' ) ) { echo '<link rel="stylesheet" href="/my-custom-theme/assets/css/light.css">'; } if ( getenv( 'ES_RENDER_PRODUCT' ) ) { echo '<link rel="stylesheet" href="/my-custom-theme/assets/css/product.css">'; } foreach ( $GLOBALS['es_hooks']['wp_head'] ?? array() as $hook ) { $hook(); } }
function wp_footer() { echo '<script src="/my-custom-theme/assets/js/theme.js"></script>'; }
function wp_nav_menu( $args ) { call_user_func( $args['fallback_cb'] ); }
function wp_unique_id( $prefix = '' ) { static $count = 0; return $prefix . ++$count; }
function get_search_query() { return ''; }
function get_search_form() { require ES_THEME . '/searchform.php'; }
function get_header() { require ES_THEME . '/header.php'; }
function get_footer() { require ES_THEME . '/footer.php'; }
function get_template_part( $slug ) { require ES_THEME . '/' . $slug . '.php'; }
function wp_date( $format ) { return date( $format ); }
class WP_Query { function __construct( $args ) {} function have_posts() { return false; } }
require ES_THEME . '/functions.php';
require ES_THEME . ( getenv( 'ES_RENDER_PRODUCT' ) ? '/woocommerce.php' : ( getenv( 'ES_RENDER_CONTACT' ) ? '/page-contact.php' : '/front-page.php' ) );
