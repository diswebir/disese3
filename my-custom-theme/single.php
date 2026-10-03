<?php get_header(); ?>
<?php while ( have_posts() ) : the_post();
    $project = 'project' === get_post_type();
    $faqs = array();
    ?>
<main id="main" class="inner-page single-page">
    <?php es_breadcrumbs(); ?>
    <article class="container">
        <div class="single-intro">
            <span class="eyebrow"><?php echo esc_html( $project ? 'PROJECT / پروژه' : 'JOURNAL / مقاله' ); ?></span>
            <h1><?php echo esc_html( get_the_title() ); ?></h1>
            <p><?php echo esc_html( get_the_excerpt() ); ?></p>
            <div class="single-meta">
                <?php if ( $project ) :
                    $client = get_post_meta( get_the_ID(), '_es_project_client', true );
                    $date = get_post_meta( get_the_ID(), '_es_completion_date', true );
                    $location = get_the_terms( get_the_ID(), 'project_location' );
                    if ( $client ) : ?><span>کارفرما: <?php echo esc_html( $client ); ?></span><?php endif;
                    if ( $date ) : ?><span>زمان اجرا: <?php echo esc_html( $date ); ?></span><?php endif;
                    if ( $location && ! is_wp_error( $location ) ) : ?><span>موقعیت: <?php echo esc_html( $location[0]->name ); ?></span><?php endif;
                else : ?>
                    <span><?php echo esc_html( get_the_date() ); ?></span>
                    <?php $reading = get_post_meta( get_the_ID(), '_es_reading_time_min', true );
                    $reviewer = get_post_meta( get_the_ID(), '_es_technical_reviewer', true );
                    if ( $reading ) : ?><span><?php echo esc_html( $reading ); ?> دقیقه مطالعه</span><?php endif;
                    if ( $reviewer ) : ?><span>نگارنده: <?php echo esc_html( $reviewer ); ?></span><?php endif;
                endif; ?>
            </div>
        </div>
        <?php if ( has_post_thumbnail() ) : ?><div class="single-cover"><?php the_post_thumbnail( 'full' ); ?></div><?php endif; ?>
        <div class="single-content entry-content">
            <?php the_content(); wp_link_pages(); ?>
            <?php if ( $project ) {
                $specs = array( '_es_total_pixel_count' => 'تعداد پیکسل / متراژ', '_es_total_power_kw' => 'توان کل (کیلووات)', '_es_project_map_coords' => 'مختصات موقعیت' );
                echo '<dl class="project-specs">';
                foreach ( $specs as $key => $label ) {
                    $val = get_post_meta( get_the_ID(), $key, true );
                    if ( $val ) { echo '<div><dt>' . esc_html( $label ) . '</dt><dd>' . esc_html( $val ) . '</dd></div>'; }
                }
                echo '</dl>';
                $gallery = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( get_the_ID(), '_es_before_after_gallery', true ) ) ) );
                if ( $gallery ) {
                    echo '<div class="single-gallery">';
                    foreach ( $gallery as $image_id ) { echo wp_get_attachment_image( $image_id, 'large' ); }
                    echo '</div>';
                }
                $video = wp_get_attachment_url( absint( get_post_meta( get_the_ID(), '_es_project_drone_video', true ) ) );
                if ( $video ) { echo '<p><a class="text-link" href="' . esc_url( $video ) . '">مشاهده ویدیوی پروژه ↗</a></p>'; }
            } else {
                $file = wp_get_attachment_url( absint( get_post_meta( get_the_ID(), '_es_software_project_file', true ) ) );
                if ( $file ) { echo '<p><a class="text-link" href="' . esc_url( $file ) . '">دریافت فایل ضمیمه ↗</a></p>'; }
                $faqs = get_post_meta( get_the_ID(), '_es_faq_schema_repeater', true );
                if ( is_array( $faqs ) && $faqs ) {
                    echo '<section class="faq"><h2>سوالات متداول</h2>';
                    foreach ( $faqs as $faq ) { echo '<details><summary>' . esc_html( $faq['question'] ?? '' ) . '</summary><p>' . esc_html( $faq['answer'] ?? '' ) . '</p></details>'; }
                    echo '</section>';
                }
            } ?>
        </div>
    </article>
</main>
<?php
    if ( ! $project && is_array( $faqs ) && $faqs ) {
        $entities = array();
        foreach ( $faqs as $faq ) {
            if ( ! empty( $faq['question'] ) && ! empty( $faq['answer'] ) ) {
                $entities[] = array( '@type' => 'Question', 'name' => $faq['question'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $faq['answer'] ) );
            }
        }
        if ( $entities ) {
            echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $entities ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE ) . '</script>';
        }
    }
endwhile;
get_footer();
