<?php
defined( 'ABSPATH' ) || exit;
add_action( 'wp_head', function () {
    $primary = sanitize_hex_color( es_opt( 'primary_color' ) ) ?: '#d9ae68';
    $accent = sanitize_hex_color( es_opt( 'accent_color' ) ) ?: '#8c73d6';
    $background = sanitize_hex_color( es_opt( 'background_color' ) ) ?: '#090e1d';
    $body = 'Lalezar' === es_opt( 'body_font' ) ? 'Lalezar' : 'Vazirmatn';
    $heading = 'Lalezar' === es_opt( 'heading_font' ) ? 'Lalezar' : 'Vazirmatn';
    echo '<style id="es-variables">:root{--theme-primary:' . esc_html( $primary ) . ';--theme-accent:' . esc_html( $accent ) . ';--theme-bg:' . esc_html( $background ) . ';--theme-body-font:"' . esc_html( $body ) . '";--theme-heading-font:"' . esc_html( $heading ) . '"}</style>' . "\n";
}, 20 );
