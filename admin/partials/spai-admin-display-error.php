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


/** @var WP_Error $error */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="spai-plugin-container">
    <div class="spai-white-block spai-head mb20">
        <div class="spai-head__plugin-name">
            <?php echo esc_html( 'Similar posts AI' );?>
        </div>
    </div>

    <div class="spai-content">
        <div class="spai-white-block spai-api-key">
            <div class="p20">
                <?php
                echo $error->errors['http_request_failed'][0]
                ?>
            </div>
            <div class="p20">
                <?php echo __('Contact us', SPAI_PLUGIN_NAME); ?>
                <a target="_blank" href="https://t.me/+WXKIeXqRcrk4MTc6"><?php echo __('Support', SPAI_PLUGIN_NAME); ?></a>
            </div>
        </div>
    </div>
</div>
