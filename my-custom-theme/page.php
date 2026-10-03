<?php get_header(); while ( have_posts() ) : the_post(); ?>
<main id="main" class="inner-page single-page"><?php es_breadcrumbs(); ?><article class="container"><div class="single-intro"><span class="eyebrow">ERFAN SANAT</span><h1><?php echo esc_html( get_the_title() ); ?></h1></div><?php if ( has_post_thumbnail() ) : ?><div class="single-cover"><?php the_post_thumbnail( 'full' ); ?></div><?php endif; ?><div class="single-content entry-content"><?php the_content(); wp_link_pages(); ?></div></article></main>
<?php endwhile; get_footer(); ?>
