<?php
/** Theme bootstrap: logic lives in inc/. */
defined( 'ABSPATH' ) || exit;
foreach ( array( 'options-schema', 'options', 'template-helpers', 'post-types', 'meta-boxes', 'contact', 'enqueue', 'dynamic-css', 'admin/fields', 'admin/dashboard', 'admin/tools' ) as $module ) {
    require_once get_template_directory() . '/inc/' . $module . '.php';
}
