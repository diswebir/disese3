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
        <div class="site-header__actions"><a class="header-phone" href="<?php echo esc_url( es_phone_url( es_opt( 'phone' ) ) ); ?>"><span class="header-phone__icon" aria-hidden="true">↗</span><span><small>مشاوره و سفارش</small><b dir="ltr"><?php echo esc_html( es_opt( 'phone' ) ); ?></b></span></a><button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="باز کردن منو"><span></span><span></span><span></span></button></div>
    </div>
</header>
