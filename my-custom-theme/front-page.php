<?php get_header(); ?>
<main id="main">
    <?php
    $hero_title = (string) es_opt( 'hero_title' );
    $hero_highlight = (string) es_opt( 'hero_highlight' );
    $highlight_at = '' !== $hero_highlight ? strpos( $hero_title, $hero_highlight ) : false;
    $cities = (array) es_opt( 'cities' );
    ?>
    <section class="hero" id="top" style="--hero-image:url('<?php echo esc_url( es_image( 'hero_image', 'hero.jpg' ) ); ?>')">
        <div class="hero__glow" aria-hidden="true"></div>
        <div class="container hero__inner">
            <div class="hero__content">
                <div class="hero__badge"><span class="pulse-dot" aria-hidden="true"></span><?php echo esc_html( es_opt( 'hero_eyebrow' ) ); ?><span class="badge-divider" aria-hidden="true"></span> گواهی ISO 9001</div>
                <p class="hero__overline">THE ART OF URBAN LIGHTING <span aria-hidden="true"></span> EST. 2000</p>
                <h1><?php if ( false !== $highlight_at ) :
                    echo esc_html( substr( $hero_title, 0, $highlight_at ) ) . '<em>' . esc_html( $hero_highlight ) . '</em>' . esc_html( substr( $hero_title, $highlight_at + strlen( $hero_highlight ) ) );
                else : echo esc_html( $hero_title ); endif; ?></h1>
                <p class="hero__description"><?php echo esc_html( es_opt( 'hero_text' ) ); ?></p>
                <div class="hero__buttons"><a class="btn btn--gold" href="<?php echo esc_url( es_projects_url() ); ?>"><?php echo esc_html( es_opt( 'hero_primary_label' ) ); ?> <span aria-hidden="true">↗</span></a><a class="btn btn--outline" href="#contact"><?php echo esc_html( es_opt( 'hero_secondary_label' ) ); ?> <span aria-hidden="true">←</span></a></div>
            </div>
        </div>
        <div class="container hero__bottom">
            <div class="hero__stats"><?php foreach ( (array) es_opt( 'stats' ) as $stat ) : ?><div><strong><?php echo esc_html( $stat['value'] ?? '' ); ?></strong><span><?php echo esc_html( $stat['label'] ?? '' ); ?></span></div><?php endforeach; ?></div>
            <a href="#projects" class="hero__scroll">اسکرول به پایین <span aria-hidden="true">↓</span></a>
        </div>
        <span class="hero__vertical" aria-hidden="true">URBAN LIGHTING — EST. ۲۰۰۰</span>
    </section>
    <div class="cities" aria-label="شهرهای همکار">
        <div class="container cities__intro"><span class="eyebrow">جلب اعتماد شما باعث افتخار ماست</span><p>مفتخریم از همکاری با شهرهای سراسر ایران</p></div>
        <?php if ( $cities ) : ?>
        <div class="cities__track"><div class="cities__marquee">
            <div class="cities__list"><?php foreach ( $cities as $city ) : ?><span><?php echo esc_html( $city['name'] ?? '' ); ?></span><?php endforeach; ?></div>
            <div class="cities__list" aria-hidden="true"><?php foreach ( $cities as $city ) : ?><span><?php echo esc_html( $city['name'] ?? '' ); ?></span><?php endforeach; ?></div>
        </div></div>
        <?php endif; ?>
    </div>
    <?php if ( es_opt( 'show_projects' ) ) { get_template_part( 'template-parts/home/projects' ); } ?>
    <?php get_template_part( 'template-parts/home/about' ); ?>
    <?php if ( es_opt( 'show_products' ) ) { get_template_part( 'template-parts/home/products' ); } ?>
    <?php get_template_part( 'template-parts/home/services' ); ?>
    <?php if ( es_opt( 'show_blog' ) ) { get_template_part( 'template-parts/home/blog' ); } ?>
    <?php get_template_part( 'template-parts/home/contact' ); ?>
</main>
<?php get_footer(); ?>
