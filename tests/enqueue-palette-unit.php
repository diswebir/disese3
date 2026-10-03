<?php
/** Verify stylesheet dependency and mode switch without WordPress. */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['mode'] = 'dark'; $GLOBALS['is_product'] = false; $GLOBALS['hooks'] = array(); $GLOBALS['styles'] = array();
function is_product() { return $GLOBALS['is_product']; }
function add_action( $name, $callback ) { $GLOBALS['hooks'][ $name ] = $callback; }
function es_opt( $key ) { return $GLOBALS['mode']; }
function get_template_directory() { return __DIR__ . '/../my-custom-theme'; }
function es_asset( $path ) { return '/my-custom-theme/assets/' . $path; }
function wp_enqueue_style( $id, $url, $dependencies, $version ) { $GLOBALS['styles'][ $id ] = array( $url, $dependencies, $version ); }
function wp_enqueue_script( ...$args ) {}
require __DIR__ . '/../my-custom-theme/inc/enqueue.php';
$GLOBALS['hooks']['wp_enqueue_scripts']();
if ( isset( $GLOBALS['styles']['es-light'] ) || ! isset( $GLOBALS['styles']['es-theme'] ) ) { throw new RuntimeException( 'Dark mode stylesheet switch failed.' ); }
echo "PASS: dark mode loads only the original theme.css\n";
$GLOBALS['mode'] = 'light'; $GLOBALS['styles'] = array();
$GLOBALS['hooks']['wp_enqueue_scripts']();
if ( ! isset( $GLOBALS['styles']['es-light'] ) || $GLOBALS['styles']['es-light'][1] !== array( 'es-theme' ) ) { throw new RuntimeException( 'Light stylesheet must load after theme.css.' ); }
echo "PASS: light mode loads local light.css after theme.css\n";
$GLOBALS['is_product'] = true; $GLOBALS['styles'] = array();
$GLOBALS['hooks']['wp_enqueue_scripts']();
if ( $GLOBALS['styles']['es-product'][1] !== array( 'es-light' ) ) { throw new RuntimeException( 'Light product CSS must load after light palette.' ); }
echo "PASS: product stylesheet loads after the light palette\n";
$GLOBALS['mode'] = 'dark'; $GLOBALS['styles'] = array();
$GLOBALS['hooks']['wp_enqueue_scripts']();
if ( $GLOBALS['styles']['es-product'][1] !== array( 'es-theme' ) ) { throw new RuntimeException( 'Dark product CSS must depend on theme stylesheet.' ); }
echo "PASS: product stylesheet loads after the dark palette\n";
