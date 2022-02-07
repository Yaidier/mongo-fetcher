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

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Mongo_List_Table extends WP_List_Table {

    private $mf_data = [];

    public function get_columns(){
        $columns = [
            'cb'                    => '<input type="checkbox" />',
            'post_title'            => 'Title',
            'post_already_exist'    => 'Status',
        ];

        return $columns;
    }

    public function add_item( $object_to_insert ) {
        array_push( $this->mf_data, $object_to_insert );
    }

    public function get_mf_data(){
        return $this->mf_data;
    }

    public function set_mf_data( $mf_data ){
        return $this->mf_data = $mf_data;
    }

    public function prepare_items() {
        $this->process_bulk_action();

        $columns                = $this->get_columns();
        $hidden                 = array();
        $sortable               = $this->get_sortable_columns();
        $this->_column_headers  = array($columns, $hidden, $sortable);

        usort( $this->mf_data, array( $this, 'usort_reorder' ) );
        $this->items = $this->mf_data;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
          'post_already_exist' => array( 'post_already_exist',false ),
          'post_title'  => array( 'post_title', false ),
        );
        return $sortable_columns;
    }

    function usort_reorder( $a, $b ) {
        $orderby    = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'post_title';
        $order      = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        $result     = strcmp( $a[$orderby], $b[$orderby] );

        return ( $order === 'asc' ) ? $result : -$result;
      }

    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
          case 'post_title':
            return $item[ $column_name ];
          case 'post_already_exist':
            return $item[ 'post_already_exist' ] ? '<span class="dashicons dashicons-warning"></span>' : '<span class="dashicons dashicons-yes-alt"></span>';
          default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="post-ids-to-sync[]" value="%s" />', $item['ID'] );    
    }

    function get_bulk_actions() {
        $actions = array(
          'sync_now' => 'Sync Now'
        );
        return $actions;
    }

    function process_bulk_action() {
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );
        }
        
        if( $this->current_action() == 'sync_now' ) {
            $this->bulk_sync();
        }

        return;
    }

    function bulk_sync() {
        if( !isset( $_POST['post-ids-to-sync'] ) || empty( $_POST['post-ids-to-sync'] ) ) {
            return; 
        }

        $post_db_ids_to_sync = $_POST['post-ids-to-sync'];
        
        foreach( $this->mf_data as &$data ){
            if( in_array( $data['ID'], $post_db_ids_to_sync ) ) {   
                if( Mongo_Fetcher_Wp_Handler::insert_post( $data ) ) {
                    $data['post_already_exist'] = true;
                }
            }
        }

        add_settings_error( 'Sync Mongo', 'sync-mongo', 'Posts Syncronyzed Successfully', 'success' );
    }
}