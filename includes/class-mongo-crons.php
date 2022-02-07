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
 * 
 */

class Mongo_Crons {

    private $cron_job_name;

    public function __construct( ){
        $this->cron_job_name = 'mongo_fetcher_cron_job';
        $this->init();
    }

    private function init() {
        $this->register_update_action_hook();
    }

    public function get_cron_name(){
        return $this->cron_job_name;
    }

    public function register_update_action_hook() {
        add_action( 'update_option', function( $option, $old_value, $value ) {
            if( 'mf_frecuency_field_value' != $option && 'mf_time_field_value' != $option ) {
                return;
            }

            if( 'mf_time_field_value' == $option ) {
                $time  = $value;
                $value = get_option( 'mf_frecuency_field_value' );
            }
            else {
                $time = get_option( 'mf_time_field_value' );
            }

            $time = ( $time ) ? strtotime( $time ) : time();

            if( $value == 0 ) {
                $this->remove_the_cron_job();
            }
            else {
                $this->set_the_cron_job( $value, $time );
            }

        }, 10, 3);
    }

    public function set_the_cron_job( $interval_value, $time ) {
        $interval_name = 'every_' . $interval_value . '_days';
        update_option( 'mf-interval-value', $interval_value  );

        if ( wp_next_scheduled( $this->cron_job_name ) ) {
           $this->remove_the_cron_job();
        }
        
        wp_schedule_event( $time, $interval_name, $this->cron_job_name );
    }

    public function remove_the_cron_job() {        
        $timestamp  = wp_next_scheduled( $this->cron_job_name );
        wp_unschedule_event( $timestamp, $this->cron_job_name );
    }

    public function run() {
        update_option( 'wn_test_cron_update', time() );
    }

    public function add_custom_interval( $schedules ) { 
        $interval_value = get_option( 'mf-interval-value' );

        if( $interval_value ) {
            $interval_name  = 'every_' . $interval_value . '_days';
            $schedules[$interval_name] = array(
                'interval' => 60 * 60 * 24 * floatval( $interval_value ),
                'display'  => esc_html__( 'Every ' . $interval_value .  ' Days' ), );
        }
        
        return $schedules;
    }
}