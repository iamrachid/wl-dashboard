<?php
class WL_Dashboard_Admin {
    /**
     * Static property to hold our singleton instance
     *
     */
    static $instance = false;

    /**
     * Database instance
     *
     */

    /**
     * This is our constructor
     *
     * @return void
     */
    private function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'), 9);
        add_action('admin_init', array($this, 'build_fields'));
    }

    /**
     * If an instance exists, this returns it.  If not, it creates one and
     * returns it.
     *
     * @return WL_Dashboard_Admin
     */

    public static function getInstance() {
        if ( !self::$instance )
            self::$instance = new self;
        return self::$instance;
    }
    public function add_plugin_admin_menu()
    {
        add_menu_page('WL Dashboard Settings', 'WL Dashboard', 'administrator', 'wl-dashboard', array($this, 'display_plugin_admin_menu'), 'dashicons-dashboard', 26);
    }

    public function display_plugin_admin_menu()
    {
        require_once 'partials/dashboard-admin.php';
    }

    public function display_plugin_admin_settings()
    {
        // set this var to be used in the settings-display view
        $active_tab = isset($_get['tab']) ? $_get['tab'] : 'general';
        if (isset($_get['error_message'])) {
            add_action('admin_notices', array($this, 'wl_dashboard_settings_messages'));
            do_action('admin_notices', $_get['error_message']);
        }
        require_once 'partials/dashboard-admin.php';
    }
    public function wl_dashboard_settings_messages($error_message)
    {
        switch ($error_message) {
            case '1':
                $message = __('there was an error adding this setting. please try again.  if this persists, shoot us an email.', 'my-text-domain');
                $err_code = esc_attr('wl_dashboard_setting');
                $setting_field = 'wl_dashboard_setting';
                break;
        }
        $type = 'error';
        add_settings_error(
            $setting_field,
            $err_code,
            $message,
            $type
        );
    }
    public function build_fields()
    {

        $fields = array(
            'db_host' => "host",
            'db_user' => "user",
            'db_password' => "password",
            'db_name' => "name",
        );

        foreach ($fields as $id => $title) {
            register_setting(
                'wl_dashboard_db_settings',
                'wl_dashboard' . '_' . $id,
                array('default' => $this->wl_dashboard_default_settings($id))
            );
        }
    }

    private function wl_dashboard_default_settings($id)
    {

        $default_options = array(
            'wl_dashboard_' . 'db_host' => DB_HOST,
            'wl_dashboard_' . 'db_user' => DB_USER,
            'wl_dashboard_' . 'db_password' => DB_PASSWORD,
            'wl_dashboard_' . 'db_name' => DB_NAME,
        );
        return $default_options[$id];
    }
}
