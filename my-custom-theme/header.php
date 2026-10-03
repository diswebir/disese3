<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">رفتن به محتوای اصلی</a>
<header class="site-header" id="site-header">
    <div class="container site-header__inner">
        <?php get_template_part( 'template-parts/header/brand' ); ?>
        <nav class="primary-nav" id="primary-nav" aria-label="منوی اصلی">
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav-list', 'fallback_cb' => 'es_fallback_menu', 'depth' => 2 ) ); ?>
        </nav>
        <div class="site-header__actions">
            <details class="header-search"><summary aria-label="باز کردن جستجو" title="جستجو"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.7"/><path d="m16 16 5 5"/></svg></summary><div class="header-search__panel"><?php get_search_form(); ?></div></details>
            <?php if ( function_exists( 'WC' ) ) : $cart = WC()->cart; ?><a class="header-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="سبد خرید، <?php echo esc_attr( $cart ? $cart->get_cart_contents_count() : 0 ); ?> کالا"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 4h2l2.2 11h11.6L21 7H6"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg><?php if ( $cart && $cart->get_cart_contents_count() ) : ?><span class="header-cart__count"><?php echo esc_html( es_persian_digits( $cart->get_cart_contents_count() ) ); ?></span><?php endif; ?></a><?php endif; ?>
            <a class="header-phone" href="<?php echo esc_url( es_phone_url( es_opt( 'phone' ) ) ); ?>"><span class="header-phone__icon" aria-hidden="true">↗</span><span><small>مشاوره و سفارش</small><b dir="ltr"><?php echo esc_html( es_opt( 'phone' ) ); ?></b></span></a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="باز کردن منو"><span></span><span></span><span></span></button>
        </div>
    </div>
</header>
