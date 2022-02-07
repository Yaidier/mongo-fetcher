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
        /**
         * Always publish to "News" category
        */
        $cat_name       = 'News';
        $news_cat_id    = get_cat_ID( $cat_name);

        extract( $post_data );
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
}