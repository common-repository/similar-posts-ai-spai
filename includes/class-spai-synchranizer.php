<?php

/**
 * Sync data with spai server
 *
 * @since      1.6.9
 *
 * @package    Spai
 * @subpackage Spai/includes
 */

class Spai_Synchronizer {
    public $api;

    private $spai_options;

    public function __construct() {
        $this->spai_options = get_option( SPAI_PLUGIN_NAME );

        $token = (isset($this->spai_options['api_key'])) ? $this->spai_options['api_key'] : null;

        $this->api = new Spai_Api( $token );

    }

    /**
     * @param int $count
     *
     * @return bool|null
     */
    public function syncPosts($count = 3)
    {
        $token = (isset($this->spai_options['api_key'])) ? $this->spai_options['api_key'] : null;
        if ($token === null) {
            return null;
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "spai_posts_data_sync";
        $check_posts_sync = $wpdb->get_results( "SELECT * FROM " . $table_name . ' WHERE is_sync = false LIMIT ' . $count );

        if (!is_array($check_posts_sync) || count($check_posts_sync) === 0) {
            return null;
        }

        $total_posts_in_spai_posts_data_sync = $wpdb->get_results( "SELECT * FROM " . $table_name );
        $data = array(
            'posts' => array(),
            'total_posts' => count( $total_posts_in_spai_posts_data_sync )
        );

        foreach ( $check_posts_sync as $check_post_sync ) {
            $the_post = get_post( $check_post_sync->post_id );
            if( isset( $the_post ) && $the_post->ID >0 && $the_post->post_status === 'publish' ) {
                $tags = wp_get_post_tags( $the_post->ID);
                $post_tags = array();
                if( $tags ) {
                    foreach ( $tags as $tag ) {
                        $post_tags[] = array(
                            'id' => $tag->term_id,
                            'name' => $tag->name
                        );
                    }
                }

                $categories = wp_get_post_categories( $the_post->ID, array('fields' => 'all') );
                $post_categories = array();
                if( $categories ) {
                    foreach ( $categories as $category ) {
                        $post_categories[] = array(
                            'id' => $category->term_id,
                            'name' => $category->name
                        );
                    }
                }

                $data['posts'][] = array(
                    'post_id' => $the_post->ID,
                    'title' => $the_post->post_title,
                    'post_url' => get_permalink( $the_post->ID ),
                    'img_url' => get_the_post_thumbnail_url( $the_post->ID, 'medium' ),
                    'post_status' => $the_post->post_status,
                    'tags' => $post_tags,
                    'categories' => $post_categories
                );
            } else {
                $wpdb->delete( $table_name, [ 'post_id' => $check_post_sync->post_id ] );
            }
        }

        $result = $this->api->sendPostData( $data );
        if(
            is_wp_error( $result )
            || !isset($result['body'])
            || !is_string($result['body'])
        ) {
            require_once plugin_dir_path( __FILE__ ) . '../includes/class-spai-logger.php';

            $logger = Spai_Logger::getInstance();
            $logger->log(
                1,
                print_r($result, true)
            );
            return false;
        }
        try {
            $result = json_decode( json_decode( $result['body'] ) );
        } catch (Exception $e) {
            require_once plugin_dir_path( __FILE__ ) . '../includes/class-spai-logger.php';

            $logger = Spai_Logger::getInstance();
            $logger->log(
                1,
                print_r($result, true)
            );
            return false;
        }
        if( isset( $result->result ) && $result->result == true ) {
            $result_data = $result->data;
            foreach ( $result_data as $result_data_one ) {
                if( $result_data_one->is_synced == true ) {
                    $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET is_sync = %s WHERE post_id = %s", true, $result_data_one->post_id ) );
                }
            }
        }

        return true;
    }
}
