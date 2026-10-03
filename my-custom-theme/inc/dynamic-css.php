<?php
defined( 'ABSPATH' ) || exit;
/** WCAG 2.2 relative luminance: validate admin-supplied palettes before printing CSS. */
function es_color_luminance( $hex ) {
    $hex = ltrim( strtolower( (string) $hex ), '#' );
    if ( 3 === strlen( $hex ) ) { $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2]; }
    if ( ! preg_match( '/^[0-9a-f]{6}$/', $hex ) ) { return 0; }
    $channels = array();
    foreach ( array( 0, 2, 4 ) as $offset ) {
        $c = hexdec( substr( $hex, $offset, 2 ) ) / 255;
        $channels[] = $c <= 0.04045 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
    }
    return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
}
function es_contrast_ratio( $first, $second ) {
    $a = es_color_luminance( $first );
    $b = es_color_luminance( $second );
    return ( max( $a, $b ) + 0.05 ) / ( min( $a, $b ) + 0.05 );
}
function es_safe_palette() {
    $light = 'light' === es_opt( 'color_mode' );
    $fallback_bg = $light ? '#f7f8fb' : '#090e1d';
    $bg = sanitize_hex_color( es_opt( $light ? 'light_background_color' : 'background_color' ) ) ?: $fallback_bg;
    if ( ( $light && es_color_luminance( $bg ) < 0.82 ) || ( ! $light && es_color_luminance( $bg ) > 0.12 ) ) { $bg = $fallback_bg; }
    $fallback_primary = $light ? '#70420d' : '#d9ae68';
    $primary = sanitize_hex_color( es_opt( $light ? 'light_primary_color' : 'primary_color' ) ) ?: $fallback_primary;
    if ( $light && ( es_contrast_ratio( $primary, '#ffffff' ) < 4.5 || es_contrast_ratio( $primary, $bg ) < 4.5 ) ) { $primary = $fallback_primary; }
    if ( ! $light && ( es_contrast_ratio( $primary, $bg ) < 4.5 || es_contrast_ratio( $primary, '#17151a' ) < 4.5 ) ) { $primary = $fallback_primary; }
    $fallback_accent = $light ? '#60448c' : '#8c73d6';
    $accent = sanitize_hex_color( es_opt( $light ? 'light_accent_color' : 'accent_color' ) ) ?: $fallback_accent;
    if ( ( $light && ( es_contrast_ratio( $accent, $bg ) < 3 || es_contrast_ratio( $accent, '#ffffff' ) < 3 ) ) || ( ! $light && es_contrast_ratio( $accent, $bg ) < 3 ) ) { $accent = $fallback_accent; }
    $text = $light ? ( sanitize_hex_color( es_opt( 'light_text_color' ) ) ?: '#17253b' ) : '#f6f4f1';
    if ( $light && ( es_contrast_ratio( $text, $bg ) < 7 || es_contrast_ratio( $text, '#ffffff' ) < 7 ) ) { $text = '#17253b'; }
    return array( 'mode' => $light ? 'light' : 'dark', 'primary' => $primary, 'accent' => $accent, 'background' => $bg, 'text' => $text );
}
add_action( 'wp_head', function () {
    $colors = es_safe_palette();
    $body = 'Lalezar' === es_opt( 'body_font' ) ? 'Lalezar' : 'Vazirmatn';
    $heading = 'Lalezar' === es_opt( 'heading_font' ) ? 'Lalezar' : 'Vazirmatn';
    echo '<style id="es-variables">:root{--theme-primary:' . esc_html( $colors['primary'] ) . ';--theme-accent:' . esc_html( $colors['accent'] ) . ';--theme-bg:' . esc_html( $colors['background'] ) . ';--text:' . esc_html( $colors['text'] ) . ';--theme-body-font:"' . esc_html( $body ) . '";--theme-heading-font:"' . esc_html( $heading ) . '"}</style>' . "\n";
}, 20 );
