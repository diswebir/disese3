<?php
/** Contact validation unit tests with WordPress API stubs. */
error_reporting( E_ALL );
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['hooks'] = array();
function add_action( $hook, $callback, $priority = 10, $accepted = 1 ) { $GLOBALS['hooks'][ $hook ][] = $callback; }
function add_filter( $hook, $callback, $priority = 10, $accepted = 1 ) { add_action( $hook, $callback ); }
class WP_Error { function __construct( $code, $message ) {} }
function is_wp_error( $thing ) { return $thing instanceof WP_Error; }
function sanitize_text_field( $thing ) { return trim( strip_tags( (string) $thing ) ); }
function sanitize_textarea_field( $thing ) { return trim( strip_tags( (string) $thing ) ); }
function sanitize_email( $thing ) { return filter_var( $thing, FILTER_SANITIZE_EMAIL ); }
function sanitize_key( $thing ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $thing ) ); }
function is_email( $thing ) { return filter_var( $thing, FILTER_VALIDATE_EMAIL ); }
function es_assert( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } echo "PASS: $message\n"; }
require __DIR__ . '/../my-custom-theme/inc/contact.php';
$valid = array( 'name' => 'علی رضایی', 'phone' => '۰۹۱۲ ۳۴۵ ۶۷۸۹', 'email' => 'ali@example.com', 'city' => '<b>اصفهان</b>', 'category' => 'urban', 'message' => 'لطفاً برای پروژه نورپردازی میدان شهر با ما تماس بگیرید.', 'consent' => '1' );
$clean = es_validate_contact_submission( $valid );
es_assert( ! is_wp_error( $clean ) && '09123456789' === $clean['phone'], 'Persian phone digits are converted to usable ASCII phone digits.' );
es_assert( 'اصفهان' === $clean['city'] && 'urban' === $clean['category'], 'Plain-text fields are sanitized and subject whitelisted.' );
foreach ( array( array( 'phone', 'abc<script>' ), array( 'email', 'not-an-email' ), array( 'category', 'unauthorized' ), array( 'consent', '0' ), array( 'message', 'short' ), array( 'name', array( 'injected' ) ) ) as $failure ) {
    $data = $valid; $data[ $failure[0] ] = $failure[1];
    es_assert( is_wp_error( es_validate_contact_submission( $data ) ), 'Invalid ' . $failure[0] . ' is rejected.' );
}
es_assert( isset( $GLOBALS['hooks']['admin_post_nopriv_es_contact_submit'], $GLOBALS['hooks']['admin_post_es_contact_submit'] ), 'Logged-in and guest submissions have native WordPress handlers.' );
es_assert( isset( $GLOBALS['hooks']['wp_privacy_personal_data_exporters'], $GLOBALS['hooks']['wp_privacy_personal_data_erasers'] ), 'WordPress privacy export and erasure tools are wired up.' );
echo "Contact validation tests passed.\n";
