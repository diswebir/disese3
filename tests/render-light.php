<?php
/** Mock homepage in light mode. */
putenv( 'ES_RENDER_LIGHT=1' );
require __DIR__ . '/render-home.php';
