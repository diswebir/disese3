<?php get_header(); ?>
<main id="main" class="inner-page"><div class="container"><div class="archive-heading"><span class="eyebrow">SEARCH</span><h1>نتایج جستجو برای «<?php echo esc_html( get_search_query() ); ?>»</h1><?php get_search_form(); ?></div><div class="articles-grid"><?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts/cards/article' ); endwhile; else : ?><p>نتیجه‌ای یافت نشد. عبارت دیگری جستجو کنید.</p><?php endif; ?></div><?php the_posts_pagination(); ?></div></main>
<?php get_footer(); ?>
