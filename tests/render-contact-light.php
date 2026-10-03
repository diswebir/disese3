<?php
/** Mock dedicated contact page in light mode. */
putenv( 'ES_RENDER_LIGHT=1' );
putenv( 'ES_RENDER_CONTACT=1' );
require __DIR__ . '/render-home.php';
