<?php
/** Native product/project/article meta smoke tests. Run with php tests/meta-unit.php. */
error_reporting( E_ALL );
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['hooks'] = array(); $GLOBALS['saved'] = array();
function add_action( $name, $fn, $priority = 10, $accepted = 1 ) { $GLOBALS['hooks'][ $name ][] = $fn; }
function add_filter( $name, $fn, $priority = 10, $accepted = 1 ) { add_action( $name, $fn ); }
function wp_verify_nonce( $value, $action ) { return $value === 'valid' && $action === 'es_save_meta'; }
function sanitize_text_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function sanitize_textarea_field( $s ) { return trim( strip_tags( (string) $s ) ); }
function wp_unslash( $x ) { return $x; }
function wp_is_post_revision( $id ) { return false; }
function current_user_can( $cap, $id = null ) { return true; }
function absint( $x ) { return abs( (int) $x ); }
function esc_url_raw( $url ) { return preg_match( '~^https?://~', $url ) ? $url : ''; }
function update_post_meta( $id, $key, $value ) { $GLOBALS['saved'][ $id ][ $key ] = $value; }
function get_post_meta( $id, $key, $single = true ) { return $GLOBALS['saved'][ $id ][ $key ] ?? ''; }
function metadata_exists( $type, $id, $key ) { return isset( $GLOBALS['saved'][ $id ][ $key ] ); }
function es_assert( $ok, $description ) { if ( ! $ok ) { throw new RuntimeException( $description ); } echo "PASS: $description\n"; }
require __DIR__ . '/../my-custom-theme/inc/meta-boxes.php';
$schema = es_meta_schema();
es_assert( count( $schema['product'] ) === 11 && count( $schema['project'] ) === 7 && count( $schema['post'] ) === 4, 'All requested custom fields are registered by content type.' );
$save = $GLOBALS['hooks']['save_post'][0];
$_POST['es_meta_nonce'] = 'invalid'; $_POST['es_meta'] = array( '_es_project_client' => 'Not saved' );
$save( 1, (object) array( 'post_type' => 'project' ) );
es_assert( empty( $GLOBALS['saved'] ), 'Invalid nonce blocks all metadata writes.' );
$_POST['es_meta_nonce'] = 'valid'; $_POST['es_meta'] = array( '_es_project_client' => '<b>شهرداری</b>', '_es_total_power_kw' => '-30', '_es_before_after_gallery' => '12,16,junk,22', '_es_project_map_coords' => '35.1, 51.2', '_es_fake' => 'invalid' );
$save( 2, (object) array( 'post_type' => 'project' ) );
es_assert( $GLOBALS['saved'][2]['_es_project_client'] === 'شهرداری' && $GLOBALS['saved'][2]['_es_total_power_kw'] == 0, 'Project text and number are sanitized.' );
es_assert( $GLOBALS['saved'][2]['_es_before_after_gallery'] === '12,16,22' && ! isset( $GLOBALS['saved'][2]['_es_fake'] ), 'Gallery IDs and metadata keys are whitelisted.' );
$_POST['es_meta'] = array( '_es_faq_schema_repeater' => array( array( 'question' => '<b>چیست؟</b>', 'answer' => '<script>x</script>نور' ), array( 'question' => '', 'answer' => '' ) ) );
$save( 3, (object) array( 'post_type' => 'post' ) );
es_assert( count( $GLOBALS['saved'][3]['_es_faq_schema_repeater'] ) === 1 && $GLOBALS['saved'][3]['_es_faq_schema_repeater'][0]['answer'] === 'xنور', 'FAQ repeater saves only valid sanitized question-answer pairs.' );
$_POST['es_meta'] = array( '_es_order_type' => 'official_tender', '_es_is_purchasable_online' => '0', '_es_demo_video_url' => 'javascript:alert(1)' );
$save( 4, (object) array( 'post_type' => 'product' ) );
es_assert( es_product_is_inquiry( 4 ) && $GLOBALS['saved'][4]['_es_demo_video_url'] === '', 'Tender products are inquiry-only and dangerous video URLs are removed.' );
$product = new class { function get_id() { return 4; } };
es_assert( $GLOBALS['hooks']['woocommerce_is_purchasable'][0]( true, $product ) === false, 'WooCommerce add-to-cart is disabled for inquiry products.' );
echo "Meta tests passed.\n";
