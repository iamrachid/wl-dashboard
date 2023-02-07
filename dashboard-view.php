<?php
class WL_Dashboard_View {
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

        add_action('init', array($this, 'setup_rewrite'));
        add_action('template_redirect', array($this, 'register_custom_plugin_redirect'));
        add_filter('query_vars', array($this, 'register_query_values'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * If an instance exists, this returns it.  If not, it creates one and
     * returns it.
     *
     * @return WL_Dashboard_View
     */

    public static function getInstance() {
        if ( !self::$instance )
            self::$instance = new self;
        return self::$instance;
    }

    public function setup_rewrite()
    {
        add_rewrite_rule('wl-dashboard', 'index.php?wl-dashboard=true', 'top');
        flush_rewrite_rules();
    }

    public function register_custom_plugin_redirect()
    {
        if (get_query_var('wl-dashboard') === 'true')
            add_filter('template_include', function () {
                return WLDB__PLUGIN_DIR . 'partials/dashboard.php';
            });

    }
    public function register_query_values($vars)
    {
        $vars[] = 'wl-dashboard';
        return $vars;
    }

    public function enqueue_styles(){
        wp_enqueue_style('wl-dashboard-bootstrap', WLDB__PLUGIN_RELATIVE_DIR . 'css/bootstrap.css', array(), WLDB_VER);
        wp_enqueue_style('wl-dashboard-bootstrap-icons', WLDB__PLUGIN_RELATIVE_DIR . 'css/bootstrap-icons.css', array(), WLDB_VER);
        wp_enqueue_style('wl-dashboard-datatables', WLDB__PLUGIN_RELATIVE_DIR . 'css/datatables.css', array(), WLDB_VER);
        wp_enqueue_style('wl-dashboard-datatables-fixed-columns', WLDB__PLUGIN_RELATIVE_DIR . 'css/fixedColumns.dataTables.min.css', array(), WLDB_VER);
        wp_enqueue_style('wl-dashboard-style', WLDB__PLUGIN_RELATIVE_DIR . 'css/style.css', array(), WLDB_VER);
    }

    public function enqueue_scripts(){

        wp_enqueue_script( 'wl-dashboard-jquery', WLDB__PLUGIN_RELATIVE_DIR . 'js/jquery-3.6.3.min.js', false);
//        wp_enqueue_script( 'wl-dashboard-bootstrap', WLDB__PLUGIN_RELATIVE_DIR . 'js/bootstrap.js', false);
        wp_enqueue_script( 'wl-dashboard-bootstrap-bundle', WLDB__PLUGIN_RELATIVE_DIR . 'js/bootstrap.bundle.js', false);
        wp_enqueue_script( 'wl-dashboard-datatables-fixed-columns', WLDB__PLUGIN_RELATIVE_DIR . 'js/dataTables.fixedColumns.min.js', array(
            'wl-dashboard-datatables'
        ));
        wp_enqueue_script( 'wl-dashboard-datatables', WLDB__PLUGIN_RELATIVE_DIR . 'js/datatables.js', false);
        wp_enqueue_script( 'wl-dashboard-highcharts', WLDB__PLUGIN_RELATIVE_DIR . 'js/highcharts.js', false);
        wp_enqueue_script( 'wl-dashboard-script', WLDB__PLUGIN_RELATIVE_DIR . 'js/script.js', array(
            'wl-dashboard-jquery',
            'wl-dashboard-highcharts',
            'wl-dashboard-datatables-fixed-columns'
        ));
        wp_localize_script('wl-dashboard-script', 'constants', array(
            'rest-url' => get_rest_url()
        ));
    }
}
