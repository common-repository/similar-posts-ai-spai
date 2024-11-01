<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://my-plugin/author
 * @since      1.0.0
 *
 * @package    Spai
 * @subpackage Spai/admin/partials
 */
?>

<?php
/**
 *  Load all form element values
 */
$options = get_option( SPAI_PLUGIN_NAME );

$reg_url = __SPAI_SITE__.'/auth/registration';
$auth_url = __SPAI_SITE__.'/auth/login';
$telegram_chat= 'https://t.me/+WXKIeXqRcrk4MTc6';
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="spai-plugin-container">
    <div class="spai-white-block spai-head mb20">
        <div class="spai-head__plugin-name">
	        <?php echo esc_html( 'Similar posts AI' );?>
        </div>
    </div>

    <div class="spai-content">
        <div class="spai-white-block spai-api-key p20">
            <form method="post" name="my_options" action="options.php">

                <?php

                /**
                 *   Returns nothing. Displays hidden input fields.
                 */
                settings_fields( SPAI_PLUGIN_NAME );
                do_settings_sections( SPAI_PLUGIN_NAME );

                ?>

                <div class="mb20">
                    <?php
                    printf( __( "To get a token, you need to <a href=\"%s\" target=\"_blank\">register</a> or <a href= \"%s\" target=\"_blank\" >log in</a> on the %s website.<br /> After adding the site, you will be given an API key.", SPAI_PLUGIN_NAME ), $reg_url, $auth_url, __SPAI_SITE__ );
                    ?>
                </div>
                <div class="mb20">
                    <?php
                    _e( 'This is necessary, since the algorithm works on the developer\'s server. <br /> Calculating similar articles on a third-party server reduces the load from your server.', SPAI_PLUGIN_NAME );
                    ?>
                </div>
                <div class="mb20">
                    <?php
                    printf( __( "If you have problems with this, you can ask for help in our  <a href=\"%s\" target=\"_blank\">telegram chat</a>.", SPAI_PLUGIN_NAME ), $telegram_chat, __SPAI_SITE__ );
                    ?>
                </div>

                <fieldset>
                    <?php
                        if( !empty( $api_key ) && isset( $checkToken['response']['code'] ) && $checkToken['response']['code'] != 200 ) {
                            $response = json_decode($checkToken['body'], true);
                            echo esc_html( $response['message'] );
                        }
                    ?>
                    <input type="text"
                           class="regular-text" id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-api_key' );?>"
                           name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[api_key]' );?>"
                           value="<?php if( !empty( $api_key ) ) { echo esc_attr( $api_key ); } ?>"
                           placeholder="<?php __( 'Enter your API key', SPAI_PLUGIN_NAME );?>"
                    />
                    <?php submit_button( __( 'Connect using an API key', SPAI_PLUGIN_NAME ), 'primary', 'submit', FALSE ); ?>
                </fieldset>
            </form>
        </div>
    </div>
</div>
