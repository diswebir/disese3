<?php defined( 'ABSPATH' ) || exit; ?>
<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
    <?php $logo_id = absint( es_opt( 'logo' ) );
    $logo = $logo_id ? wp_get_attachment_image( $logo_id, 'medium', false, array( 'class' => 'brand__image', 'alt' => get_bloginfo( 'name' ) ) ) : '';
    if ( $logo ) :
        echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup is escaped by WordPress.
    else : ?>
        <span class="brand__mark" aria-hidden="true"><span></span><span></span><span></span></span>
        <span class="brand__text"><strong>عرفان صنعت</strong><small>ERFAN SANAT</small></span>
    <?php endif; ?>
</a>
