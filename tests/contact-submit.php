<?php
/** End-to-end handler smoke test with WordPress stubs. The handler redirects/exits. */
error_reporting( E_ALL );
define( 'ABSPATH', __DIR__ . '/' );
define( 'MINUTE_IN_SECONDS', 60 );
$GLOBALS['saved'] = array();
$GLOBALS['case'] = getenv( 'CONTACT_CASE' ) ?: 'valid';
function add_action( $hook, $callback, $priority = 10, $accepted = 1 ) { $GLOBALS['hooks'][ $hook ] = $callback; }
function add_filter( $hook, $callback, $priority = 10, $accepted = 1 ) { add_action( $hook, $callback ); }
class WP_Error { function __construct( $code, $message ) {} }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function es_opt( $key ) { return array( 'form_enabled' => 1, 'form_recipient' => '', 'email' => 'info@example.com' )[ $key ] ?? ''; }
function sanitize_text_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_textarea_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_email( $s ) { return filter_var( $s, FILTER_SANITIZE_EMAIL ); }
function sanitize_key( $s ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $s ) ); }
function is_email( $s ) { return filter_var( $s, FILTER_VALIDATE_EMAIL ); }
function wp_unslash( $x ) { return $x; }
function wp_verify_nonce( $value, $action ) { return 'valid-nonce' === $value && 'es_contact_submit' === $action; }
function wp_salt( $name ) { return 'testing-secret'; }
function get_transient( $key ) { return 'rate' === $GLOBALS['case']; }
function set_transient( $key, $value, $ttl ) { $GLOBALS['saved']['rate'] = array( $key, $ttl ); }
function wp_date( $format ) { return '2026/10/03 11:00'; }
function wp_insert_post( $args, $error ) { if ( 'storage' === $GLOBALS['case'] ) { return new WP_Error( 'fail', 'Storage unavailable' ); } $GLOBALS['saved']['post'] = $args; return 77; }
function update_post_meta( $id, $key, $value ) { $GLOBALS['saved']['meta'][ $key ] = $value; }
function wp_mail( $recipient, $subject, $body ) { $GLOBALS['saved']['mail'] = array( $recipient, $subject, $body ); return 'mailfail' !== $GLOBALS['case']; }
function wp_get_referer() { return '/contact/'; }
function es_contact_url() { return '/contact/'; }
function wp_validate_redirect( $url, $fallback ) { return $url ?: $fallback; }
function remove_query_arg( $key, $url ) { return $url; }
function add_query_arg( $key, $value, $url ) { return $url . '?' . $key . '=' . $value; }
function wp_safe_redirect( $url, $code ) { $GLOBALS['saved']['redirect'] = array( $url, $code ); }
register_shutdown_function( function () {
    $data = $GLOBALS['saved']; $case = $GLOBALS['case'];
    $expected = array( 'valid' => 'saved', 'mailfail' => 'saved', 'nonce' => 'invalid', 'bot' => 'saved', 'rate' => 'wait', 'storage' => 'error' )[ $case ];
    $redirect = $data['redirect'] ?? array( '', 0 );
    $good = $redirect[0] === '/contact/?es_contact=' . $expected . '#contact-form' && $redirect[1] === 303;
    if ( in_array( $case, array( 'valid', 'mailfail' ), true ) ) {
        $good = $good && ( $data['post']['post_type'] ?? '' ) === 'es_inquiry' && ( $data['post']['post_status'] ?? '' ) === 'private' &&
            ( $data['meta']['_es_contact_phone'] ?? '' ) === '09123456789' &&
            ( $data['meta']['_es_contact_notification_sent'] ?? '' ) === ( 'mailfail' === $case ? '0' : '1' ) &&
            ( $data['mail'][0] ?? '' ) === 'info@example.com' && ( $data['rate'][1] ?? 0 ) === 60;
    } else { $good = $good && ! isset( $data['post'] ); }
    if ( ! $good ) { fwrite( STDERR, 'FAIL: ' . $case . ': ' . var_export( $data, true ) ); exit( 1 ); }
    echo "PASS: contact handler $case scenario redirects and persists safely.\n";
} );
require __DIR__ . '/../my-custom-theme/inc/contact.php';
$_POST = array( 'es_contact_nonce' => 'valid-nonce', 'name' => 'علی رضایی', 'phone' => '۰۹۱۲۳۴۵۶۷۸۹', 'email' => 'ali@example.com', 'city' => 'اصفهان', 'category' => 'urban', 'message' => 'درخواست مشاوره برای نورپردازی میدان شهر اصفهان را دارم.', 'consent' => '1', 'website' => '' );
$_SERVER['REMOTE_ADDR'] = '192.0.2.1';
if ( 'nonce' === $GLOBALS['case'] ) { $_POST['es_contact_nonce'] = 'invalid'; }
if ( 'bot' === $GLOBALS['case'] ) { $_POST['website'] = 'spam.example'; }
es_contact_submit();
