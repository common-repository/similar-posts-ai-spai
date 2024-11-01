<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://spai.site/
 * @since      1.0.0
 *
 * @package    Spai
 * @subpackage Spai/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @property   Spai_Admin
 * @package    Spai
 * @subpackage Spai/admin
 * @author     Spai <admin@spai.site>
 */
class Spai_Admin
{

    /**
     * Default display_type.
     *
     * @since    1.1.5
     */
    const DISPLAY_TYPE_DEFAULT = 5;

    /**
     * Default display_type.
     *
     * @since    1.1.5
     */
    const SHOW_ON_DEFAULT = 1;

    /**
     * Default template.
     *
     * @since    1.3.0
     */
    const TEMPLATE_DEFAULT = 4;

    /**
     * @var Spai_Api $api
     */
    public $api;

    /**
     * @var false|mixed|null
     */
    private $my_plugin_options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->my_plugin_options = get_option(SPAI_PLUGIN_NAME);

        $this->load_dependencies();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function spai_enqueue_styles()
    {
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

        wp_enqueue_style(SPAI_PLUGIN_NAME . '-short_codes',
            plugin_dir_url(__FILE__) . '../public/css/spai-short_codes.css', array(), SPAI_VERSION, 'all');
        wp_enqueue_style(SPAI_PLUGIN_NAME . '-spai-admin', plugin_dir_url(__FILE__) . 'css/spai-admin.css', array(), SPAI_VERSION,
            'all');
        wp_enqueue_style(SPAI_PLUGIN_NAME . '-spectrum', plugin_dir_url(__FILE__) . 'libs/spectrum/spectrum.min.css', array(), SPAI_VERSION,
            'all');
        wp_enqueue_style( SPAI_PLUGIN_NAME . '-short_codes', __SPAI_PUBLIC__ . 'css/spai-short_codes.css', array(), SPAI_VERSION, 'all' );
        wp_enqueue_style( SPAI_PLUGIN_NAME . '-public', __SPAI_PUBLIC__ . 'css/spai-public.css', array(), SPAI_VERSION, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function spai_enqueue_scripts()
    {
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

        wp_enqueue_script(
            SPAI_PLUGIN_NAME .'-spai-admin', plugin_dir_url(__FILE__) . 'js/spai-admin.js',
            array('jquery'),
            SPAI_VERSION,
            false
        );
        wp_enqueue_script(
            SPAI_PLUGIN_NAME .'-spai-admin-preview', plugin_dir_url(__FILE__) . 'js/spai-admin-preview.js',
            array('jquery'),
            SPAI_VERSION,
            false
        );
        wp_enqueue_script(SPAI_PLUGIN_NAME .'-spectrum', plugin_dir_url(__FILE__) . 'libs/spectrum/spectrum.min.js');
        wp_enqueue_script(SPAI_PLUGIN_NAME .'-chartjs', plugin_dir_url(__FILE__) . 'libs/chartjs/3.8.0/chart-3.8.0.js');
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu()
    {
        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         */
        add_options_page('Settings', 'Similar Posts AI', 'manage_options', SPAI_PLUGIN_NAME,
            array($this, 'display_plugin_admin_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function spai_add_action_links($links)
    {
        $additionalLinks = array(
            '<a href="' . admin_url('options-general.php?page=' . SPAI_PLUGIN_NAME) . '">' . __('Settings',
                SPAI_PLUGIN_NAME) . '</a>',
            '<a target="_blank" href="https://t.me/+WXKIeXqRcrk4MTc6">' . __('Support', SPAI_PLUGIN_NAME) . '</a>',
        );
        return array_merge($additionalLinks, $links);
    }

    public function spai_wp_resource_hints($urls, $relation_type)
    {
        if ('dns-prefetch' === $relation_type) {
            $urls[] = __SPAI_SITE__;
        }

        return $urls;
    }

    /**
     * Render the settings page for this plugin.
     *
     * @throws RuntimeException
     * @since    1.0.0
     */
    public function display_plugin_admin_page()
    {
        $api_key = isset($this->my_plugin_options['api_key']) ? $this->my_plugin_options['api_key'] : null;
        $checkToken = $this->api->checkToken();
        if (is_wp_error($checkToken)) {
            $error = $checkToken;
            include_once('partials/spai-admin-display-error.php');
            throw new \RuntimeException(print_r($error, true));
        }

        if (
            !empty($api_key)
            && isset($checkToken['response']['code']) && $checkToken['response']['code'] == 200
        ) {
            $response = json_decode(json_decode($checkToken['body'], true), true);
            $balance = isset($response['user']['balance']) ? $response['user']['balance'] : 0;
            $show_ads = isset($response['site']['show_ads']) ? (bool)$response['site']['show_ads'] : false;
            $heading = isset($this->my_plugin_options['heading']) ? $this->my_plugin_options['heading'] : null;
            $default_image = isset($this->my_plugin_options['default_image']) ? $this->my_plugin_options['default_image'] : null;
            $display_type = isset($this->my_plugin_options['display_type']) ? $this->my_plugin_options['display_type'] : self::DISPLAY_TYPE_DEFAULT;
            $showOn = isset($this->my_plugin_options['showOn']) ? $this->my_plugin_options['showOn'] : self::SHOW_ON_DEFAULT;
            $template = isset($this->my_plugin_options['template']) ? $this->my_plugin_options['template'] : self::TEMPLATE_DEFAULT;
            $title_max = isset($this->my_plugin_options['title_max']) ? $this->my_plugin_options['title_max'] : null;
            $effect_of_increasing_the_image_size = isset($this->my_plugin_options['effect_of_increasing_the_image_size']) ? $this->my_plugin_options['effect_of_increasing_the_image_size'] : true;
            $show_category = isset($this->my_plugin_options['show_category']) ? (bool)$this->my_plugin_options['show_category'] : true;
            $heading_color = isset($this->my_plugin_options['heading_color']) ? $this->my_plugin_options['heading_color'] : null;
            $category_color = isset($this->my_plugin_options['category_color']) ? $this->my_plugin_options['category_color'] : null;
            $category_background_color = isset($this->my_plugin_options['category_background_color']) ? $this->my_plugin_options['category_background_color'] : null;

            $statistic = $response['site']['statistic'];
            $impStat = $statistic['impressions'];
            $clicksStat = $statistic['clicks'];

            //spai_posts_data_sync count synced
            global $wpdb;
            $table_name = $wpdb->prefix . "spai_posts_data_sync";
            $check_posts_sync = $wpdb->get_results("SELECT * FROM " . $table_name);

            $total_posts_in_spai_posts_data_sync = count($check_posts_sync);
            $synced = 0;

            if ($check_posts_sync) {
                foreach ($check_posts_sync as $check_post_sync) {
                    if ((bool)$check_post_sync->is_sync === true) {
                        $synced++;
                    }
                }
            }

            include_once('partials/spai-admin-display.php');
        } else {
            include_once('partials/spai-admin-setup.php');
        }
    }


    /**
     * Validate options
     */
    public function validate($input)
    {
        $valid = array();
        $valid['api_key'] = (isset($input['api_key']) && !empty($input['api_key'])) ? sanitize_text_field($input['api_key']) : '';
        $valid['heading'] = (isset($input['heading']) && !empty($input['heading'])) ? sanitize_text_field($input['heading']) : '';
        $valid['default_image'] = (isset($input['default_image']) && !empty($input['default_image'])) ? esc_url_raw($input['default_image']) : '';

        $valid['display_type'] = ( isset( $input['display_type'] ) && !empty( $input['display_type'] ) ) ? (int) $input['display_type'] : self::DISPLAY_TYPE_DEFAULT;
        if( $valid['display_type'] > 5 || $valid['display_type'] < 1 ) { $valid['display_type'] = self::DISPLAY_TYPE_DEFAULT; }

        $valid['template'] = ( isset( $input['template'] ) && !empty( $input['template'] ) ) ? (int) $input['template'] : self::TEMPLATE_DEFAULT;
        if( $valid['template'] > 7 || $valid['template'] < 1 ) { $valid['template'] = self::TEMPLATE_DEFAULT; }

        $valid['showOn'] = ( isset( $input['showOn'] ) && !empty( $input['showOn'] ) ) ? (int) $input['showOn'] : self::SHOW_ON_DEFAULT;
        if( $valid['showOn'] > 3 || $valid['showOn'] < 1 ) { $valid['showOn'] = self::SHOW_ON_DEFAULT; }

        $valid['title_max'] = ( isset( $input['title_max'] ) && !empty( $input['title_max'] ) ) ? (int)$input['title_max'] : 50;

        if(isset($input['effect_of_increasing_the_image_size'])) {
            $valid['effect_of_increasing_the_image_size'] = (bool)$input['effect_of_increasing_the_image_size'];
        } else {
            $valid['effect_of_increasing_the_image_size'] = false;
        }

        if(isset($input['show_category'])) {
            $valid['show_category'] = (bool)$input['show_category'];
        } else {
            $valid['show_category'] = false;
        }


        $valid['heading_color'] = (isset($input['heading_color']) && !empty($input['heading_color'])) ? sanitize_text_field($input['heading_color']) : '';
        $valid['category_color'] = (isset($input['category_color']) && !empty($input['category_color'])) ? sanitize_text_field($input['category_color']) : '';
        $valid['category_background_color'] = (isset($input['category_background_color']) && !empty($input['category_background_color'])) ? sanitize_text_field($input['category_background_color']) : '';

        return $valid;
    }

    /**
     * Update all options
     *
     */
    public function options_update()
    {
        register_setting(SPAI_PLUGIN_NAME, SPAI_PLUGIN_NAME, array($this, 'validate'));
    }

    private function load_dependencies()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-spai-api.php';

        $token = isset($this->my_plugin_options['api_key']) ? $this->my_plugin_options['api_key'] : null;
        $this->api = new Spai_Api($token);
    }

    public function addPostToTableSpaiPostsDataSync($post_id, $post)
    {
        if (in_array($post->post_type, array('post')) && in_array($post->post_status, array('publish'))) {
            global $wpdb;
            $table_name = $wpdb->prefix . "spai_posts_data_sync";

            $check = $wpdb->get_results("SELECT * FROM " . $table_name . ' WHERE post_id = ' . $post_id);
            if ($check) {
                $wpdb->update($table_name, array('is_sync' => false), array('post_id' => $post_id));
            } else {
                $wpdb->insert($table_name, array('post_id' => $post_id));
            }
        }
    }

}
