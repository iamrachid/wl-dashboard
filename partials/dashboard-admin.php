<div class="wrap">
    <h1>WL Dashboard Settings</h1>
    <a href="<?php echo get_site_url(null, 'wl-dashboard'); ?>" target="_blank">Visit dashboard</a>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'wl_dashboard_db_settings' ); ?>
        <?php do_settings_sections( 'wl_dashboard_db_settings' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">DB Host</th>
                <td><input type="text" name="wl_dashboard_db_host" value="<?php echo esc_attr( get_option('wl_dashboard_db_host') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">DB User</th>
                <td><input type="text" name="wl_dashboard_db_user" value="<?php echo esc_attr( get_option('wl_dashboard_db_user') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">DB Password</th>
                <td><input type="password" name="wl_dashboard_db_password" value="<?php echo esc_attr( get_option('wl_dashboard_db_password') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">DB Name</th>
                <td><input type="text" name="wl_dashboard_db_name" value="<?php echo esc_attr( get_option('wl_dashboard_db_name') ); ?>" /></td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
</div>