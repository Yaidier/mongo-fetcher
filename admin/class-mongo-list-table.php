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
        $columns = array(
            'post_status' => 'Status',
            'post_title' => 'Title',
        );

        return $columns;
    }

    public function add_item( $object_to_insert ) {
        array_push( $this->mf_data, $object_to_insert );
    }

    public function get_mf_data(){
        return $this->mf_data;
    }

    public function prepare_items() {
        $columns                = $this->get_columns();
        $hidden                 = array();
        $sortable               = array();
        $this->_column_headers  = array($columns, $hidden, $sortable);
        $this->items            = $this->mf_data;
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
          case 'post_title':
            return $item[ $column_name ];
          case 'post_status':
            return $item[ 'post_already_exist' ] ? '<span class="dashicons dashicons-warning"></span>' : '<span class="dashicons dashicons-yes-alt"></span>';
          default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
      }
}