<?php
/** Palette validation without WordPress: `php tests/palette-unit.php`. */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['palette_options'] = array();
function es_opt( $key ) {
    $defaults = array( 'color_mode' => 'dark', 'primary_color' => '#d9ae68', 'accent_color' => '#8c73d6', 'background_color' => '#090e1d', 'light_primary_color' => '#70420d', 'light_accent_color' => '#60448c', 'light_background_color' => '#f7f8fb', 'light_text_color' => '#17253b' );
    return $GLOBALS['palette_options'][ $key ] ?? $defaults[ $key ];
}
function sanitize_hex_color( $color ) { return is_string( $color ) && preg_match( '/^#[0-9a-f]{6}$/i', $color ) ? $color : null; }
function add_action( $name, $callback, $priority = 10 ) { /* No head rendering in this test. */ }
function ensure( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } echo "PASS: $message\n"; }
require __DIR__ . '/../my-custom-theme/inc/dynamic-css.php';
$p = es_safe_palette();
ensure( 'dark' === $p['mode'] && es_contrast_ratio( $p['primary'], $p['background'] ) >= 4.5, 'Default dark palette passes AA for primary against canvas.' );
$GLOBALS['palette_options'] = array( 'color_mode' => 'light' );
$p = es_safe_palette();
ensure( 'light' === $p['mode'] && es_contrast_ratio( $p['primary'], $p['background'] ) >= 4.5 && es_contrast_ratio( $p['primary'], '#ffffff' ) >= 4.5 && es_contrast_ratio( $p['text'], '#ffffff' ) >= 7, 'Default light palette passes AA buttons and AAA text.' );
$GLOBALS['palette_options'] = array( 'color_mode' => 'light', 'light_primary_color' => '#ffffff', 'light_accent_color' => '#fefefe', 'light_background_color' => '#161b24', 'light_text_color' => '#bbbbbb' );
$p = es_safe_palette();
ensure( '#70420d' === $p['primary'] && '#60448c' === $p['accent'] && '#f7f8fb' === $p['background'] && '#17253b' === $p['text'], 'Unsafe light options revert to readable defaults.' );
$GLOBALS['palette_options'] = array( 'color_mode' => 'dark', 'primary_color' => '#070a0b', 'accent_color' => '#111111', 'background_color' => '#fefefe' );
$p = es_safe_palette();
ensure( '#d9ae68' === $p['primary'] && '#8c73d6' === $p['accent'] && '#090e1d' === $p['background'], 'Unsafe dark options revert to readable defaults.' );
$GLOBALS['palette_options'] = array( 'color_mode' => 'light', 'light_primary_color' => '#3d3679', 'light_accent_color' => '#21714f', 'light_background_color' => '#ffffff', 'light_text_color' => '#101a2b' );
$p = es_safe_palette();
ensure( '#3d3679' === $p['primary'] && '#21714f' === $p['accent'] && '#ffffff' === $p['background'] && '#101a2b' === $p['text'], 'Accessible custom palette is retained.' );
