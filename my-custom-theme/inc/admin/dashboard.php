<?php
defined( 'ABSPATH' ) || exit;
add_action( 'admin_menu', function () {
    add_menu_page( 'تنظیمات عرفان صنعت', 'عرفان صنعت', 'manage_options', 'es-theme', 'es_render_dashboard', 'dashicons-admin-appearance', 59 );
} );
function es_render_dashboard() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $schema = es_options_schema();
    $tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'identity';
    if ( ! isset( $schema[ $tab ] ) && 'tools' !== $tab ) { $tab = 'identity'; }
    echo '<div class="wrap es-admin" dir="rtl"><div class="es-admin__head"><span class="es-admin__eyebrow">ERFAN SANAT • THEME STUDIO</span><h1>تنظیمات روشنای شهر</h1><p>مدیریت یکپارچه هویت، محتوای صفحه نخست و راه‌های ارتباطی</p></div>';
    if ( isset( $_GET['es_notice'] ) ) {
        $notices = array( 'saved' => 'تنظیمات ذخیره شد.', 'imported' => 'تنظیمات درون‌ریزی شد.', 'demo' => 'دسته‌بندی‌ها و محتوای نمونه آماده شدند.', 'error' => 'فایل درون‌ریزی معتبر نیست.' );
        $notice = sanitize_key( wp_unslash( $_GET['es_notice'] ) );
        if ( isset( $notices[ $notice ] ) ) { echo '<div class="notice notice-' . ( 'error' === $notice ? 'error' : 'success' ) . ' inline"><p>' . esc_html( $notices[ $notice ] ) . '</p></div>'; }
    }
    echo '<nav class="es-tabs" aria-label="بخش‌های تنظیمات">';
    foreach ( $schema as $id => $item ) { echo '<a class="es-tabs__link ' . ( $id === $tab ? 'is-active' : '' ) . '" href="' . esc_url( admin_url( 'admin.php?page=es-theme&tab=' . $id ) ) . '">' . esc_html( $item['label'] ) . '</a>'; }
    echo '<a class="es-tabs__link ' . ( 'tools' === $tab ? 'is-active' : '' ) . '" href="' . esc_url( admin_url( 'admin.php?page=es-theme&tab=tools' ) ) . '">ابزارها و دمو</a></nav>';
    if ( 'tools' === $tab ) { es_render_tools(); }
    else {
        echo '<form action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" method="post"><input type="hidden" name="action" value="es_save_options"><input type="hidden" name="tab" value="' . esc_attr( $tab ) . '">';
        wp_nonce_field( 'es_save_options' );
        /* Every tab submits only its own fields; the rest are retained server-side. */
        foreach ( $schema[ $tab ]['sections'] as $section ) {
            echo '<section class="es-panel"><h2>' . esc_html( $section['title'] ) . '</h2>';
            foreach ( $section['fields'] as $key => $field ) { es_render_option_field( $key, $field ); }
            echo '</section>';
        }
        echo '<div class="es-admin__actions"><button class="button button-primary button-hero" type="submit">ذخیره تغییرات</button></div></form>';
    }
    echo '</div>';
}
add_action( 'admin_post_es_save_options', function () {
    if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'دسترسی غیرمجاز', '', array( 'response' => 403 ) ); }
    check_admin_referer( 'es_save_options' );
    $tab = isset( $_POST['tab'] ) ? sanitize_key( wp_unslash( $_POST['tab'] ) ) : 'identity';
    $schema = es_options_schema();
    if ( ! isset( $schema[ $tab ] ) ) { wp_die( 'بخش نامعتبر', '', array( 'response' => 400 ) ); }
    $allowed = array();
    foreach ( $schema[ $tab ]['sections'] as $section ) { $allowed = array_merge( $allowed, array_keys( $section['fields'] ) ); }
    $submitted = isset( $_POST['es_options'] ) && is_array( $_POST['es_options'] ) ? wp_unslash( $_POST['es_options'] ) : array();
    $submitted = array_intersect_key( $submitted, array_flip( $allowed ) );
    $clean = es_sanitize_options( $submitted );
    $current = get_option( 'es_theme_options', array() );
    $current = is_array( $current ) ? $current : array();
    update_option( 'es_theme_options', array_merge( $current, array_intersect_key( $clean, array_flip( $allowed ) ) ) );
    wp_safe_redirect( admin_url( 'admin.php?page=es-theme&tab=' . $tab . '&es_notice=saved' ) ); exit;
} );
