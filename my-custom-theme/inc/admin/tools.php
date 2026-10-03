<?php
defined( 'ABSPATH' ) || exit;
function es_render_tools() {
    echo '<section class="es-panel"><h2>درون‌ریزی ساختار و محتوای نمونه</h2><p>دسته‌بندی‌های محصولات، ویژگی‌ها، پروژه‌ها و مقالات همراه با چند نوشته نمونه و تصاویر محلی ایجاد می‌شوند. اجرای دوباره، محتوای موجود را تکرار نمی‌کند. برای محصول و ویژگی‌ها ووکامرس باید فعال باشد.</p><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="es_seed_demo">'; wp_nonce_field( 'es_seed_demo' ); echo '<button class="button button-primary" type="submit">ایجاد ساختار و دمو</button></form></section>';
    echo '<section class="es-panel"><h2>برگه اختصاصی تماس با ما</h2><p>برگه /contact/ را می‌سازد و تمپلیت تماس را به آن اختصاص می‌دهد. اگر برگه از قبل وجود داشته باشد فقط تمپلیت به آن متصل می‌شود؛ محتوای موجود پاک نخواهد شد.</p><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="es_create_contact_page">'; wp_nonce_field( 'es_create_contact_page' ); echo '<button class="button button-primary" type="submit">ایجاد یا اتصال برگه تماس</button></form></section>';
    echo '<section class="es-panel"><h2>خروجی تنظیمات</h2><p>فایل JSON تنها تنظیمات همین پوسته را شامل می‌شود، نه محتوای سایت یا فایل‌های رسانه.</p><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="es_export">'; wp_nonce_field( 'es_export' ); echo '<button class="button" type="submit">دریافت JSON</button></form></section>';
    echo '<section class="es-panel"><h2>درون‌ریزی تنظیمات</h2><form method="post" enctype="multipart/form-data" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="es_import">'; wp_nonce_field( 'es_import' ); echo '<input type="file" name="es_file" accept=".json,application/json" required> <button class="button" type="submit">درون‌ریزی JSON</button></form></section>';
}
function es_tools_auth( $action ) {
    if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'دسترسی غیرمجاز', '', array( 'response' => 403 ) ); }
    check_admin_referer( $action );
}
function es_tools_redirect( $notice ) { wp_safe_redirect( admin_url( 'admin.php?page=es-theme&tab=tools&es_notice=' . $notice ) ); exit; }
add_action( 'admin_post_es_export', function () {
    es_tools_auth( 'es_export' );
    nocache_headers(); header( 'Content-Type: application/json; charset=utf-8' ); header( 'Content-Disposition: attachment; filename="erfan-sanat-options.json"' );
    echo wp_json_encode( array( 'theme' => 'erfan-sanat', 'version' => 1, 'options' => es_opt() ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); exit;
} );
add_action( 'admin_post_es_import', function () {
    es_tools_auth( 'es_import' );
    if ( empty( $_FILES['es_file']['tmp_name'] ) || ! is_uploaded_file( $_FILES['es_file']['tmp_name'] ) || (int) $_FILES['es_file']['size'] > 1024 * 1024 ) { es_tools_redirect( 'error' ); }
    $data = json_decode( file_get_contents( $_FILES['es_file']['tmp_name'] ), true );
    if ( ! is_array( $data ) || ( $data['theme'] ?? '' ) !== 'erfan-sanat' || ! isset( $data['options'] ) || ! is_array( $data['options'] ) ) { es_tools_redirect( 'error' ); }
    update_option( 'es_theme_options', es_sanitize_options( $data['options'] ) ); es_tools_redirect( 'imported' );
} );
function es_ensure_contact_page() {
    $page = get_page_by_path( 'contact' );
    if ( $page ) { $id = $page->ID; }
    else {
        $id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'contact', 'post_title' => 'تماس با ما', 'post_content' => '' ), true );
    }
    if ( ! is_wp_error( $id ) && $id ) { update_post_meta( $id, '_wp_page_template', 'templates/template-contact.php' ); }
    return $id;
}
add_action( 'admin_post_es_create_contact_page', function () {
    es_tools_auth( 'es_create_contact_page' );
    $id = es_ensure_contact_page();
    es_tools_redirect( is_wp_error( $id ) || ! $id ? 'error' : 'contact' );
} );
/** Stable slug-based seeding; existing content, permalinks and the site's front page are never overwritten. */
function es_seed_terms( $taxonomy, $terms, $parent = 0 ) {
    if ( ! taxonomy_exists( $taxonomy ) ) { return; }
    foreach ( $terms as $slug => $item ) {
        $name = is_array( $item ) ? $item[0] : $item;
        $found = term_exists( $slug, $taxonomy );
        if ( ! $found ) { $found = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug, 'parent' => $parent ) ); }
        $id = is_array( $found ) ? (int) $found['term_id'] : (int) $found;
        if ( is_array( $item ) && $id && ! is_wp_error( $found ) ) { es_seed_terms( $taxonomy, $item[1], $id ); }
    }
}
function es_seed_post( $type, $slug, $title, $excerpt, $image, $terms = array() ) {
    $existing = get_posts( array( 'post_type' => $type, 'name' => $slug, 'posts_per_page' => 1, 'post_status' => 'any' ) );
    if ( $existing ) { return; }
    $id = wp_insert_post( array( 'post_type' => $type, 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $title, 'post_excerpt' => $excerpt, 'post_content' => '<p>' . esc_html( $excerpt ) . '</p>' ), true );
    if ( is_wp_error( $id ) ) { return; }
    foreach ( $terms as $taxonomy => $slugs ) { wp_set_object_terms( $id, $slugs, $taxonomy ); }
    $path = get_template_directory() . '/assets/images/' . $image;
    if ( file_exists( $path ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $upload = wp_upload_bits( 'es-' . $slug . '.jpg', null, file_get_contents( $path ) );
        if ( empty( $upload['error'] ) ) {
            $attachment = wp_insert_attachment( array( 'post_mime_type' => 'image/jpeg', 'post_title' => $title, 'post_status' => 'inherit' ), $upload['file'], $id );
            if ( ! is_wp_error( $attachment ) ) { wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $upload['file'] ) ); set_post_thumbnail( $id, $attachment ); }
        }
    }
    if ( 'product' === $type ) { update_post_meta( $id, '_es_order_type', 'phone_inquiry' ); update_post_meta( $id, '_es_is_purchasable_online', '0' ); update_post_meta( $id, '_es_custom_price_badge', 'قیمت پس از بررسی پروژه' ); }
}
add_action( 'admin_post_es_seed_demo', function () {
    es_tools_auth( 'es_seed_demo' );
    es_seed_terms( 'project_cat', array( 'urban-beautification' => 'آذین‌بندی و میادین شهری', 'light-tunnels-walkways' => 'تونل‌های نوری و پیاده‌راه‌ها', 'bridges-monuments' => 'نورپردازی پل‌ها و ابنیه تاریخی', 'parks-landscapes' => 'فضاهای سبز، آب‌نما و بوستان‌ها', 'occasional-lighting-projects' => 'المان‌های ویژه عید و مناسبتی' ) );
    es_seed_terms( 'project_location', array( 'isfahan' => 'اصفهان', 'tehran' => 'تهران', 'mashhad' => 'مشهد', 'shiraz' => 'شیراز', 'south-coasts' => 'بوشهر و بنادر جنوبی', 'other-cities' => 'سایر استان‌ها' ) );
    es_seed_terms( 'category', array( 'software-controllers-tutorials' => 'آموزش نرم‌افزار و کنترلرها', 'lighting-standards' => 'استانداردها و اصول مهندسی نورپردازی', 'troubleshooting-wiring' => 'رفع عیب و محاسبات افت ولتاژ', 'company-news-events' => 'اخبار و رویدادها' ) );
    es_seed_terms( 'post_tag', array( 'ws2811-controller' => 'کنترلر WS2811', 'dmx-addressing' => 'آدرس‌دهی DMX', 'voltage-drop-calculation' => 'محاسبه افت ولتاژ' ) );
    es_seed_terms( 'product_cat', array(
        'urban-lighting-elements' => array( 'المان‌های نوری شهری', array( 'square-elements' => 'المان‌های میدانی و میداندار', 'urban-chandeliers' => 'لوسترهای نوری شهری', 'pole-mounted-elements' => 'المان‌های پایه چراغی و سردیسی', 'light-trees' => 'درخت‌های نوری نخل و شکوفه', 'light-tunnels' => 'المان‌های تونل نوری و طاق نصرت' ) ),
        'decorative-light-strings' => array( 'ریسه‌ها و خطوط نوری تزیینی', array( 'pixel-strings-berry' => 'ریسه پیکسلی و بلوطی', 'neon-flex' => 'نئون فلکس ۲۲۰ و ۱۲ ولت', 'strip-lights' => 'ریسه شلنگی و نواری LED', 'string-fairy-lights' => 'ریسه‌های میکرو و سوزنی مناسبتی' ) ),
        'architectural-facade-lighting' => array( 'چراغ‌های نما و معماری', array( 'wall-washers' => 'وال‌واشر نمای ساختمان', 'flood-lights' => 'پروژکتورهای نورپردازی و محوطه‌ای', 'inground-lights' => 'چراغ‌های دفنی پارکتی و ضدآب', 'jet-lights' => 'جت‌لایت و نور خطی باریک' ) ),
        'pixel-point-lights' => array( 'سیستم‌های پیکسلی و پوینت‌لایت', array( 'dmx-point-lights' => 'پوینت‌لایت DMX فول‌کالر', 'spi-point-lights' => 'پوینت‌لایت SPI', 'digital-tubes-3d' => 'تیوب‌های دیجیتال و ۳D پیکسل' ) ),
        'controllers-power-supplies' => array( 'کنترلرها و ملزومات الکتریکال', array( 'dmx-artnet-controllers' => 'کنترلرهای DMX و آرت‌نت', 'spi-led-controllers' => 'کنترلرهای برنامه‌پذیر SD Card', 'switching-power-supplies' => 'منابع تغذیه سوئیچینگ بارانی و ضدآب', 'waterproof-connectors-cables' => 'کانکتور و متعلقات ضدآب' ) ),
    ) );
    if ( function_exists( 'wc_create_attribute' ) ) {
        $attributes = array(
            'voltage' => array( 'ولتاژ کاری', array( '12v-dc' => '۱۲ ولت DC', '24v-dc' => '۲۴ ولت DC', '220v-ac' => '۲۲۰ ولت AC' ) ),
            'ip-rating' => array( 'درجه حفاظت', array( 'ip65' => 'IP65', 'ip67' => 'IP67', 'ip68' => 'IP68' ) ),
            'control-protocol' => array( 'پروتکل کنترلی', array( 'dmx512' => 'DMX512', 'spi-ws2811' => 'SPI WS2811', 'spi-ucs1903' => 'SPI UCS1903', 'analog-mono' => 'آنالوگ تک‌رنگ' ) ),
            'light-color' => array( 'طیف نور', array( 'full-color-rgb' => 'فول‌کالر RGB', 'rgbw' => 'RGBW', 'sun-amber' => 'آفتابی', 'warm-white-3000k' => 'سفید گرم 3000K', 'cool-white-6000k' => 'سفید سرد 6000K' ) ),
            'beam-angle' => array( 'زاویه تابش لنز', array( 'lens-15-deg' => '۱۵ درجه', 'lens-45-deg' => '۴۵ درجه', 'lens-120-deg' => '۱۲۰ درجه' ) ),
            'body-material' => array( 'متریال بدنه', array( 'aluminum-diecast' => 'آلومینیوم دایکاست', 'galvanized-iron' => 'آهن گالوانیزه', 'polycarbonate' => 'پلی‌کربنات' ) ),
        );
        foreach ( $attributes as $slug => $definition ) {
            if ( ! wc_attribute_taxonomy_id_by_name( $slug ) ) { wc_create_attribute( array( 'name' => $definition[0], 'slug' => $slug, 'type' => 'select', 'has_archives' => true ) ); }
            $taxonomy = 'pa_' . $slug;
            if ( ! taxonomy_exists( $taxonomy ) ) { register_taxonomy( $taxonomy, 'product', array( 'hierarchical' => false, 'public' => true, 'rewrite' => false ) ); }
            es_seed_terms( $taxonomy, $definition[1] );
        }
        delete_transient( 'wc_attribute_taxonomies' );
    }
    es_seed_post( 'project', 'urban-light-tunnel', 'تونل نوری شهری', 'نورپردازی خلاقانه مسیرهای پیاده‌روی با تونل‌های نوری اختصاصی.', 'light-tunnel.jpg', array( 'project_cat' => array( 'light-tunnels-walkways' ), 'project_location' => array( 'isfahan' ) ) );
    es_seed_post( 'project', 'illuminated-city-sphere', 'گوی نورانی میدان', 'طراحی و ساخت المان حجمی نورانی برای میدان شهری.', 'light-sphere.jpg', array( 'project_cat' => array( 'urban-beautification' ), 'project_location' => array( 'tehran' ) ) );
    es_seed_post( 'project', 'urban-light-trees', 'درختان نوری هوشمند', 'درختان نورانی هوشمند برای فضای سبز و بوستان‌ها.', 'light-tree.jpg', array( 'project_cat' => array( 'parks-landscapes' ), 'project_location' => array( 'shiraz' ) ) );
    es_seed_post( 'post', 'urban-lighting-guide', 'راهنمای انتخاب نورپردازی شهری', 'معیارهای مهم طراحی، انتخاب تجهیزات و اجرای نورپردازی پایدار شهری.', 'hero.jpg', array( 'category' => array( 'lighting-standards' ) ) );
    if ( post_type_exists( 'product' ) ) {
        es_seed_post( 'product', 'smart-light-tree', 'درخت نوری هوشمند آرتام', 'درخت نوری پیکسلی RGB با کنترل هوشمند و طراحی متناسب با فضاهای شهری.', 'light-tree.jpg', array( 'product_cat' => array( 'light-trees' ) ) );
        es_seed_post( 'product', 'urban-light-sphere', 'گوی نورانی شهری', 'المان حجمی سفارشی برای میادین و فضاهای باز.', 'light-sphere.jpg', array( 'product_cat' => array( 'square-elements' ) ) );
        es_seed_post( 'product', 'led-light-tunnel', 'تونل نوری LED', 'سازه نوری سفارشی برای مسیرهای عبوری و جشنواره‌ها.', 'light-tunnel.jpg', array( 'product_cat' => array( 'light-tunnels' ) ) );
    }
    es_ensure_contact_page();
    flush_rewrite_rules(); es_tools_redirect( 'demo' );
} );
