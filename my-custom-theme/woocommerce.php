<?php get_header(); ?>
<?php if ( function_exists( 'is_product' ) && is_product() ) : ?>
    <?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/product-single' ); endwhile; ?>
<?php else : ?>
    <main id="main" class="inner-page shop-page"><div class="container"><?php if ( function_exists( 'woocommerce_content' ) ) { woocommerce_content(); } ?></div></main>
<?php endif; ?>
<?php get_footer(); ?>
