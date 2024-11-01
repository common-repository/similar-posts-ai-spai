<?php

/**
 * Fired during plugin activation
 *
 * @link       https://my-plugin/author
 * @since      1.0.0
 *
 * @package    Spai
 * @subpackage Spai/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Spai
 * @subpackage Spai/includes
 * @author     Author <admin@spai.site>
 */
class Spai_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
    {
        self::createSpaiPostsDataSync();
	    self::createConnectionToExternalServer();
	}

	private static function createSpaiPostsDataSync()
    {
        global $wpdb;
        $table_spai_posts_data_sync_version = '1.0';
        $table_name = $wpdb->prefix . "spai_posts_data_sync";

        self::createTableSpaiPostsDataSync( $table_name );
        self::addDataInTableSpaiPostsDataSync( $table_name );

        add_option( "spai_table_posts_data_sync_version", $table_spai_posts_data_sync_version );
    }

	private static function createTableSpaiPostsDataSync( $table_name )
    {

        $sql = "CREATE TABLE " . $table_name . " (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          post_id mediumint(9) DEFAULT 0,
          is_sync BIT(1) DEFAULT 0,
          UNIQUE KEY id (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }

    private static function addDataInTableSpaiPostsDataSync( $table_name )
    {
        global $wpdb;
        $posts = get_posts(
            array(
                'numberposts' => -1,
                'post_status' => array('publish', 'future'),
            )
        );
        foreach ( $posts as $post ) {
            $wpdb->insert( $table_name, array( 'post_id' => $post->ID) );
        }
    }

	private static function createConnectionToExternalServer()
	{
		$admin_email = get_option( 'admin_email' );
		$siteurl = get_option( 'siteurl' );
		$blog_name = get_option( 'blogname' );

        require_once plugin_dir_path( __FILE__ ) . 'class-spai-api.php';
		$api = new Spai_Api(null);

		$data = [
			'admin_email' => $admin_email,
			'site_url' => $siteurl,
			'blog_name' => $blog_name
		];

		$answer = $api->createConnectionToNewUser( $data );
        if( is_wp_error( $answer ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'class-spai-logger.php';

            $logger = Spai_Logger::getInstance();
            $logger->log(
                1,
                json_encode($answer)
            );
            return false;
        }
		$json =  json_decode( json_decode( $answer['body'] ) );

		if( $json->result == true ) {
			$update_data = [
				'api_key' => $json->data->api_key
			];

			update_option( 'spai', $update_data );
		}

	}
}
