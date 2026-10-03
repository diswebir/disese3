<?php
/** Explicit page-tool idempotency test without WordPress DB. */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['hooks'] = array(); $GLOBALS['page'] = null; $GLOBALS['insertions'] = 0; $GLOBALS['template'] = '';
function add_action( $name, $callback, $priority = 10, $accepted = 1 ) { $GLOBALS['hooks'][ $name ] = $callback; }
function is_wp_error( $value ) { return false; }
function get_page_by_path( $slug ) { return $GLOBALS['page']; }
function wp_insert_post( $data, $wp_error = false ) {
    $GLOBALS['insertions']++; $GLOBALS['page'] = (object) array( 'ID' => 51, 'post_status' => $data['post_status'], 'post_name' => $data['post_name'] );
    return 51;
}
function update_post_meta( $id, $key, $value ) { if ( '_wp_page_template' === $key ) { $GLOBALS['template'] = $value; } }
require __DIR__ . '/../my-custom-theme/inc/admin/tools.php';
if ( es_ensure_contact_page() !== 51 || es_ensure_contact_page() !== 51 || $GLOBALS['insertions'] !== 1 || $GLOBALS['template'] !== 'templates/template-contact.php' ) { throw new RuntimeException( 'Contact page must be created once and template assigned without duplication.' ); }
echo "PASS: Contact page creation is idempotent and assigns the dedicated template.\n";
