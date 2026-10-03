<?php
/** Light-mode mock product preview. */
putenv( 'ES_RENDER_PRODUCT=1' );
putenv( 'ES_RENDER_LIGHT=1' );
require __DIR__ . '/render-home.php';
