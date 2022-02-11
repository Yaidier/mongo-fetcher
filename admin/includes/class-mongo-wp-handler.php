<?php

/**
 * The WP Halder functionalities of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mongo_Fetcher
 * @subpackage Mongo_Fetcher/admin
 */

/**
 * The WP Halder functionalities of the plugin.
 *
 * Creates new posts on Wordpress
 *
 * @package    Mongo_Fetcher
 * @subpackage Mongo_Fetcher/admin
 * @author     Yaidier Perez <yaidier.perez@gmail.com>
 * 
 */

class Mongo_Fetcher_Wp_Handler {

    public static function insert_post( $post_data ) {
        extract( $post_data );

        if( $post_id = post_exists( $post_title ) ) {
            return 'NOT INSERTED!!! This post already exist on wp with post id ' . $post_id;
        }

        /**
         * Always publish to "News" category
        */
        $cat_name       = 'News';
        $news_cat_id    = get_cat_ID( $cat_name);

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

        $response = wp_insert_post( $post_arr );
        
        if( is_wp_error( $response ) ) {
            return $response->get_error_messages();
        }

        return 'SUCCESS. Inserted to wp with post id ' . $response;
    }

}