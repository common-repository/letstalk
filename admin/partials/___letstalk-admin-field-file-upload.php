<?php

/**
 * Provides the markup for an upload field
 *
 * @link       https://topishare.com
 * @since      1.0.0
 *
 * @package    Letstalk
 * @subpackage Letstalk/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'letstalk' ); ?>: </label><?php

}

?><input
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	data-id="url-file"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	type="<?php echo esc_attr( $atts['type'] ); ?>"
	value="<?php echo esc_attr( $atts['value'] ); ?>" />
<a href="#" class="" id="upload-file"><?php esc_html_e( $atts['label-upload'], 'letstalk' ); ?></a>
<a href="#" class="hide" id="remove-file"><?php esc_html_e( $atts['label-remove'], 'letstalk' ); ?></a>