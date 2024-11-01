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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="spai-plugin-container">
    <div class="spai-white-block spai-head mb20">
        <div class="spai-head__plugin-name">
            <?php echo esc_html( 'Similar posts AI' );?>
        </div>
    </div>

    <div class="spai-white-block spai-content p20">

        <div class="spai-page-title">
            <h2><?php _e( get_admin_page_title(), SPAI_PLUGIN_NAME  ); ?></h2>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab-design"><?php _e( 'Design', SPAI_PLUGIN_NAME );?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-monetization"><?php _e('Monetization', SPAI_PLUGIN_NAME);?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-statistic"><?php _e('Statistic', SPAI_PLUGIN_NAME);?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-contact"><?php _e('Support', SPAI_PLUGIN_NAME);?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab-api"><?php _e( 'API', SPAI_PLUGIN_NAME );?></a>
            </li>
        </ul>

        <div class="spai-settings-content">
            <form method="post" name="my_options" action="options.php">

                <?php

                /**
                 *   Returns nothing. Displays hidden input fields.
                 */
                settings_fields( SPAI_PLUGIN_NAME );
                do_settings_sections( SPAI_PLUGIN_NAME );

                ?>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab-design">
                        <?php include_once( 'tab-content/spai-tab-design.php' );?>
                    </div>
                    <div class="tab-pane" id="tab-monetization">
                        <?php include_once( 'tab-content/spai-tab-monetization.php' );?>
                    </div>
                    <div class="tab-pane" id="tab-statistic">
                        <?php include_once( 'tab-content/spai-tab-statistic.php' );?>
                    </div>
                    <div class="tab-pane" id="tab-contact">
                        <?php include_once( 'tab-content/spai-tab-contact.php' );?>
                    </div>
                    <div class="tab-pane" id="tab-api">
                        <?php include_once( 'tab-content/spai-tab-api.php' );?>
                    </div>
                </div>

                <?php submit_button( __( 'Save all changes', SPAI_PLUGIN_NAME), 'primary', 'submit', TRUE ); ?>
            </form>
        </div>
    </div>
</div>
