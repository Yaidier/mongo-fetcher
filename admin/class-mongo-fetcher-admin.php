<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mongo_Fetcher
 * @subpackage Mongo_Fetcher/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mongo_Fetcher
 * @subpackage Mongo_Fetcher/admin
 * @author     Yaidier Perez <yaidier.perez@gmail.com>
 */
class Mongo_Fetcher_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $mongo_fetcher    The ID of this plugin.
	 */
	private $mongo_fetcher;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $settings_sections;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $mongo_fetcher       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $mongo_fetcher, $version ) {

		$this->mongo_fetcher        = $mongo_fetcher;
		$this->version              = $version;
        $this->settings_sections    = (array) require_once plugin_dir_path( __FILE__ ) . '/config/settings-sections.php';
    
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mongo_Fetcher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mongo_Fetcher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->mongo_fetcher, plugin_dir_url( __FILE__ ) . 'css/mongo-fetcher-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mongo_Fetcher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mongo_Fetcher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

         /**
          * No Admin Scripts needed for this plugin so far
          *
		  * wp_enqueue_script( $this->mongo_fetcher, plugin_dir_url( __FILE__ ) . 'js/mongo-fetcher-admin.js', array( 'jquery' ), $this->version, false );
        */

	}

	/**
	 * Register the Menu Options for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_pages() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mongo_Fetcher_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mongo_Fetcher_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        add_menu_page( 'Mongo Fetcher', 'Mongo Fetcher', 'manage_options', 'mongo_fetcher_plugin', array($this, 'admin'), 'dashicons-align-full-width', 110 );

	}

    public function admin() {
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : null;

        if( $tab == 'settings' ) {
            $this->render_settings_tab();
        }
        else {
            $this->render_sync_tab();
        }
    }

    private function get_admin_layout() {
        ob_start();
        require_once 'partials/mongo-fetcher-admin-display.php';
        return ob_get_clean();
    }

    public function render_sync_tab() {
        $sync_content   = $this->get_sync_content();
        $admin_layout   = $this->get_admin_layout();
        $view           = str_replace( '{{render_tab_content}}', $sync_content, $admin_layout );

        echo $view;
    }
    
    public function render_settings_tab() {
        $settings_content   = $this->get_settings_content();
        $admin_layout       = $this->get_admin_layout();
        $view               = str_replace( '{{render_tab_content}}', $settings_content, $admin_layout );

        echo $view;
    }

    private function get_settings_content() {
        ob_start();
        echo '<form method="post" action="options.php">';

        settings_fields( 'mf_settings_group' );
        do_settings_sections( 'mf_settings_section' );
        submit_button();

        echo '</form>';
        return ob_get_clean();
    }

    private function get_the_settings_options() {
        $mongo_credentials = [];

        foreach ($this->settings_sections['mf_options']['fields'] as $field_id => $field) {
            $value = get_option( $field_id . '_value' );

            if ( ( $value === false || $value == '' || $value === 'null' ) && $field['is_mandatory'] ) {
                return [ 'status' => 'error', 'message' => $field['label'] . ' is missng' ];
            } else {
                $mongo_credentials[$field_id] = $value;
            }
        }

        return $mongo_credentials;
    }

    private function get_sync_content() {
        $mongo_credentials = $this->get_the_settings_options();
        extract( $mongo_credentials );

        if( isset( $status ) && $status == 'error' ) {
            add_settings_error( 'Sync Mongo', 'sync-mongo', $message, 'error' );
            return;
        }

        if( !class_exists( 'MongoDB\Driver\Manager' ) ) {
            add_settings_error( 'Sync Mongo', 'sync-mongo', 'The "mongodb" php extension is missing. Please contact your hosting in order to activate/install it', 'error' );
            return;
        }

        $this->check_for_sync_request();

        try {
            $mongo_client   = new Mongo_client( $mf_user, $mf_pass, $mf_server, $mf_database );
            $db_objects     = $mongo_client->get_collection( $mf_collection );
        }
        catch (Exception $e) {
            add_settings_error( 'Sync Mongo', 'sync-mongo', $e->getMessage(), 'error' );
            return;
        }
        
        $mongo_results = new Mongo_List_Table();

        $cont = 0;
        foreach( $db_objects as $object ) {
            if( isset( $mf_number_of_post ) && $mf_number_of_post && ( $cont >= intval( $mf_number_of_post ) ) ){
                break;
            }

            $mongo_results->add_item( $this->prepare_object( $object ) );
            $cont++;
        }

        update_option( 'mf-temp-data-to-push', $mongo_results->get_mf_data() );
        return $this->display_sync_content( $mongo_results );
    }

    private function check_for_sync_request() {
        if( isset( $_POST['mf_sync_mongo_db_now'] ) ) {
            $data_to_push = get_option( 'mf-temp-data-to-push' );

            foreach( $data_to_push as $data ) {
                $this->create_new_wp_posts( $data );
            }

            add_settings_error( 'Sync Mongo', 'sync-mongo', 'Posts Syncronyzed Successfully', 'success' );
        }
    }

    private function display_sync_content( $mongo_results ){
        ob_start();
        echo '<form method="post" action="">';

        $mongo_results->prepare_items(); 
        $mongo_results->display(); 
        submit_button( 'Sync Mongo DB', 'primary', 'mf_sync_mongo_db_now' );
        
        echo '</form>';
        return ob_get_clean();
    }

    private function prepare_object( $object ) {
        $object_to_insert = [
            'ID'                    => $object->_id->__toString(),
            'post_title'            => $object->title,
            'source_meta'           => $object->publisher,
            'news_date_meta'        => $object->published->toDateTime()->format('Y-m-d'),
            'url'                   => $object->url,
            'post_content'          => $object->abstract,
            'post_tag'              => $object->keyword,
            'post_already_exist'    => $this->check_if_post_alreay_exist( $object->title ),
        ];

        return $object_to_insert;
    }

    public function check_if_post_alreay_exist( $post_title ) {
        $result = get_page_by_title( $post_title, 'OBJECT', 'post' );
        if( $result ) {
            return true;
        }

        return false;
    }

    public function create_new_wp_posts( $data ) {
        /**
         * Always publish to "News" category
        */
        $cat_name       = 'News';
        $news_cat_id    = get_cat_ID( $cat_name);

        extract( $data );
        $data = $data;
        $post_arr = [
            'post_title'    => $post_title,
            'post_content'  => $post_content,
            'post_category' => [ $news_cat_id ],
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'tags_input'    => [ $post_tag ],
            'meta_input'    => [
                'source'        => $source_meta,
                'link'          => $url,
                'start_date'    => $news_date_meta,
                'Type'          => $cat_name
            ],
        ];

        return wp_insert_post( $post_arr );
    }

    public function settings_page() {
        foreach ( $this->settings_sections as $section_id => $section ) {
            add_settings_section( $section_id, $section['label'], array($this, $section['callback']), $section['page']);

            foreach ( $section['fields'] as $field_id => $field ) {
                register_setting( $field['option_group'], $field_id . '_value' );
                add_settings_field( 'mf_field_' . $field_id, $field['label'], array($this, 'render_' . $field['type'] ), $section['page'], $section_id, [ 'id' => $field_id ] );
            }
        }
    }

    public function general_options() {

    }

    public function render_text( $args ) {
        $field_id   = $args['id'];
        $value      = get_option( $field_id . '_value' );
        $html       = '<input type="text" name="' . $field_id . '_value" value="' . $value . '"/>';

        echo $html;
    }

    public function render_number( $args ) {
        $field_id   = $args['id'];
        $value      = get_option( $field_id . '_value' );
        $html       = '<input type="number" name="' . $field_id . '_value" value="' . $value . '"/>';

        echo $html;
    }

}
