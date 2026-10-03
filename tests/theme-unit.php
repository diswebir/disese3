<?php
/** Run with php tests/theme-unit.php (or php-wasm-cli tests/theme-unit.php). */
error_reporting( E_ALL );
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['es_mock_option_calls'] = 0;
$GLOBALS['es_mock_options'] = array( 'hero_title' => 'عنوان سفارشی', 'show_projects' => 0 );
function get_option( $key, $fallback = false ) { $GLOBALS['es_mock_option_calls']++; return $GLOBALS['es_mock_options']; }
function sanitize_text_field( $value ) { return trim( strip_tags( (string) $value ) ); }
function sanitize_textarea_field( $value ) { return trim( strip_tags( (string) $value ) ); }
function sanitize_email( $value ) { return filter_var( $value, FILTER_SANITIZE_EMAIL ); }
function esc_url_raw( $value ) { return preg_match( '~^https?://~', $value ) ? $value : ''; }
function sanitize_hex_color( $value ) { return preg_match( '/^#[a-fA-F0-9]{6}$/', $value ) ? $value : null; }
function absint( $value ) { return abs( (int) $value ); }
function es_assert( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } echo "PASS: $message\n"; }
require __DIR__ . '/../my-custom-theme/inc/options-schema.php';
require __DIR__ . '/../my-custom-theme/inc/options.php';
$schema = es_options_schema(); $fields = es_schema_fields();
es_assert( count( $schema ) === 4 && count( $fields ) >= 30, 'All options originate from a single four-tab schema.' );
es_assert( es_opt( 'hero_title' ) === 'عنوان سفارشی', 'Stored option overrides default.' );
es_assert( es_opt( 'phone' ) === '03191091011', 'Missing option falls back to schema default.' );
es_assert( es_opt( 'show_projects' ) === 0, 'Explicit false toggle is not replaced by true default.' );
es_assert( $GLOBALS['es_mock_option_calls'] === 1, 'Options row is read once per request.' );
$clean = es_sanitize_options( array(
    'hero_title' => '<script>bad</script>عنوان',
    'primary_color' => 'not-a-color',
    'color_mode' => 'light',
    'light_background_color' => '#fefefe',
    'body_font' => 'InjectedFont',
    'logo' => '-12',
    'show_projects' => '0',
    'stats' => array( array( 'value' => '<b>5</b>', 'label' => '<script>x</script>پروژه', 'unknown' => 'ignored' ), 'wrong row', array( 'value' => '', 'label' => '' ) ),
    'unauthorized_field' => '<script>alert(1)</script>',
    'aparat_url' => 'javascript:alert(1)',
) );
es_assert( ! isset( $clean['unauthorized_field'] ), 'Unexpected keys are removed by whitelist.' );
es_assert( $clean['primary_color'] === '#d9ae68' && $clean['body_font'] === 'Vazirmatn', 'Invalid color and select values fall back safely.' );
es_assert( $clean['color_mode'] === 'light' && $clean['light_background_color'] === '#fefefe', 'Light mode and hex colors survive schema sanitization.' );
es_assert( es_sanitize_options( array( 'color_mode' => 'arbitrary' ) )['color_mode'] === 'dark', 'Unknown mode safely defaults to dark.' );
es_assert( $clean['logo'] === 12 && $clean['show_projects'] === 0, 'Image ID and toggle are normalized.' );
es_assert( count( $clean['stats'] ) === 1 && count( $clean['stats'][0] ) === 2 && strpos( $clean['stats'][0]['value'], '<' ) === false, 'Repeater rows and cells are whitelisted and sanitized.' );
es_assert( $clean['aparat_url'] === '', 'Unsafe URL schemes are rejected.' );
$empty = es_sanitize_options( array() );
es_assert( $empty['show_blog'] === 0 && $empty['show_products'] === 0, 'Unchecked toggles are stored as zero.' );
echo "Theme options unit tests passed.\n";
