<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://my-plugin/author
 * @since      1.0.0
 *
 * @package    Spai
 * @subpackage Spai/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @property  Spai_Public
 * @package    Spai
 * @subpackage Spai/public
 * @author     Admin <admin@spai.site>
 */
class Spai_Public {

    public $api;

    /**
     * @var false|mixed|null
     */
    private $spai_options;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->spai_options = get_option( SPAI_PLUGIN_NAME );

        $token = (isset($this->spai_options['api_key'])) ? $this->spai_options['api_key'] : null;

        $this->api = new Spai_Api( $token );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function spai_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        wp_enqueue_style( SPAI_PLUGIN_NAME . '-short_codes', plugin_dir_url( __FILE__ ) . 'css/spai-short_codes.css', array(), SPAI_VERSION, 'all' );
		wp_enqueue_style( SPAI_PLUGIN_NAME . '-public', plugin_dir_url( __FILE__ ) . 'css/spai-public.css', array(), SPAI_VERSION, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function spai_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( SPAI_PLUGIN_NAME . '-public', plugin_dir_url( __FILE__ ) . 'js/spai-public.js', array( 'jquery' ), SPAI_VERSION, true );
		wp_enqueue_script( SPAI_PLUGIN_NAME . '-jquery-mobile', plugin_dir_url( __FILE__ ) . 'libs/jquery-mobile/1.4.5/jquery.mobile.custom.min.js', array( 'jquery', 'jquery-migrate' ), SPAI_VERSION, true );

	}

	/**
	 * The function of adding text to the footer
	 *
	 * @since    1.0.0
	 */

	public function spai_add_widget_after_post( $content ){
        // Return if it's not in the loop or in the main query.
        if ( ! in_the_loop() && ! is_main_query() ) {
            return $content;
        }

        if ( doing_filter('get_the_excerpt') ) {
            return $content;
        }

        if( is_single() ) {
            $widget = new Spai_Widget(0);
            $widget->injectPostIdJs($widget->getPostId());
            $content .= $widget->widget();
        }

        return $content;
	}


    public function spai_wp_resource_hints($urls, $relation_type)
    {
        if ('dns-prefetch' === $relation_type) {
            $urls[] = __SPAI_SITE__;
        }

        return $urls;
    }

    /**
     * The function of adding widget to the content
     *
     * @since    1.3.4
     */
    public function spai_register_widget() {
        register_widget( 'Spai_Widget' );
    }

    /**
     * @return bool|null
     */
    public function checkSpaiPostsDataSync()
    {
        require_once plugin_dir_path( __FILE__ ) . '../includes/class-spai-synchranizer.php';

        $synchronizer = new Spai_Synchronizer();

        return $synchronizer->syncPosts();
    }

    public function spai_save_related_post_click()
    {
        $post_id = ( int ) $_POST['spai_post_id'];
        $clicked_related_post_id = ( int ) $_POST['spai_clicked_related_post_id'];
	    $imp_id = ( int ) $_POST['spai_imp_id'];

	    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        $data = array(
            'post_id' => $post_id,
            'clicked_related_post_id' => $clicked_related_post_id,
            'imp_id' => $imp_id,
	        'ip' => $this->getIp(),
	        'user_agent' => $user_agent
        );
        $result = $this->api->sendClickToRelatedPost( $data );
        echo $result;
    }

    public function spai_get_related_posts() {
        $instance = (isset($_POST['instance']) && !empty($_POST['instance'])) ? $_POST['instance'] : [];
        $postId = (isset($_POST['postId']) && !empty($_POST['postId'])) ? $_POST['postId'] : null;

        try {
            $widget = new Spai_Widget(0);
            $widget->setPostId($postId);
            echo $widget->getContent($instance);exit;
        } catch (Exception $e) {

        }
    }

    public function spai_send_imp_is_loaded() {
        $postId = (isset($_POST['postId']) && !empty($_POST['postId'])) ? $_POST['postId'] : null;
        $impId = (isset($_POST['impId']) && !empty($_POST['impId'])) ? $_POST['impId'] : null;

        $data = [
            'postId' => $postId,
            'impId' => $impId
        ];

        $response = $this->api->sendImpressionIsLoaded($data);
        exit;
    }

    public function spai_define_javascript_ajaxurl() {
        if ( is_single() ) {
            ?>
            <script type="text/javascript">
                var spai_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            </script>
            <?php
        }
    }

    /**
     * @return string|null
     */
    private function getIp()
    {
        $ip = null;
        if (isset( $_SERVER['HTTP_CLIENT_IP'] ) and !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) and  !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset( $_SERVER['REMOTE_ADDR'] ) and  !empty( $_SERVER['REMOTE_ADDR']) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

}
