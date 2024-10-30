<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://topishare.com
 * @since      1.0.0
 *
 * @package    Letstalk
 * @subpackage Letstalk/admin/partials
 */

?><h2>
    <img src="<?php echo plugins_url( 'letstalk/admin/images/topishare_logo.png' ); ?>" alt="<?php echo esc_html( get_admin_page_title() ); ?>">
    <?php echo esc_html( get_admin_page_title() ); ?>
</h2>

<form method="post" action="options.php"><?php

// saved in wp-options table where option_name = 'letstalk-options'
settings_fields( $this->plugin_name . '-options' );

do_settings_sections( $this->plugin_name );

submit_button( 'Save Settings' );

?></form>