<section class="section projects-section" id="projects"><div class="container">
    <div class="section-head"><div><span class="eyebrow"><span class="eyebrow-line"></span> نمونه‌کارهای برتر / OUR PROJECTS</span><h2>پروژه‌های <em>نورپردازی شهری</em></h2><p>نمونه پروژه‌های نورپردازی حرفه‌ای شهری؛ هر پروژه، روایتی از هنر، مهندسی و نور است که هویت بصری شهر را برای همیشه دگرگون می‌کند.</p></div><a class="text-link" href="<?php echo esc_url( es_projects_url() ); ?>">آرشیو تمام پروژه‌ها <span aria-hidden="true">↖</span></a></div>
    <div class="projects-grid">
        <?php $projects = new WP_Query( array( 'post_type' => 'project', 'posts_per_page' => 3, 'no_found_rows' => true ) );
        if ( $projects->have_posts() ) :
            $position = 0;
            while ( $projects->have_posts() ) : $projects->the_post();
                get_template_part( 'template-parts/cards/project', null, array( 'number' => ++$position ) );
            endwhile;
            wp_reset_postdata();
        else :
            $samples = array(
                array( 'تونل نوری', 'light-tunnel.jpg', 'کمانی، نیم‌آرک و طاق نصرت؛ مسیری جادویی برای جشنواره‌ها و پیاده‌راه‌های شهری' ),
                array( 'گوی نورانی', 'light-sphere.jpg', 'المان حجمی نورافکن؛ نقطه‌کانونی میادین و فضاهای باز شهری' ),
                array( 'المان نوری', 'light-element.jpg', 'سازه‌های نوری اختصاصی با هویت بصری ماندگار' ),
            );
            foreach ( $samples as $i => $item ) : ?>
                <a class="project-card" href="<?php echo esc_url( es_projects_url() ); ?>"><div class="project-card__image"><img loading="lazy" src="<?php echo esc_url( es_asset( 'images/' . $item[1] ) ); ?>" alt="<?php echo esc_attr( $item[0] ); ?>"><span class="project-card__number" aria-hidden="true"><?php echo esc_html( es_persian_digits( sprintf( '%02d', $i + 1 ) ) ); ?></span><span class="project-card__arrow" aria-hidden="true">↗</span><div class="project-card__overlay"><span>پروژه نورپردازی شهری</span><h3><?php echo esc_html( $item[0] ); ?></h3></div></div><div class="project-card__info"><span class="project-card__meta">عرفان صنعت / پروژه شهری</span><p><?php echo esc_html( $item[2] ); ?></p></div></a>
            <?php endforeach;
        endif; ?>
    </div>
    <div class="section-after"><span>PROJECTS THAT LIGHT THE WAY</span><a href="<?php echo esc_url( es_projects_url() ); ?>">مشاهده آرشیو کامل پروژه‌ها <span aria-hidden="true">↗</span></a></div>
</div></section>
