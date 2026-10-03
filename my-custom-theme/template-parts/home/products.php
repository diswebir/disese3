<section class="section products-section" id="products"><div class="container">
    <div class="section-head"><div><span class="eyebrow"><span class="eyebrow-line"></span> محبوب‌ترین تولیدات / OUR PRODUCTS</span><h2>برخی از محصولات <em>عرفان صنعت</em></h2><p>با بهره‌گیری از به‌روزترین تجهیزات و کارشناسان متخصص، مجموعه‌ای از محصولات نورپردازی پیشرفته را برای شهر شما عرضه می‌کنیم.</p></div><a class="text-link" href="<?php echo esc_url( es_shop_url() ); ?>">مشاهده همه محصولات ↖</a></div>
    <div class="products-intro"><span class="products-intro__symbol" aria-hidden="true">✳</span><span>راهکارهای نورپردازی هوشمند</span><strong>از ایده تا روشنایی</strong><span class="products-intro__edition">ES / COLLECTION ۲۰۲۶</span></div>
    <div class="products-grid">
    <?php $products = post_type_exists( 'product' ) ? new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => 5, 'no_found_rows' => true ) ) : null;
    if ( $products && $products->have_posts() ) :
        while ( $products->have_posts() ) : $products->the_post(); get_template_part( 'template-parts/cards/product' ); endwhile;
        wp_reset_postdata();
    else :
        $samples = array(
            array( 'درختان نوری هوشمند', 'light-tree.jpg', '۱۶ میلیون رنگ و کنترل با اپلیکیشن', 'درختان نوری', 'پیکسل RGB · کنترل هوشمند' ),
            array( 'خورشید‌نما', 'sun-light.jpg', 'از ۳۲ تا ۴۸ پره، نورپردازی تمام‌رنگی', 'المان‌های میدانی', 'قطر تا ۳.۳ متر · NRF' ),
            array( 'لوستر فضای باز', 'chandelier.jpg', 'شکوه نور برای میدان‌ها و پیاده‌راه‌ها', 'لوسترهای شهری', 'ارتفاع تا ۷ متر' ),
            array( 'ریسه‌های LED', 'string-lights.jpg', 'ریسه فندقی، بلوطی و سوزنی مقاوم', 'ریسه‌های تزیینی', 'مقاوم در برابر آب · IP67' ),
            array( 'پوینت‌لایت پیکسل', 'point-light.jpg', 'نورپردازی دیجیتال با کنترل دقیق هر پیکسل', 'سیستم‌های پیکسلی', 'فول‌کالر هوشمند · IP68' ),
        );
        foreach ( $samples as $i => $item ) : ?>
            <a class="product-card" href="<?php echo esc_url( es_shop_url() ); ?>"><div class="product-card__image"><img loading="lazy" src="<?php echo esc_url( es_asset( 'images/' . $item[1] ) ); ?>" alt="<?php echo esc_attr( $item[0] ); ?>"><span class="product-card__badge"><?php echo 3 === $i ? 'قابل خرید' : 'طراحی اختصاصی'; ?></span><span class="product-card__arrow" aria-hidden="true">↗</span></div><div class="product-card__info"><span class="eyebrow"><?php echo esc_html( $item[3] ); ?></span><h3><?php echo esc_html( $item[0] ); ?></h3><p><?php echo esc_html( $item[2] ); ?></p><div class="product-card__bottom"><span><?php echo esc_html( $item[4] ); ?></span><span class="product-card__link">مشاهده محصول ←</span></div></div></a>
        <?php endforeach;
    endif; ?>
    </div>
    <div class="custom-banner"><div><span class="eyebrow">MADE FOR YOUR VISION</span><h3>طراحی و ساخت سفارشی، مطابق طرح اختصاصی شما</h3><p>اگر طرح خاصی در ذهن دارید، تیم مهندسی ما از ایده تا اجرای نهایی در کنار شماست.</p></div><a class="btn btn--gold" href="<?php echo esc_url( es_contact_form_url() ); ?>">شروع سفارش اختصاصی ↗</a></div>
</div></section>
