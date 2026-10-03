<?php
/** Online-cart mock, not a live checkout. */
putenv( 'ES_RENDER_PRODUCT=1' );
putenv( 'ES_RENDER_ONLINE=1' );
require __DIR__ . '/render-home.php';
