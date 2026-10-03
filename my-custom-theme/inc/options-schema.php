<?php
defined( 'ABSPATH' ) || exit;
/** Single source of truth: tab > section > fields. Keys are stable storage identifiers. */
function es_options_schema() {
    return array(
        'identity' => array( 'label' => 'هویت و رنگ‌ها', 'sections' => array(
            array( 'title' => 'هویت بصری', 'fields' => array(
                'site_tagline' => array( 'label' => 'عنوان کوتاه برند', 'type' => 'text', 'default' => 'شرکت دانش‌بنیان عرفان صنعت اصفهان' ),
                'logo' => array( 'label' => 'لوگو', 'type' => 'image', 'default' => '' ),
                'primary_color' => array( 'label' => 'رنگ اصلی تم تیره', 'type' => 'color', 'default' => '#d9ae68' ),
                'accent_color' => array( 'label' => 'رنگ مکمل تم تیره', 'type' => 'color', 'default' => '#8c73d6' ),
                'background_color' => array( 'label' => 'رنگ زمینه تم تیره', 'type' => 'color', 'default' => '#090e1d' ),
                'body_font' => array( 'label' => 'فونت متن', 'type' => 'select', 'choices' => array( 'Vazirmatn' => 'وزیرمتن', 'Lalezar' => 'لاله‌زار' ), 'default' => 'Vazirmatn' ),
                'heading_font' => array( 'label' => 'فونت تیتر', 'type' => 'select', 'choices' => array( 'Vazirmatn' => 'وزیرمتن', 'Lalezar' => 'لاله‌زار' ), 'default' => 'Vazirmatn' ),
            ) ),
        ) ),
        'palette' => array( 'label' => 'تم رنگی', 'sections' => array(
            array( 'title' => 'انتخاب ظاهر سایت', 'fields' => array(
                'color_mode' => array( 'label' => 'حالت نمایش', 'type' => 'select', 'choices' => array( 'dark' => 'تم تیره (دارک)', 'light' => 'تم روشن (لایت)' ), 'default' => 'dark', 'description' => 'پیش‌نمایش بالا نمایی از رنگ‌های پیش‌فرض است. پس از ذخیره، ظاهر همه صفحات، فرم‌ها، فروشگاه و منوها تغییر می‌کند.' ),
                'light_primary_color' => array( 'label' => 'رنگ اصلی تم روشن', 'type' => 'color', 'default' => '#70420d', 'description' => 'برای خوانایی متن و دکمه‌ها، رنگ‌های کم‌کنتراست به رنگ امن پیش‌فرض برمی‌گردند.' ),
                'light_accent_color' => array( 'label' => 'رنگ مکمل تم روشن', 'type' => 'color', 'default' => '#60448c', 'description' => 'رنگ مکمل در حاشیه‌های تزئینی استفاده می‌شود؛ اگر کنتراست کافی نداشته باشد، رنگ پیش‌فرض اعمال می‌شود.' ),
                'light_background_color' => array( 'label' => 'زمینه تم روشن', 'type' => 'color', 'default' => '#f7f8fb', 'description' => 'زمینه‌های تیره یا کم‌کنتراست در حالت روشن به رنگ امن پیش‌فرض برمی‌گردند.' ),
                'light_text_color' => array( 'label' => 'متن تم روشن', 'type' => 'color', 'default' => '#17253b', 'description' => 'متن اصلی باید روی پس‌زمینه روشن کاملاً خوانا بماند.' ),
            ) ),
        ) ),
        'home' => array( 'label' => 'صفحه نخست', 'sections' => array(
            array( 'title' => 'بنر اصلی', 'fields' => array(
                'hero_eyebrow' => array( 'label' => 'برچسب بالای تیتر', 'type' => 'text', 'default' => 'شرکت دانش‌بنیان عرفان صنعت اصفهان' ),
                'hero_title' => array( 'label' => 'تیتر بنر', 'type' => 'text', 'default' => 'درخشان‌تر از همیشه، آینده‌ای روشن برای ایران می‌سازیم' ),
                'hero_highlight' => array( 'label' => 'عبارت برجسته در تیتر', 'type' => 'text', 'default' => 'آینده‌ای روشن برای ایران می‌سازیم', 'description' => 'این عبارت باید دقیقاً بخشی از تیتر بنر باشد.' ),
                'hero_text' => array( 'label' => 'توضیح بنر', 'type' => 'textarea', 'default' => 'با بیش از دو دهه تجربه، پیشگام در طراحی، تولید و اجرای پروژه‌های روشنایی شهری و صنعتی؛ روشنایی‌بخش خیابان‌ها، پارک‌ها و میادین کشور هستیم.' ),
                'hero_image' => array( 'label' => 'تصویر بنر', 'type' => 'image', 'default' => '' ),
                'hero_primary_label' => array( 'label' => 'متن دکمه اول', 'type' => 'text', 'default' => 'مشاهده پروژه‌های نورپردازی' ),
                'hero_secondary_label' => array( 'label' => 'متن دکمه دوم', 'type' => 'text', 'default' => 'دریافت مشاوره رایگان' ),
                'show_projects' => array( 'label' => 'نمایش پروژه‌ها', 'type' => 'toggle', 'default' => 1 ),
                'show_products' => array( 'label' => 'نمایش محصولات', 'type' => 'toggle', 'default' => 1 ),
                'show_blog' => array( 'label' => 'نمایش مقالات', 'type' => 'toggle', 'default' => 1 ),
            ) ),
            array( 'title' => 'درباره و آمار', 'fields' => array(
                'about_title' => array( 'label' => 'تیتر درباره', 'type' => 'text', 'default' => 'بیش از ۲۵ سال تجربه، حاصلِ کارِ مفیدِ بی‌وقفه' ),
                'about_text' => array( 'label' => 'متن درباره', 'type' => 'textarea', 'default' => 'شرکت دانش‌بنیان عرفان صنعت اصفهان در زمینه نورپردازی شهری، با سابقه‌ای درخشان و متخصصانی مجرب، طیف وسیعی از خدمات را به شهرداری‌ها، سازمان‌های عمرانی و پیمانکاران پروژه‌های شهری ارائه می‌دهد. برای ما نور، نمادی از امید، پیشرفت و زندگی است.' ),
                'about_image' => array( 'label' => 'تصویر درباره', 'type' => 'image', 'default' => '' ),
                'stats' => array( 'label' => 'آمارها', 'type' => 'repeater', 'subfields' => array( 'value' => 'عدد', 'label' => 'عنوان' ), 'default' => array( array( 'value' => '۲۵+', 'label' => 'سال تجربه درخشان' ), array( 'value' => '۸۵۰+', 'label' => 'پروژه تکمیل‌شده' ), array( 'value' => '۲۸۹+', 'label' => 'مشتری فعال' ), array( 'value' => '۹۰+', 'label' => 'محصول متنوع' ) ) ),
                'cities' => array( 'label' => 'شهرهای همکار', 'type' => 'repeater', 'subfields' => array( 'name' => 'نام شهر' ), 'default' => array( array( 'name' => 'اصفهان' ), array( 'name' => 'تهران' ), array( 'name' => 'شیراز' ), array( 'name' => 'مشهد' ), array( 'name' => 'کرمان' ), array( 'name' => 'تبریز' ), array( 'name' => 'اهواز' ), array( 'name' => 'یزد' ) ) ),
            ) ),
        ) ),
        'contact' => array( 'label' => 'ارتباط و فوتر', 'sections' => array(
            array( 'title' => 'راه‌های ارتباطی', 'fields' => array(
                'phone' => array( 'label' => 'تلفن تماس', 'type' => 'tel', 'default' => '03191091011' ),
                'email' => array( 'label' => 'ایمیل', 'type' => 'email', 'default' => 'info@erfansanat.com' ),
                'address' => array( 'label' => 'نشانی', 'type' => 'textarea', 'default' => 'اصفهان، خمینی‌شهر، شهرک صنعتی برق و الکترونیک، بلوار الکترونیک، پلاک ۱۱۹' ),
                'hours' => array( 'label' => 'ساعات کاری', 'type' => 'text', 'default' => 'شنبه تا پنجشنبه، ۷ صبح تا ۴:۳۰ بعدازظهر' ),
                'aparat_url' => array( 'label' => 'آدرس آپارات', 'type' => 'url', 'default' => '' ),
                'instagram_url' => array( 'label' => 'آدرس اینستاگرام', 'type' => 'url', 'default' => '' ),
                'whatsapp_url' => array( 'label' => 'لینک واتساپ', 'type' => 'url', 'default' => '' ),
                'telegram_url' => array( 'label' => 'لینک تلگرام', 'type' => 'url', 'default' => '' ),
                'map_url' => array( 'label' => 'لینک موقعیت روی نقشه (اختیاری)', 'type' => 'url', 'default' => '' ),
                'postal_code' => array( 'label' => 'کد پستی', 'type' => 'text', 'default' => '۸۴۱۸۱۴۸۶۷۸' ),
                'form_enabled' => array( 'label' => 'نمایش فرم مشاوره در برگه تماس', 'type' => 'toggle', 'default' => 1 ),
                'form_recipient' => array( 'label' => 'ایمیل دریافت درخواست‌ها (اختیاری؛ در صورت خالی بودن از ایمیل شرکت استفاده می‌شود)', 'type' => 'email', 'default' => '' ),
                'contact_faqs' => array( 'label' => 'سوالات متداول تماس', 'type' => 'repeater', 'subfields' => array( 'question' => 'سؤال', 'answer' => 'پاسخ' ), 'default' => array( array( 'question' => 'چطور برای پروژه نورپردازی استعلام قیمت بگیرم؟', 'answer' => 'مشخصات پروژه، موقعیت و اطلاعات تماس خود را در فرم بنویسید یا مستقیم با کارشناسان فروش تماس بگیرید.' ), array( 'question' => 'آیا امکان طراحی و ساخت المان اختصاصی وجود دارد؟', 'answer' => 'بله، تیم مهندسی ما متناسب با ابعاد، بودجه و هویت بصری پروژه شما طرح اختصاصی ارائه می‌کند.' ), array( 'question' => 'زمان پاسخگویی به درخواست‌ها چقدر است؟', 'answer' => 'کارشناسان در ساعات کاری، درخواست‌ها را بررسی می‌کنند و در اولین فرصت با شما تماس می‌گیرند.' ) ) ),
                'footer_text' => array( 'label' => 'متن کپی‌رایت', 'type' => 'text', 'default' => 'تمامی حقوق برای عرفان صنعت اصفهان محفوظ است.' ),
            ) ),
        ) ),
    );
}
function es_schema_fields() {
    $fields = array();
    foreach ( es_options_schema() as $tab ) {
        foreach ( $tab['sections'] as $section ) {
            $fields = array_merge( $fields, $section['fields'] );
        }
    }
    return $fields;
}
