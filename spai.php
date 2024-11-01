<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://spai.site
 * @since             1.0.0
 * @package           Spai
 *
 * @wordpress-plugin
 * Plugin Name:       Similar Posts AI (SPAI)
 * Description:       Creates an AI-based recommended articles widget. The fastest plugin, since all calculations take place on the developer's servers.
 * Version:           1.8.0
 * Author:            SPAIgroup
 * Author URI:        http://spai.site/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spai
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('SPAI_PLUGIN_NAME', 'spai');
define('SPAI_PLUGIN_URL', plugin_dir_url( __FILE__ ));

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SPAI_VERSION', '1.8.0' );

define('__SPAI_SITE__', 'https://spai.site');

//ini_set('display_errors', 0);

function shutDownFunction() {
    global $wp_version;
    $error = error_get_last();
    if (!empty($error)
        && (
            $error['type'] === E_ERROR
            || $error['type'] === E_WARNING
            || $error['type'] === E_DEPRECATED
            || $error['type'] === E_NOTICE
        )
    ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-spai-logger.php';

        /** @var Spai_logger $logger */
        $logger = Spai_Logger::getInstance();

        $currentTheme = wp_get_theme();
        $additionalInfo = [
            'php_version' => PHP_VERSION,
            'wp_version' => $wp_version,
            'wp_theme' => esc_html( $currentTheme->get( 'TextDomain' ) ),
            'pluginVersion' => SPAI_VERSION
        ];

        $logger->log(
            $error['type'],
            json_encode(array_merge($additionalInfo, $error))
        );
    }
}
register_shutdown_function('shutDownFunction');

try {

    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-spai-activator.php
     */
    function activate_spai_plugin() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-spai-activator.php';
        Spai_Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-spai-deactivator.php
     */
    function deactivate_spai_plugin() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-spai-deactivator.php';
        Spai_Deactivator::deactivate();
    }

    register_activation_hook( __FILE__, 'activate_spai_plugin' );
    register_deactivation_hook( __FILE__, 'deactivate_spai_plugin' );

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-spai.php';

    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_spai_plugin() {

        $plugin = new Spai();
        $plugin->run();

    }

    run_spai_plugin();

} catch (Exception $e) {
    global $wp_version;
    /**
     *
     * @since 1.5.0
     */
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-spai-logger.php';

    $logger = Spai_Logger::getInstance();

    $currentTheme = wp_get_theme();
    $additionalInfo = [
        'php_version' => PHP_VERSION,
        'wp_version' => $wp_version,
        'wp_theme' => esc_html( $currentTheme->get( 'TextDomain' ) ),
        'pluginVersion' => SPAI_VERSION
    ];

    $errorMessage = [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'code' => $e->getCode(),
        'trace' => $e->getTraceAsString(),
    ];

    $logger->log(
        1,
        json_encode(array_merge($additionalInfo, $errorMessage))
    );
}
