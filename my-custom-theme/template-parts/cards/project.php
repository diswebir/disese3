<?php
 defined( 'ABSPATH' ) || exit;
 $categories = get_the_terms( get_the_ID(), 'project_cat' );
 $locations = get_the_terms( get_the_ID(), 'project_location' );
 $number = isset( $args['number'] ) ? absint( $args['number'] ) : 0;
 ?>
<a class="project-card" href="<?php echo esc_url( get_permalink() ); ?>">
    <div class="project-card__image">
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'es-card', array( 'loading' => 'lazy' ) ); } else { echo '<img loading="lazy" src="' . esc_url( es_asset( 'images/light-element.jpg' ) ) . '" alt="">'; } ?>
        <?php if ( $number ) : ?><span class="project-card__number" aria-hidden="true"><?php echo esc_html( es_persian_digits( sprintf( '%02d', $number ) ) ); ?></span><?php endif; ?>
        <span class="project-card__arrow" aria-hidden="true">↗</span>
        <div class="project-card__overlay"><span><?php echo esc_html( $categories && ! is_wp_error( $categories ) ? $categories[0]->name : 'نورپردازی شهری' ); ?></span><h3><?php echo esc_html( get_the_title() ); ?></h3></div>
    </div>
    <div class="project-card__info"><span class="project-card__meta"><?php echo esc_html( $locations && ! is_wp_error( $locations ) ? $locations[0]->name : 'پروژه عرفان صنعت' ); ?> / <?php echo esc_html( $categories && ! is_wp_error( $categories ) ? $categories[0]->name : 'نورپردازی شهری' ); ?></span><p><?php echo esc_html( es_excerpt( 15 ) ); ?></p></div>
</a>
