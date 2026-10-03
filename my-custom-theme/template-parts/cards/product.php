<?php
 defined( 'ABSPATH' ) || exit;
 $product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
 $categories = get_the_terms( get_the_ID(), 'product_cat' );
 $category = $categories && ! is_wp_error( $categories ) ? $categories[0]->name : 'محصولات نورپردازی';
 $inquiry = $product && es_product_is_inquiry( get_the_ID() );
 $badge = get_post_meta( get_the_ID(), '_es_custom_price_badge', true );
 ?>
<a class="product-card" href="<?php echo esc_url( get_permalink() ); ?>">
    <div class="product-card__image">
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'es-card', array( 'loading' => 'lazy' ) ); } else { echo '<img loading="lazy" src="' . esc_url( es_asset( 'images/point-light.jpg' ) ) . '" alt="">'; } ?>
        <span class="product-card__badge"><?php echo esc_html( $inquiry ? 'استعلام قیمت' : 'محصول عرفان صنعت' ); ?></span>
        <span class="product-card__arrow" aria-hidden="true">↗</span>
    </div>
    <div class="product-card__info"><span class="eyebrow"><?php echo esc_html( $category ); ?></span><h3><?php echo esc_html( get_the_title() ); ?></h3><p><?php echo esc_html( es_excerpt( 13 ) ); ?></p><div class="product-card__bottom"><span><?php if ( $product && ! $inquiry && $product->get_price() ) { echo wp_kses_post( $product->get_price_html() ); } else { echo esc_html( $badge ?: 'طراحی و تولید اختصاصی' ); } ?></span><span class="product-card__link">مشاهده محصول ←</span></div></div>
</a>
