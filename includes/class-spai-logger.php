<?php
require_once plugin_dir_path( __FILE__ ) . 'class-spai-api.php';

/**
 * Register all actions and filters for the plugin
 *
 * @since      1.4.3
 *
 * @package    Spai
 * @subpackage Spai/includes
 */

class Spai_Logger {

    private static $_logger = null;

    private $api;

    private $my_plugin_options;

    private function __construct()
    {
        $this->my_plugin_options = get_option( 'spai' );
        $token = isset($this->my_plugin_options['api_key']) ? $this->my_plugin_options['api_key'] : null;
        $this->api = new Spai_Api($token);
    }

    public static function getInstance()
    {
        if (!self::$_logger) {
            self::$_logger = new self;
        }

        return self::$_logger;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    /**
     * @param int $level
     * @param string $message
     *
     * @return void
     */
    public function log($level, $message)
    {
        $data = [
            'level' => $level,
            'message' => $message
        ];
        $this->api->log($data);
    }
}
