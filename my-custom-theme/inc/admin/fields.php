<?php
defined( 'ABSPATH' ) || exit;
function es_render_option_field( $key, $field ) {
    $value = es_opt( $key );
    $name = 'es_options[' . $key . ']';
    echo '<div class="es-field es-field--' . esc_attr( $field['type'] ) . '"><label class="es-field__label" for="es-' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label><div class="es-field__control">';
    switch ( $field['type'] ) {
        case 'textarea':
            echo '<textarea id="es-' . esc_attr( $key ) . '" rows="4" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>'; break;
        case 'toggle':
            echo '<label class="es-switch"><input id="es-' . esc_attr( $key ) . '" type="checkbox" name="' . esc_attr( $name ) . '" value="1" ' . checked( $value, 1, false ) . '><span>فعال</span></label>'; break;
        case 'select':
            echo '<select id="es-' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '">';
            foreach ( $field['choices'] as $option => $label ) { echo '<option value="' . esc_attr( $option ) . '" ' . selected( $value, $option, false ) . '>' . esc_html( $label ) . '</option>'; }
            echo '</select>';
            if ( 'color_mode' === $key ) {
                echo '<div class="es-mode-preview" data-mode-preview data-mode="' . esc_attr( 'light' === $value ? 'light' : 'dark' ) . '"><div class="es-mode-preview__bar"><span>عرفان صنعت</span><span>منو • تماس</span></div><div class="es-mode-preview__body"><strong>شهری روشن‌تر، از امروز</strong><p>نمونه خوانایی متن و دکمه در تم انتخابی شما</p><span class="es-mode-preview__button">مشاهده پروژه‌ها ↗</span></div></div>';
            }
            break;
        case 'image':
            $url = wp_get_attachment_image_url( absint( $value ), 'medium' );
            echo '<div class="es-image-field"><input type="hidden" id="es-' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"><img src="' . esc_url( $url ?: '' ) . '" alt="" ' . ( $url ? '' : 'hidden' ) . '><div><button type="button" class="button" data-option-image="es-' . esc_attr( $key ) . '">انتخاب تصویر</button> <button type="button" class="button" data-clear-image="es-' . esc_attr( $key ) . '">حذف</button></div></div>'; break;
        case 'repeater':
            echo '<div class="es-repeater" data-repeater data-key="' . esc_attr( $key ) . '"><div data-repeater-rows>';
            foreach ( is_array( $value ) ? $value : array() as $index => $row ) { es_render_repeater_row( $key, $field, $index, $row ); }
            echo '</div><button type="button" class="button" data-add-row>+ افزودن ردیف</button>';
            echo '<template data-row-template>'; es_render_repeater_row( $key, $field, '__INDEX__', array() ); echo '</template></div>'; break;
        default:
            echo '<input id="es-' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . ( 'color' === $field['type'] ? '' : ' class="regular-text"' ) . '>';
    }
    if ( ! empty( $field['description'] ) ) { echo '<p class="es-field__hint">' . esc_html( $field['description'] ) . '</p>'; }
    echo '</div></div>';
}
function es_render_repeater_row( $key, $field, $index, $row ) {
    echo '<div class="es-repeat-row">';
    foreach ( $field['subfields'] as $subkey => $label ) {
        echo '<input aria-label="' . esc_attr( $label ) . '" placeholder="' . esc_attr( $label ) . '" name="es_options[' . esc_attr( $key ) . '][' . esc_attr( $index ) . '][' . esc_attr( $subkey ) . ']" value="' . esc_attr( $row[ $subkey ] ?? '' ) . '">';
    }
    echo '<button type="button" class="button" data-remove-row>حذف</button></div>';
}
