<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://my-plugin/author
 * @since      1.0.0
 *
 * @package    Spai
 * @subpackage Spai/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Spai
 * @subpackage Spai/includes
 * @author     Author <admin@spai.site>
 */
class Spai_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        self::DeleteTableSpaiPostsDataSync();
	}

	private static function DeleteTableSpaiPostsDataSync()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "spai_posts_data_sync";

        $sql = "DROP TABLE IF EXISTS " . $table_name . ";";
        $wpdb->query( $sql );
    }

}
