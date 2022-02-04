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



class Mongo_Settings {

    /**
	 * The settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	// static private $settings_sections;

    static public function init() {
        // self::$settings_sections = get_settings_sections()
        self::settings_page();

        $value = get_option( 'wn_test_cron_update' );
    }

    static private function settings_sections() {
        return (array) require MONGO_FETCHER_PLUGIN_PATH . 'admin/config/settings-sections.php';
    }

    static private function settings_page() {
        foreach ( self::settings_sections() as $section_id => $section ) {
            add_settings_section( $section_id, $section['label'], array(self::class, $section['callback']), $section['page']);

            foreach ( $section['fields'] as $field_id => $field ) {
                register_setting( $field['option_group'], $field_id . '_value' );
                $args = [
                    'id'        => $field_id,
                    'options'   => $field['options'] ?? false ,
                ];
                add_settings_field( 'mf_field_' . $field_id, $field['label'], array(self::class, 'render_' . $field['type'] ), $section['page'], $section_id, $args );
            }
        }
    }

    static function general_options() {

    }

    static function render_text( $args ) {
        $field_id   = $args['id'];
        $value      = get_option( $field_id . '_value' );
        $html       = '<input type="text" name="' . $field_id . '_value" value="' . $value . '"/>';

        echo $html;
    }

    static function render_number( $args ) {
        $field_id   = $args['id'];
        $value      = get_option( $field_id . '_value' );
        $html       = '<input type="number" name="' . $field_id . '_value" value="' . $value . '"/>';

        echo $html;
    }

    static function render_select( $args ) {
        $field_id   = $args['id'];
        $value      = get_option( $field_id . '_value' );
        $options    = $args['options'] ?? null;
        $html       = '<select name="' . $field_id . '_value" value="' . $value . '">';

        foreach( $options as $option_label => $option_value ) {
            $selected   = ( $option_value == $value ) ? 'selected="selected"' : '';
            $html       = $html . '<option value="' . $option_value . '" ' . $selected . ' >' . $option_label . '</option>';
        }

        $html .= '</select>';
        echo $html;
    }

    static function render_time( $args ) {
        $field_id   = $args['id'];
        $value      = get_option( $field_id . '_value' );
        $html       = '<input type="time" name="' . $field_id . '_value" value="' . $value . '"/>';

        echo $html;
    }

    static function get_settings_fields( $options ) {
        $settings_sections = self::settings_sections();

        return $settings_sections[$options]['fields'];
    }
    
}