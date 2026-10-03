<?php get_header(); ?>
<main id="main" class="inner-page"><div class="container"><div class="archive-heading"><span class="eyebrow">ERFAN SANAT</span><h1><?php echo esc_html( wp_get_document_title() ); ?></h1></div><div class="articles-grid"><?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts/cards/article' ); endwhile; else : ?><p>هنوز محتوایی منتشر نشده است.</p><?php endif; ?></div><?php the_posts_pagination( array( 'prev_text' => 'قبلی', 'next_text' => 'بعدی' ) ); ?></div></main>
<?php get_footer(); ?>
