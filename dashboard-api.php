<?php

class WL_Dashboard_API {
    /**
     * Static property to hold our singleton instance
     *
     */
    static $instance = false;

    /**
     * Database instance
     *
     */
    private $local_db ;

    /**
     * This is our constructor
     *
     * @return void
     */
    private function __construct() {
        $this->db_connect();
        add_action( 'rest_api_init', array($this, 'register_routes'));
    }

    /**
     * If an instance exists, this returns it.  If not, it creates one and
     * retuns it.
     *
     * @return WL_Dashboard_API
     */

    public static function getInstance() {
        if ( !self::$instance )
            self::$instance = new self;
        return self::$instance;
    }


    public function db_connect(){
        $servername = get_option('wl_dashboard_db_host');
        $username = get_option('wl_dashboard_db_user');
        $pwd = get_option('wl_dashboard_db_password');
        $dbname = get_option('wl_dashboard_db_name');

        $this->local_db = new wpdb($username, $pwd, $dbname, $servername);
    }

    public function register_routes() {
        // GET /dashboard/dates
        register_rest_route( 'dashboard', '/dates', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_dates'),
        ));

        // GET /dashboard/schedule/date
        register_rest_route( 'dashboard', '/schedule/(?P<date>.+)', array(
            'methods' => 'GET',
            'callback' => array($this,'display_row'),
        ) );

        // GET /dashboard/table/date/venue/raceno
        register_rest_route( 'dashboard', '/table/(?P<date>.+)/(?P<venue>.+)/(?P<raceno>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'display_data_tab'),
        ) );

        // GET /dashboard/chart/date/venue/raceno
        register_rest_route( 'dashboard', '/charts/(?P<date>.+)/(?P<venue>.+)/(?P<raceno>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'display_data_chart'),
        ) );
    }

    function get_dates() {
        $sql = "SELECT DISTINCT rdate FROM local.wl_pretty_api_dateschedules ORDER BY rdate desc";//
        $res = $this->local_db->get_results($sql);
        $values = array();
        foreach ($res as $row)
            $values[] = $row->rdate;
        return $values;
    }

    function display_row( $data) {
        $a=$data["date"];

        $sql = "SELECT * FROM local.wl_pretty_api_dateschedules where rdate='$a' ORDER BY venue";
       return $this->local_db->get_results($sql);
    }

    function display_data_tab( $data) {
        $a1 = $data["venue"];
        $a2 = $data["date"];
        $a3 = $data["raceno"];

        $sql="SELECT * FROM local.wl_pretty_api_datatables_admin where rdate='$a2' and venue='$a1'";
        $res = $this->local_db->get_row($sql);
        $json = json_decode($res->docdata);
        $raceno_index = array_search("raceno", $json->cols);
        $json->rows = array_filter($json->rows, function($row) use ($a3, $raceno_index) {
            if($row[$raceno_index] == $a3)
                return true;
            return false;
        });
        return $json;
    }


    function display_data_chart( $data) {
        $a2=$data["date"];
        $a1=$data["venue"];
        $a3=$data["raceno"];

        $sql="SELECT * FROM local.wl_pretty_api_stacks_preview where rdate='$a2' and venue='$a1' and raceno='$a3'";
        return $this->local_db->get_results($sql);
    }
}
