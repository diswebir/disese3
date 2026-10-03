<?php
/** Standalone contact page mock; no WordPress DB or actual form delivery. */
putenv( 'ES_RENDER_CONTACT=1' );
require __DIR__ . '/render-home.php';
