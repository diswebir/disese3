<?php
/** Native, dependency-free contact workflow. Submissions are private WP posts, not theme options. */
defined( 'ABSPATH' ) || exit;

function es_contact_categories() {
    return array(
        'urban' => 'پروژه نورپردازی شهری',
        'product' => 'محصول و استعلام قیمت',
        'technical' => 'مشاوره فنی و همکاری',
        'other' => 'سایر موارد',
    );
}
function es_contact_normalize_phone( $number ) {
    return strtr( (string) $number, array( '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9' ) );
}
/** Returns sanitized data or a generic error; never accepts arrays in scalar inputs. */
function es_validate_contact_submission( $raw ) {
    if ( ! is_array( $raw ) ) { return new WP_Error( 'invalid', 'درخواست نامعتبر است.' ); }
    $scalar = function ( $key ) use ( $raw ) { return isset( $raw[ $key ] ) && is_string( $raw[ $key ] ) ? $raw[ $key ] : ''; };
    $data = array(
        'name' => sanitize_text_field( $scalar( 'name' ) ),
        'phone' => sanitize_text_field( es_contact_normalize_phone( $scalar( 'phone' ) ) ),
        'email' => sanitize_email( $scalar( 'email' ) ),
        'city' => sanitize_text_field( $scalar( 'city' ) ),
        'category' => sanitize_key( $scalar( 'category' ) ),
        'message' => sanitize_textarea_field( $scalar( 'message' ) ),
    );
    $digits = preg_replace( '/[\s\-()]/', '', $data['phone'] );
    $categories = es_contact_categories();
    if ( strlen( $data['name'] ) < 4 || strlen( $data['name'] ) > 180 ||
        ! preg_match( '/^\+?[0-9]{8,15}$/', $digits ) || strlen( $data['city'] ) > 160 ||
        ( '' !== $scalar( 'email' ) && ! is_email( $data['email'] ) ) ||
        ! isset( $categories[ $data['category'] ] ) ||
        strlen( $data['message'] ) < 20 || strlen( $data['message'] ) > 8000 ||
        '1' !== $scalar( 'consent' ) ) {
        return new WP_Error( 'invalid', 'لطفاً فیلدهای فرم را بررسی کنید.' );
    }
    $data['phone'] = $digits;
    return $data;
}
function es_contact_redirect( $status ) {
    $referer = wp_get_referer();
    $target = wp_validate_redirect( $referer ?: '', es_contact_url() );
    $target = remove_query_arg( 'es_contact', $target );
    wp_safe_redirect( add_query_arg( 'es_contact', $status, $target ) . '#contact-form', 303 );
    exit;
}
function es_contact_submit() {
    if ( ! es_opt( 'form_enabled' ) ) { es_contact_redirect( 'unavailable' ); }
    $raw = isset( $_POST ) && is_array( $_POST ) ? wp_unslash( $_POST ) : array();
    $nonce = isset( $raw['es_contact_nonce'] ) && is_string( $raw['es_contact_nonce'] ) ? $raw['es_contact_nonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'es_contact_submit' ) ) { es_contact_redirect( 'invalid' ); }
    /* Quietly discard likely bots without putting untrusted input in the database. */
    if ( ! empty( $raw['website'] ) ) { es_contact_redirect( 'saved' ); }
    $data = es_validate_contact_submission( $raw );
    if ( is_wp_error( $data ) ) { es_contact_redirect( 'invalid' ); }
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
    $rate_key = 'es_contact_' . md5( $ip . wp_salt( 'nonce' ) );
    if ( get_transient( $rate_key ) ) { es_contact_redirect( 'wait' ); }
    $post_id = wp_insert_post( array(
        'post_type' => 'es_inquiry', 'post_status' => 'private',
        'post_title' => sprintf( 'درخواست %s — %s', $data['name'], wp_date( 'Y/m/d H:i' ) ),
        'post_content' => $data['message'],
    ), true );
    if ( is_wp_error( $post_id ) || ! $post_id ) { es_contact_redirect( 'error' ); }
    foreach ( array( 'name', 'phone', 'email', 'city', 'category' ) as $key ) {
        update_post_meta( $post_id, '_es_contact_' . $key, $data[ $key ] );
    }
    set_transient( $rate_key, 1, MINUTE_IN_SECONDS );
    $recipient = sanitize_email( es_opt( 'form_recipient' ) ?: es_opt( 'email' ) );
    $sent = false;
    if ( is_email( $recipient ) ) {
        $lines = array(
            'درخواست جدید از برگه تماس عرفان صنعت',
            'نام: ' . $data['name'], 'تلفن: ' . $data['phone'],
            'ایمیل: ' . ( $data['email'] ?: 'ثبت نشده' ), 'شهر: ' . ( $data['city'] ?: 'ثبت نشده' ),
            'موضوع: ' . es_contact_categories()[ $data['category'] ], '', 'پیام:', $data['message'],
        );
        $sent = wp_mail( $recipient, 'درخواست مشاوره جدید | عرفان صنعت', implode( "\n", $lines ) );
    }
    update_post_meta( $post_id, '_es_contact_notification_sent', $sent ? '1' : '0' );
    es_contact_redirect( 'saved' );
}
add_action( 'admin_post_nopriv_es_contact_submit', 'es_contact_submit' );
add_action( 'admin_post_es_contact_submit', 'es_contact_submit' );

add_action( 'add_meta_boxes_es_inquiry', function () {
    add_meta_box( 'es-inquiry-details', 'مشخصات درخواست‌کننده', 'es_contact_admin_details', 'es_inquiry', 'side' );
} );
function es_contact_admin_details( $post ) {
    $labels = array( 'name' => 'نام', 'phone' => 'تلفن', 'email' => 'ایمیل', 'city' => 'شهر', 'category' => 'موضوع' );
    echo '<div dir="rtl">';
    foreach ( $labels as $key => $label ) {
        $value = get_post_meta( $post->ID, '_es_contact_' . $key, true );
        if ( 'category' === $key ) { $value = es_contact_categories()[ $value ] ?? $value; }
        echo '<p><strong>' . esc_html( $label ) . ':</strong> ' . esc_html( $value ?: '—' ) . '</p>';
    }
    echo '<p><strong>اعلان ایمیل:</strong> ' . ( '1' === get_post_meta( $post->ID, '_es_contact_notification_sent', true ) ? 'ارسال شد' : 'ارسال نشد؛ تنظیمات ایمیل سایت را بررسی کنید.' ) . '</p></div>';
}
add_filter( 'manage_es_inquiry_posts_columns', function ( $columns ) {
    return array( 'cb' => $columns['cb'] ?? '', 'title' => 'درخواست', 'es_phone' => 'تلفن', 'es_category' => 'موضوع', 'date' => 'تاریخ' );
} );
add_action( 'manage_es_inquiry_posts_custom_column', function ( $column, $post_id ) {
    if ( 'es_phone' === $column ) { echo esc_html( get_post_meta( $post_id, '_es_contact_phone', true ) ); }
    if ( 'es_category' === $column ) { $category = get_post_meta( $post_id, '_es_contact_category', true ); echo esc_html( es_contact_categories()[ $category ] ?? '—' ); }
}, 10, 2 );

/** Integrate email-linked requests with WordPress's native personal-data tools. */
function es_contact_privacy_posts( $email, $page = 1, $eraser = false ) {
    if ( ! is_email( $email ) ) { return array(); }
    return get_posts( array(
        'post_type' => 'es_inquiry', 'post_status' => 'private', 'fields' => 'ids',
        'posts_per_page' => 20, 'offset' => $eraser ? 0 : ( max( 1, (int) $page ) - 1 ) * 20,
        'meta_key' => '_es_contact_email', 'meta_value' => sanitize_email( $email ),
    ) );
}
add_filter( 'wp_privacy_personal_data_exporters', function ( $exporters ) {
    $exporters['es-contact'] = array( 'exporter_friendly_name' => 'درخواست‌های مشاوره عرفان صنعت', 'callback' => function ( $email, $page = 1 ) {
        $ids = es_contact_privacy_posts( $email, $page );
        $items = array();
        foreach ( $ids as $id ) {
            $category = get_post_meta( $id, '_es_contact_category', true );
            $items[] = array( 'group_id' => 'es-contact', 'group_label' => 'درخواست‌های مشاوره', 'item_id' => 'es-inquiry-' . $id, 'data' => array(
                array( 'name' => 'نام', 'value' => get_post_meta( $id, '_es_contact_name', true ) ),
                array( 'name' => 'تلفن', 'value' => get_post_meta( $id, '_es_contact_phone', true ) ),
                array( 'name' => 'ایمیل', 'value' => get_post_meta( $id, '_es_contact_email', true ) ),
                array( 'name' => 'شهر', 'value' => get_post_meta( $id, '_es_contact_city', true ) ),
                array( 'name' => 'موضوع', 'value' => es_contact_categories()[ $category ] ?? $category ),
                array( 'name' => 'پیام', 'value' => get_post_field( 'post_content', $id ) ),
            ) );
        }
        return array( 'data' => $items, 'done' => count( $ids ) < 20 );
    } );
    return $exporters;
} );
add_filter( 'wp_privacy_personal_data_erasers', function ( $erasers ) {
    $erasers['es-contact'] = array( 'eraser_friendly_name' => 'درخواست‌های مشاوره عرفان صنعت', 'callback' => function ( $email, $page = 1 ) {
        $ids = es_contact_privacy_posts( $email, $page, true );
        $removed = false;
        foreach ( $ids as $id ) { if ( wp_delete_post( $id, true ) ) { $removed = true; } }
        return array( 'items_removed' => $removed, 'items_retained' => false, 'messages' => array(), 'done' => count( $ids ) < 20 );
    } );
    return $erasers;
} );
