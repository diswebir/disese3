<?php
/** Contact landing page, shared by the named page template and /contact/ route. */
defined( 'ABSPATH' ) || exit;
$phone = (string) es_opt( 'phone' );
$email = sanitize_email( es_opt( 'email' ) );
$faqs = (array) es_opt( 'contact_faqs' );
$status = isset( $_GET['es_contact'] ) ? sanitize_key( wp_unslash( $_GET['es_contact'] ) ) : '';
$notices = array(
    'saved' => 'درخواست شما ثبت شد. کارشناسان ما در اولین فرصت با شما تماس می‌گیرند.',
    'invalid' => 'اطلاعات فرم کامل یا معتبر نیست. لطفاً فیلدها را بررسی کنید و دوباره تلاش کنید.',
    'wait' => 'درخواست قبلی ثبت شده است. لطفاً کمی بعد دوباره تلاش کنید.',
    'error' => 'در حال حاضر ثبت درخواست ممکن نیست. لطفاً تلفنی با ما تماس بگیرید.',
    'unavailable' => 'در حال حاضر فرم آنلاین فعال نیست. لطفاً با شماره زیر تماس بگیرید.',
);
?>
<main id="main" class="es-contact-page">
    <section class="contact-hero" style="--contact-hero-image:url('<?php echo esc_url( es_asset( 'images/light-tunnel.jpg' ) ); ?>')">
        <div class="container contact-hero__inner">
            <nav class="contact-hero__crumb" aria-label="مسیر صفحه"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">صفحه نخست</a><span aria-hidden="true"> / </span><span aria-current="page">تماس با ما</span></nav>
            <div class="contact-hero__copy"><span class="eyebrow"><span class="eyebrow-line"></span> LET'S TALK ABOUT LIGHT / در ارتباط باشیم</span><h1>بیایید با هم، <em>چیزی روشن‌تر</em> بسازیم.</h1><p>هر ایده‌ای برای نورپردازی شهر، از یک گفت‌وگوی ساده آغاز می‌شود. درباره پروژه‌تان با ما صحبت کنید؛ تیم عرفان صنعت برای راهنمایی شما آماده است.</p><div class="contact-hero__buttons"><a class="btn btn--gold" href="#contact-form">ثبت درخواست مشاوره <span aria-hidden="true">↗</span></a><a class="btn btn--outline" href="<?php echo esc_url( es_phone_url( $phone ) ); ?>">تماس مستقیم <span aria-hidden="true">←</span></a></div></div>
            <div class="contact-hero__foot"><span>عرفان صنعت اصفهان <i aria-hidden="true"></i> از ایده تا درخشش، کنار شما</span><span>URBAN LIGHTING — EST. ۲۰۰۰</span></div>
        </div>
    </section>

    <section class="contact-channels" aria-labelledby="contact-channels-heading"><div class="container">
        <div class="contact-section-heading"><span class="eyebrow"><span class="eyebrow-line"></span> راه‌های ارتباطی / GET IN TOUCH</span><h2 id="contact-channels-heading">ما همیشه <em>در دسترسیم.</em></h2><p>مسیر دلخواه‌تان را برای شروع یک همکاری روشن انتخاب کنید.</p></div>
        <div class="contact-channels__grid">
            <a class="contact-channel" href="<?php echo esc_url( es_phone_url( $phone ) ); ?>"><span class="contact-channel__index">۰۱ / ارتباط فوری</span><span class="contact-channel__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.6 3.8 7.7 3l2.1 4.7-2.2 1.7a16 16 0 0 0 7 7l1.7-2.2 4.7 2.1-.8 3.1a2 2 0 0 1-2.2 1.5A18.5 18.5 0 0 1 3.1 6a2 2 0 0 1 1.5-2.2Z"/></svg></span><h3>تلفن مستقیم</h3><p>یک تماس تا شروع پروژه شما فاصله داریم.</p><strong dir="ltr"><?php echo esc_html( $phone ); ?></strong><span class="contact-channel__arrow" aria-hidden="true">↗</span></a>
            <a class="contact-channel" href="<?php echo esc_url( 'mailto:' . $email ); ?>"><span class="contact-channel__index">۰۲ / مکاتبه رسمی</span><span class="contact-channel__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg></span><h3>ایمیل شرکت</h3><p>برای ارسال مستندات و درخواست‌های رسمی.</p><strong class="contact-channel__email"><?php echo esc_html( $email ); ?></strong><span class="contact-channel__arrow" aria-hidden="true">↗</span></a>
            <div class="contact-channel"><span class="contact-channel__index">۰۳ / زمان پاسخگویی</span><span class="contact-channel__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><h3>ساعات کاری</h3><p>در این زمان‌ها منتظر شنیدن صدای شما هستیم.</p><strong class="contact-channel__hours"><?php echo esc_html( es_opt( 'hours' ) ); ?></strong></div>
        </div>
    </div></section>

    <section class="contact-workspace" id="contact-form" aria-labelledby="contact-form-heading"><div class="container contact-workspace__grid">
        <div class="contact-workspace__intro"><span class="eyebrow"><span class="eyebrow-line"></span> فرم درخواست مشاوره</span><h2 id="contact-form-heading">داستان پروژه‌تان را <em>برای ما بگویید.</em></h2><p>چند خط درباره نیازتان بنویسید. کارشناسان ما درخواست شما را بررسی می‌کنند و در اولین فرصت برای راهنمایی و هماهنگی قدم بعدی با شما تماس می‌گیرند.</p>
            <div class="contact-workspace__steps"><div><span>۱</span><strong>ارسال درخواست</strong><small>جزئیات پروژه را با ما در میان بگذارید.</small></div><div><span>۲</span><strong>بررسی تخصصی</strong><small>تیم فنی بهترین راهکار را بررسی می‌کند.</small></div><div><span>۳</span><strong>شروع همکاری</strong><small>برای طراحی، برآورد و اجرا همراه می‌شویم.</small></div></div>
            <div class="contact-workspace__note"><span aria-hidden="true">✦</span><p>«نور فقط روشنایی نیست؛ فرصتی‌ست برای ساختن تجربه‌ای تازه از شهر.»</p><small>تیم عرفان صنعت اصفهان</small></div>
        </div>
        <div class="contact-form-card">
            <div class="contact-form-card__top"><span class="eyebrow">YOUR NEXT BRIGHT IDEA</span><span aria-hidden="true">✳</span></div>
            <h3>مشاوره تخصصی، از همین‌جا شروع می‌شود.</h3><p>اطلاعات خود را وارد کنید تا با شما در ارتباط باشیم.</p>
            <?php if ( isset( $notices[ $status ] ) ) : ?><div class="contact-alert <?php echo 'saved' === $status ? 'contact-alert--success' : 'contact-alert--error'; ?>" role="<?php echo 'saved' === $status ? 'status' : 'alert'; ?>"><?php echo esc_html( $notices[ $status ] ); ?></div><?php endif; ?>
            <?php if ( es_opt( 'form_enabled' ) ) : ?>
            <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="es_contact_submit">
                <?php wp_nonce_field( 'es_contact_submit', 'es_contact_nonce' ); ?>
                <div class="contact-form__trap" aria-hidden="true"><label for="contact-website">وب‌سایت</label><input id="contact-website" type="text" name="website" value="" tabindex="-1" autocomplete="off"></div>
                <div class="contact-form__row"><div class="contact-form__field"><label for="contact-name">نام و نام خانوادگی <span>*</span></label><input id="contact-name" type="text" name="name" placeholder="نام شما" required minlength="2" maxlength="90" autocomplete="name"></div><div class="contact-form__field"><label for="contact-phone">شماره تماس <span>*</span></label><input id="contact-phone" type="tel" name="phone" placeholder="۰۹۱۲ ۰۰۰ ۰۰۰۰" required maxlength="24" inputmode="tel" autocomplete="tel" dir="ltr"></div></div>
                <div class="contact-form__row"><div class="contact-form__field"><label for="contact-email">ایمیل (اختیاری)</label><input id="contact-email" type="email" name="email" placeholder="name@example.com" maxlength="120" autocomplete="email" dir="ltr"></div><div class="contact-form__field"><label for="contact-city">شهر / سازمان</label><input id="contact-city" type="text" name="city" placeholder="مثلاً اصفهان / شهرداری" maxlength="80" autocomplete="address-level2"></div></div>
                <div class="contact-form__field"><label for="contact-category">موضوع درخواست <span>*</span></label><select id="contact-category" name="category" required><option value="" disabled selected>موضوع موردنظر را انتخاب کنید</option><?php foreach ( es_contact_categories() as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></div>
                <div class="contact-form__field"><label for="contact-message">درباره پروژه‌تان بنویسید <span>*</span></label><textarea id="contact-message" name="message" placeholder="ابعاد پروژه، موقعیت اجرا، محصولات موردنظر یا هر سؤالی که دارید..." required minlength="10" maxlength="4000" rows="5"></textarea></div>
                <label class="contact-form__consent"><input type="checkbox" name="consent" value="1" required><span>موافقم که اطلاعات این فرم صرفاً برای پاسخ‌گویی به درخواست من توسط عرفان صنعت نگهداری و استفاده شود.</span></label>
                <button type="submit" class="btn btn--gold contact-form__submit">ارسال درخواست مشاوره <span aria-hidden="true">↗</span></button>
                <p class="contact-form__privacy">اطلاعات شما عمومی نمی‌شود. برای درخواست حذف اطلاعات ثبت‌شده، به ایمیل شرکت پیام دهید.</p>
            </form>
            <?php else : ?><div class="contact-form__disabled">فرم آنلاین فعلاً غیرفعال است. لطفاً از طریق تلفن یا ایمیل با ما در تماس باشید.</div><?php endif; ?>
        </div>
    </div></section>

    <section class="contact-visit" aria-labelledby="contact-visit-heading"><div class="container contact-visit__grid"><div class="contact-visit__copy"><span class="eyebrow"><span class="eyebrow-line"></span> دفتر مرکزی / FIND US</span><h2 id="contact-visit-heading">جایی که ایده‌ها <em>به نور تبدیل می‌شوند.</em></h2><p>برای مراجعه حضوری، لطفاً پیش از حرکت با کارشناسان ما هماهنگ کنید.</p><div class="contact-visit__address"><span class="contact-visit__icon" aria-hidden="true">⌖</span><div><small>نشانی کارخانه و دفتر مرکزی</small><address><?php echo esc_html( es_opt( 'address' ) ); ?></address><?php if ( es_opt( 'postal_code' ) ) : ?><small>کد پستی: <?php echo esc_html( es_opt( 'postal_code' ) ); ?></small><?php endif; ?></div></div><div class="contact-visit__links"><?php if ( es_opt( 'map_url' ) ) : ?><a class="btn btn--outline" href="<?php echo esc_url( es_opt( 'map_url' ) ); ?>" target="_blank" rel="noopener noreferrer">مسیریابی روی نقشه <span aria-hidden="true">↗</span></a><?php endif; ?><?php foreach ( array( 'whatsapp_url' => 'واتساپ', 'telegram_url' => 'تلگرام', 'instagram_url' => 'اینستاگرام', 'aparat_url' => 'آپارات' ) as $key => $label ) : if ( es_opt( $key ) ) : ?><a class="contact-visit__social" href="<?php echo esc_url( es_opt( $key ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $label ); ?> ↗</a><?php endif; endforeach; ?></div></div>
        <div class="contact-map" role="img" aria-label="نمای شماتیک موقعیت دفتر عرفان صنعت در اصفهان"><div class="contact-map__road contact-map__road--one"></div><div class="contact-map__road contact-map__road--two"></div><div class="contact-map__road contact-map__road--three"></div><span class="contact-map__city">ISFAHAN / اصفهان</span><div class="contact-map__pin"><span aria-hidden="true">✦</span><strong>عرفان صنعت</strong><small>شهرک صنعتی برق و الکترونیک</small></div><span class="contact-map__caption">نمای شماتیک — موقعیت دقیق را از مسیر‌یاب دریافت کنید</span></div>
    </div></section>

    <?php if ( $faqs ) : ?><section class="contact-faq" aria-labelledby="contact-faq-heading"><div class="container contact-faq__grid"><div><span class="eyebrow"><span class="eyebrow-line"></span> پرسش‌های پرتکرار / FAQ</span><h2 id="contact-faq-heading">پاسخ به <em>سؤال‌های شما.</em></h2><p>اگر هنوز سؤالی دارید، ما فقط یک تماس با شما فاصله داریم.</p><a class="text-link" href="<?php echo esc_url( es_phone_url( $phone ) ); ?>">تماس با کارشناسان ↖</a></div><div class="contact-faq__items"><?php foreach ( $faqs as $i => $faq ) : if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) { continue; } ?><details><summary><span><?php echo esc_html( es_persian_digits( sprintf( '%02d', $i + 1 ) ) ); ?></span><strong><?php echo esc_html( $faq['question'] ); ?></strong><span class="contact-faq__plus" aria-hidden="true">+</span></summary><p><?php echo esc_html( $faq['answer'] ); ?></p></details><?php endforeach; ?></div></div></section><?php endif; ?>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); if ( get_the_content() ) : ?><div class="container entry-content contact-extra"><?php the_content(); ?></div><?php endif; endwhile; endif; ?>
</main>
<?php
$faq_entities = array();
foreach ( $faqs as $faq ) {
    if ( ! empty( $faq['question'] ) && ! empty( $faq['answer'] ) ) {
        $faq_entities[] = array( '@type' => 'Question', 'name' => $faq['question'], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $faq['answer'] ) );
    }
}
$contact_schema = array(
    '@context' => 'https://schema.org', '@type' => 'ContactPage', 'name' => 'تماس با عرفان صنعت اصفهان',
    'url' => es_contact_url(),
    'mainEntity' => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ), 'telephone' => $phone, 'email' => $email, 'address' => array( '@type' => 'PostalAddress', 'streetAddress' => es_opt( 'address' ), 'postalCode' => es_opt( 'postal_code' ), 'addressCountry' => 'IR' ) ),
);
if ( $faq_entities ) { $contact_schema['hasPart'] = array( '@type' => 'FAQPage', 'mainEntity' => $faq_entities ); }
echo '<script type="application/ld+json">' . wp_json_encode( $contact_schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE ) . '</script>';
