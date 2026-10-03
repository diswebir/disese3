<?php defined( 'ABSPATH' ) || exit; ?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>"><label for="es-search">جستجو در سایت</label><input id="es-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="عبارت موردنظر را وارد کنید..."><button type="submit">جستجو</button></form>
