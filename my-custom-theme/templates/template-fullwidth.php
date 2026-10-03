<?php
/* Template Name: تمام‌عرض */
get_header(); while ( have_posts() ) : the_post(); ?>
<main id="main" class="inner-page fullwidth-page"><div class="container"><h1><?php echo esc_html( get_the_title() ); ?></h1><div class="entry-content"><?php the_content(); wp_link_pages(); ?></div></div></main>
<?php endwhile; get_footer(); ?>
