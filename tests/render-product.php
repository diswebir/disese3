<?php
/** Mock single-product preview; not a WordPress/WooCommerce installation. */
putenv( 'ES_RENDER_PRODUCT=1' );
require __DIR__ . '/render-home.php';
