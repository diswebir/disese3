<?php
/** Editorial single-product layout. WooCommerce still owns prices, forms, gallery and reviews. */
defined( 'ABSPATH' ) || exit;
global $product;
$product = wc_get_product( get_the_ID() );
if ( ! $product ) { return; }
$id = $product->get_id();
$categories = get_the_terms( $id, 'product_cat' );
$category = $categories && ! is_wp_error( $categories ) ? $categories[0] : null;
$inquiry = es_product_is_inquiry( $id );
$phone = get_post_meta( $id, '_es_inquiry_phone', true ) ?: es_opt( 'phone' );
?>
<main id="main" class="woocommerce es-single-product">
    <div class="container es-single-product__crumbs" aria-label="مسیر صفحه">
        <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) { woocommerce_breadcrumb( array( 'delimiter' => '<span class="es-single-product__divider" aria-hidden="true">/</span>', 'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="مسیر صفحه">', 'wrap_after' => '</nav>' ) ); } ?>
        <a class="es-single-product__back" href="<?php echo esc_url( es_shop_url() ); ?>">← بازگشت به محصولات</a>
    </div>
    <div class="container">
        <?php do_action( 'woocommerce_before_single_product' ); ?>
        <article <?php wc_product_class( 'es-product', $product ); ?> aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
            <div class="es-product__top">
                <div class="es-product__media">
                    <div class="es-product__gallery">
                        <?php if ( $product->get_image_id() ) { do_action( 'woocommerce_before_single_product_summary' ); }
                        else { ?><div class="es-product__fallback" role="img" aria-label="تصویر محصول هنوز بارگذاری نشده است"><span>ES</span><small>تصویر محصول به‌زودی افزوده می‌شود</small></div><?php } ?>
                    </div>
                    <div class="es-product__media-foot"><span>ERFAN SANAT — URBAN LIGHTING</span><span>۰۱ / محصول اختصاصی</span></div>
                </div>
                <div class="es-product__information">
                    <div class="es-product__overline"><span class="eyebrow">PRODUCT / محصول عرفان صنعت</span><span class="es-product__serial">ES / <?php echo esc_html( es_persian_digits( $id ) ); ?></span></div>
                    <?php if ( $category ) : ?><a class="es-product__category" href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?> <span aria-hidden="true">↗</span></a><?php endif; ?>
                    <div class="summary entry-summary es-product__summary">
                        <?php do_action( 'woocommerce_single_product_summary' ); ?>
                    </div>
                    <div class="es-product__help"><span class="es-product__help-icon" aria-hidden="true">✳</span><div><strong>برای انتخاب بهتر، کنار شماییم</strong><p>برای اطلاع از جزئیات فنی و شرایط سفارش با کارشناسان ما گفتگو کنید.</p></div><a href="<?php echo esc_url( es_phone_url( $phone ) ); ?>" aria-label="تماس با کارشناسان فروش">↗</a></div>
                </div>
            </div>
            <div class="es-product__benefits" aria-label="خدمات همراه محصول">
                <div><span aria-hidden="true">✧</span><div><strong>طراحی متناسب با پروژه</strong><small>بررسی نیاز هر فضای شهری</small></div></div>
                <div><span aria-hidden="true">◇</span><div><strong>مشاوره تخصصی</strong><small>انتخاب محصول با راهنمایی کارشناسان</small></div></div>
                <div><span aria-hidden="true">↗</span><div><strong>ارتباط مستقیم</strong><small>پاسخگویی برای استعلام و سفارش</small></div></div>
            </div>
            <section class="es-product__details" aria-label="اطلاعات محصول">
                <div class="es-product__section-title"><div><span class="eyebrow">PRODUCT DETAILS / جزئیات محصول</span><h2>جزئیاتی برای یک انتخاب <em>روشن‌تر.</em></h2></div><span class="es-product__section-number">۰۱ / ۰۲</span></div>
                <?php if ( function_exists( 'woocommerce_output_product_data_tabs' ) ) { woocommerce_output_product_data_tabs(); } ?>
            </section>
            <section class="es-product__consult" aria-labelledby="es-product-consult-title">
                <div><span class="eyebrow">LET'S LIGHT IT UP / مشاوره تخصصی</span><h2 id="es-product-consult-title">برای پروژه‌تان به <em>نورِ درست</em> فکر می‌کنید؟</h2><p>از انتخاب محصول تا بررسی شرایط اجرا، کارشناسان عرفان صنعت همراه شما هستند.</p></div>
                <a class="btn btn--gold" href="<?php echo esc_url( es_phone_url( $phone ) ); ?>">مشاوره و استعلام قیمت <span aria-hidden="true">↗</span></a>
            </section>
        </article>
        <?php do_action( 'woocommerce_after_single_product' ); ?>
    </div>
    <?php
    $related_ids = wc_get_related_products( $id, 3 );
    $related = $related_ids ? wc_get_products( array( 'status' => 'publish', 'include' => $related_ids, 'limit' => 3, 'orderby' => 'include' ) ) : wc_get_products( array( 'status' => 'publish', 'limit' => 3, 'exclude' => array( $id ), 'orderby' => 'date', 'order' => 'DESC' ) );
    if ( $related ) : ?>
    <section class="es-product__related container" aria-labelledby="es-product-related-heading">
        <div class="es-product__section-title"><div><span class="eyebrow">EXPLORE MORE / محصولات بیشتر</span><h2 id="es-product-related-heading">محصولات دیگری که <em>می‌درخشند.</em></h2></div><a class="text-link" href="<?php echo esc_url( es_shop_url() ); ?>">مشاهده همه محصولات ←</a></div>
        <div class="es-product__related-grid">
            <?php foreach ( $related as $item ) :
                $item_id = $item->get_id();
                $src = $item->get_image_id() ? wp_get_attachment_image_url( $item->get_image_id(), 'es-card' ) : wc_placeholder_img_src();
                $item_inquiry = es_product_is_inquiry( $item_id );
            ?><a class="es-product__related-card" href="<?php echo esc_url( get_permalink( $item_id ) ); ?>"><div class="es-product__related-image"><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $item->get_name() ); ?>" loading="lazy"><span aria-hidden="true">↗</span></div><div class="es-product__related-info"><span class="eyebrow">محصول عرفان صنعت</span><h3><?php echo esc_html( $item->get_name() ); ?></h3><span><?php echo $item_inquiry ? 'استعلام قیمت و مشاوره' : wp_kses_post( $item->get_price_html() ); ?></span></div></a><?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</main>
