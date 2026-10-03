<?php
defined( 'ABSPATH' ) || exit;
function es_meta_schema() {
    return array(
        'product' => array(
            '_es_is_purchasable_online' => array( 'label' => 'خرید آنلاین فعال', 'type' => 'checkbox' ),
            '_es_order_type' => array( 'label' => 'متد فروش', 'type' => 'select', 'choices' => array( 'online_cart' => 'سبد خرید آنلاین', 'phone_inquiry' => 'استعلام تلفنی', 'official_tender' => 'مناقصه رسمی' ) ),
            '_es_inquiry_phone' => array( 'label' => 'شماره کارشناس', 'type' => 'tel' ),
            '_es_custom_price_badge' => array( 'label' => 'برچسب جایگزین قیمت', 'type' => 'text' ),
            '_es_min_order_qty' => array( 'label' => 'حداقل متراژ / تیراژ', 'type' => 'number' ),
            '_es_production_lead_time' => array( 'label' => 'زمان تحویل', 'type' => 'text' ),
            '_es_wattage_rating' => array( 'label' => 'توان نامی (وات)', 'type' => 'number' ),
            '_es_chip_brand' => array( 'label' => 'برند چیپ LED', 'type' => 'text' ),
            '_es_technical_datasheet_pdf' => array( 'label' => 'کاتالوگ / دیتاشیت', 'type' => 'file' ),
            '_es_wiring_schematic_img' => array( 'label' => 'دیاگرام سیم‌بندی', 'type' => 'file' ),
            '_es_demo_video_url' => array( 'label' => 'لینک ویدیو آپارات / mp4', 'type' => 'url' ),
        ),
        'project' => array(
            '_es_project_client' => array( 'label' => 'کارفرما / شهرداری', 'type' => 'text' ),
            '_es_completion_date' => array( 'label' => 'زمان اجرا', 'type' => 'text' ),
            '_es_total_pixel_count' => array( 'label' => 'تعداد پیکسل / متراژ کابل', 'type' => 'number' ),
            '_es_total_power_kw' => array( 'label' => 'توان کل (KW)', 'type' => 'number' ),
            '_es_before_after_gallery' => array( 'label' => 'گالری قبل/بعد (شناسه تصاویر)', 'type' => 'gallery' ),
            '_es_project_drone_video' => array( 'label' => 'ویدیوی هلی‌شات', 'type' => 'file' ),
            '_es_project_map_coords' => array( 'label' => 'مختصات Lat, Lng', 'type' => 'text' ),
        ),
        'post' => array(
            '_es_reading_time_min' => array( 'label' => 'زمان مطالعه (دقیقه)', 'type' => 'number' ),
            '_es_technical_reviewer' => array( 'label' => 'مهندس ناظر / نگارنده', 'type' => 'text' ),
            '_es_software_project_file' => array( 'label' => 'فایل ضمیمه', 'type' => 'file' ),
            '_es_faq_schema_repeater' => array( 'label' => 'سوالات متداول', 'type' => 'faq' ),
        ),
    );
}
add_action( 'add_meta_boxes', function () {
    foreach ( es_meta_schema() as $type => $fields ) {
        if ( 'product' === $type && ! post_type_exists( 'product' ) ) { continue; }
        add_meta_box( 'es-details', 'اطلاعات تخصصی عرفان صنعت', 'es_render_meta_box', $type, 'normal', 'default' );
    }
} );
function es_render_meta_box( $post ) {
    wp_nonce_field( 'es_save_meta', 'es_meta_nonce' );
    echo '<div class="es-meta">';
    foreach ( es_meta_schema()[ $post->post_type ] as $key => $field ) {
        $value = get_post_meta( $post->ID, $key, true );
        echo '<div class="es-meta__row"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label>';
        if ( 'checkbox' === $field['type'] ) {
            echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="es_meta[' . esc_attr( $key ) . ']" value="1" ' . checked( $value, '1', false ) . '>';
        } elseif ( 'select' === $field['type'] ) {
            echo '<select id="' . esc_attr( $key ) . '" name="es_meta[' . esc_attr( $key ) . ']">';
            foreach ( $field['choices'] as $option => $label ) { echo '<option value="' . esc_attr( $option ) . '" ' . selected( $value, $option, false ) . '>' . esc_html( $label ) . '</option>'; }
            echo '</select>';
        } elseif ( 'faq' === $field['type'] ) {
            echo '<div class="es-faq-rows" data-faq-rows>';
            foreach ( is_array( $value ) ? $value : array() as $i => $row ) { es_faq_row( $i, $row ); }
            echo '</div><button type="button" class="button" data-add-faq>افزودن سؤال</button>';
        } else {
            $media = in_array( $field['type'], array( 'file', 'gallery' ), true );
            echo '<input id="' . esc_attr( $key ) . '" type="' . esc_attr( $media ? 'text' : $field['type'] ) . '" name="es_meta[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '"' . ( 'number' === $field['type'] ? ' min="0" step="any"' : '' ) . ( $media ? ' readonly' : '' ) . '>';
            if ( $media ) { echo '<button type="button" class="button" data-media-target="' . esc_attr( $key ) . '" data-multiple="' . esc_attr( 'gallery' === $field['type'] ? '1' : '0' ) . '">انتخاب از رسانه</button>'; }
        }
        echo '</div>';
    }
    echo '</div>';
}
function es_faq_row( $i, $row = array() ) {
    echo '<div class="es-repeat-row"><input aria-label="سؤال" placeholder="سؤال" name="es_meta[_es_faq_schema_repeater][' . esc_attr( $i ) . '][question]" value="' . esc_attr( $row['question'] ?? '' ) . '"><input aria-label="پاسخ" placeholder="پاسخ" name="es_meta[_es_faq_schema_repeater][' . esc_attr( $i ) . '][answer]" value="' . esc_attr( $row['answer'] ?? '' ) . '"><button type="button" class="button" data-remove-row>حذف</button></div>';
}
add_action( 'save_post', function ( $post_id, $post ) {
    if ( ! isset( $_POST['es_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['es_meta_nonce'] ) ), 'es_save_meta' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
    $schema = es_meta_schema();
    if ( ! isset( $schema[ $post->post_type ] ) ) { return; }
    $raw = isset( $_POST['es_meta'] ) && is_array( $_POST['es_meta'] ) ? wp_unslash( $_POST['es_meta'] ) : array();
    foreach ( $schema[ $post->post_type ] as $key => $field ) {
        $value = $raw[ $key ] ?? '';
        if ( 'checkbox' === $field['type'] ) { $value = empty( $value ) ? '0' : '1'; }
        elseif ( 'faq' === $field['type'] ) {
            $rows = array();
            foreach ( is_array( $value ) ? array_slice( $value, 0, 30 ) : array() as $row ) {
                if ( ! is_array( $row ) ) { continue; }
                $q = sanitize_text_field( $row['question'] ?? '' ); $a = sanitize_textarea_field( $row['answer'] ?? '' );
                if ( $q && $a ) { $rows[] = array( 'question' => $q, 'answer' => $a ); }
            }
            $value = $rows;
        } elseif ( 'gallery' === $field['type'] ) { $value = implode( ',', array_filter( array_map( 'absint', explode( ',', is_scalar( $value ) ? $value : '' ) ) ) ); }
        elseif ( 'file' === $field['type'] ) { $value = absint( $value ); }
        elseif ( 'number' === $field['type'] ) { $value = is_scalar( $value ) && is_numeric( $value ) ? max( 0, (float) $value ) : ''; }
        elseif ( 'url' === $field['type'] ) { $value = esc_url_raw( is_scalar( $value ) ? $value : '' ); }
        elseif ( 'select' === $field['type'] ) { $value = isset( $field['choices'][ $value ] ) ? $value : 'phone_inquiry'; }
        else { $value = sanitize_text_field( is_scalar( $value ) ? $value : '' ); }
        update_post_meta( $post_id, $key, $value );
    }
}, 10, 2 );
/* Product quoting: leave Woo's own purchasing logic in place for online products. */
function es_product_is_inquiry( $id ) {
    $mode = get_post_meta( $id, '_es_order_type', true );
    $online = get_post_meta( $id, '_es_is_purchasable_online', true );
    return ( $mode && 'online_cart' !== $mode ) || ( metadata_exists( 'post', $id, '_es_is_purchasable_online' ) && '1' !== (string) $online );
}
add_filter( 'woocommerce_is_purchasable', function ( $purchasable, $product ) {
    return es_product_is_inquiry( $product->get_id() ) ? false : $purchasable;
}, 10, 2 );
add_filter( 'woocommerce_get_price_html', function ( $html, $product ) {
    if ( ! es_product_is_inquiry( $product->get_id() ) ) { return $html; }
    $badge = get_post_meta( $product->get_id(), '_es_custom_price_badge', true );
    return '<span class="price es-price-badge">' . esc_html( $badge ?: 'استعلام قیمت' ) . '</span>';
}, 10, 2 );
add_action( 'woocommerce_single_product_summary', function () {
    if ( ! is_product() ) { return; }
    $id = get_the_ID(); $mode = get_post_meta( $id, '_es_order_type', true );
    if ( es_product_is_inquiry( $id ) ) {
        $badge = get_post_meta( $id, '_es_custom_price_badge', true );
        $phone = get_post_meta( $id, '_es_inquiry_phone', true ) ?: es_opt( 'phone' );
        echo '<div class="es-inquiry"><p>' . esc_html( $badge ?: 'قیمت پس از بررسی پروژه اعلام می‌شود.' ) . '</p><a class="btn btn--gold" href="' . esc_url( es_phone_url( $phone ) ) . '">' . esc_html( 'official_tender' === $mode ? 'تماس برای مناقصه' : 'استعلام قیمت و مشاوره' ) . '</a></div>';
    }
    $details = array( '_es_min_order_qty' => 'حداقل سفارش', '_es_production_lead_time' => 'زمان تحویل', '_es_wattage_rating' => 'توان نامی (وات)', '_es_chip_brand' => 'برند چیپ LED' );
    echo '<div class="es-product-specs">';
    foreach ( $details as $key => $label ) { $val = get_post_meta( $id, $key, true ); if ( '' !== (string) $val ) { echo '<p><strong>' . esc_html( $label ) . '</strong><span>' . esc_html( $val ) . '</span></p>'; } }
    foreach ( array( '_es_technical_datasheet_pdf' => 'دریافت دیتاشیت', '_es_wiring_schematic_img' => 'دیاگرام نصب' ) as $key => $label ) { $url = wp_get_attachment_url( absint( get_post_meta( $id, $key, true ) ) ); if ( $url ) { echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $label ) . ' ↗</a>'; } }
    $demo_url = get_post_meta( $id, '_es_demo_video_url', true );
    if ( $demo_url ) { echo '<a href="' . esc_url( $demo_url ) . '" target="_blank" rel="noopener noreferrer">تماشای ویدیوی محصول ↗</a>'; }
    echo '</div>';
}, 35 );
