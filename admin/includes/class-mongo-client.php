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

class Mongo_client {

    private $mongo_connection;
    private $database;

    public function __construct( $user, $password, $server, $database ) {
        $this->database         = $database;
        
        $connection_string      = "mongodb://$user:$password@$server/$database";
        $this->mongo_connection = new MongoDB\Client( $connection_string );
    }

    public function get_collection( $collection_name ) {
        $database   = $this->database;
        $collection = $this->mongo_connection->$database->$collection_name;
        
        return $collection->find()->toArray();
    }
    
}