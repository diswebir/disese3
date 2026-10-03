<?php defined( 'ABSPATH' ) || exit; ?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-top"><div><span class="eyebrow">LET'S BUILD SOMETHING BRIGHT</span><h2>با هم، شهری <em>روشن‌تر</em> می‌سازیم.</h2></div><a class="btn btn--gold" href="<?php echo esc_url( es_phone_url( es_opt( 'phone' ) ) ); ?>">شروع یک گفتگو <span aria-hidden="true">↗</span></a></div>
        <div class="footer-grid">
            <div class="footer-about"><?php get_template_part( 'template-parts/header/brand' ); ?><p>طراحی، تولید و اجرای هوشمندانه نورپردازی شهری و صنعتی. از ایده تا اجرا، کنار شما هستیم.</p></div>
            <div><h3>دسترسی سریع</h3><?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-links', 'fallback_cb' => 'es_fallback_menu', 'depth' => 1 ) ); ?></div>
            <div><h3>خدمات ما</h3><ul class="footer-links"><li><a href="<?php echo esc_url( es_projects_url() ); ?>">پروژه‌های نورپردازی</a></li><li><a href="<?php echo esc_url( es_shop_url() ); ?>">محصولات نورپردازی</a></li><li><a href="<?php echo esc_url( es_blog_url() ); ?>">مقالات فنی</a></li><li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">مشاوره تخصصی</a></li></ul></div>
            <div><h3>ارتباط با ما</h3><div class="footer-contact"><a dir="ltr" href="<?php echo esc_url( es_phone_url( es_opt( 'phone' ) ) ); ?>"><?php echo esc_html( es_opt( 'phone' ) ); ?></a><a href="<?php echo esc_url( 'mailto:' . sanitize_email( es_opt( 'email' ) ) ); ?>"><?php echo esc_html( es_opt( 'email' ) ); ?></a><p><?php echo esc_html( es_opt( 'address' ) ); ?></p></div><?php if ( es_opt( 'aparat_url' ) ) : ?><a class="social-link" href="<?php echo esc_url( es_opt( 'aparat_url' ) ); ?>" rel="noopener noreferrer">آپارات ↗</a><?php endif; ?> <?php if ( es_opt( 'instagram_url' ) ) : ?><a class="social-link" href="<?php echo esc_url( es_opt( 'instagram_url' ) ); ?>" rel="noopener noreferrer">اینستاگرام ↗</a><?php endif; ?></div>
        </div>
        <div class="footer-bottom"><span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( es_opt( 'footer_text' ) ); ?></span><span>ساخته‌شده با عشق به نور و شهر</span><a href="#top" class="back-to-top" aria-label="بازگشت به بالا">↑</a></div>
    </div>
</footer>
<nav class="mobile-dock" aria-label="دسترسی سریع موبایل"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span aria-hidden="true">⌂</span>خانه</a><a href="<?php echo esc_url( es_projects_url() ); ?>"><span aria-hidden="true">✧</span>پروژه‌ها</a><a href="<?php echo esc_url( es_shop_url() ); ?>"><span aria-hidden="true">◇</span>محصولات</a><a href="<?php echo esc_url( es_phone_url( es_opt( 'phone' ) ) ); ?>"><span aria-hidden="true">↗</span>تماس</a></nav>
<?php wp_footer(); ?>
</body></html>
