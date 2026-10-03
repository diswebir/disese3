<?php
defined( 'ABSPATH' ) || exit;
/** All theme settings live in one autoloaded wp_options row. Defaults are never written unnecessarily. */
function es_opt( $key = null, $fallback = null ) {
    static $options = null;
    if ( null === $options ) {
        $stored  = get_option( 'es_theme_options', array() );
        $options = is_array( $stored ) ? $stored : array();
    }
    if ( null === $key ) { return $options; }
    if ( array_key_exists( $key, $options ) ) { return $options[ $key ]; }
    $fields = es_schema_fields();
    return isset( $fields[ $key ] ) ? $fields[ $key ]['default'] : $fallback;
}
function es_sanitize_options( $raw ) {
    if ( ! is_array( $raw ) ) { return array(); }
    $clean = array();
    foreach ( es_schema_fields() as $key => $field ) {
        if ( ! array_key_exists( $key, $raw ) ) {
            if ( 'toggle' === $field['type'] ) { $clean[ $key ] = 0; }
            continue;
        }
        $value = $raw[ $key ];
        if ( 'repeater' === $field['type'] ) {
            $rows = array();
            if ( is_array( $value ) ) {
                foreach ( array_slice( $value, 0, 50 ) as $row ) {
                    if ( ! is_array( $row ) ) { continue; }
                    $item = array();
                    foreach ( $field['subfields'] as $subkey => $label ) {
                        $item[ $subkey ] = sanitize_text_field( isset( $row[ $subkey ] ) && is_scalar( $row[ $subkey ] ) ? $row[ $subkey ] : '' );
                    }
                    if ( array_filter( $item ) ) { $rows[] = $item; }
                }
            }
            $clean[ $key ] = $rows;
        } elseif ( 'toggle' === $field['type'] ) {
            $clean[ $key ] = (int) (bool) $value;
        } elseif ( is_scalar( $value ) ) {
            switch ( $field['type'] ) {
                case 'color': $clean[ $key ] = sanitize_hex_color( $value ) ?: $field['default']; break;
                case 'image': $clean[ $key ] = absint( $value ); break;
                case 'email': $clean[ $key ] = sanitize_email( $value ); break;
                case 'url': $clean[ $key ] = esc_url_raw( $value ); break;
                case 'textarea': $clean[ $key ] = sanitize_textarea_field( $value ); break;
                case 'select': $clean[ $key ] = isset( $field['choices'][ $value ] ) ? $value : $field['default']; break;
                default: $clean[ $key ] = sanitize_text_field( $value );
            }
        }
    }
    return $clean;
}
