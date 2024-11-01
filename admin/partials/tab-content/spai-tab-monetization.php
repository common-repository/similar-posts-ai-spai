<?php

/** @var string $api_key */
$enableMonitizationLink = __SPAI_SITE__ . '/auth/by-site-api-key?api_key=' . $api_key . '&redirect=/sites/';
?>

<div class="">
    <div class="mt20">
        <label class="spai-input_label"><?php
            _e('Monetization', SPAI_PLUGIN_NAME); ?>:</label>
        <div class="spai-input_division">
            <?php
            /** @var bool $show_ads */
            if ($show_ads === true) {
                echo 'Enabled';
                echo ' <a href="' . $enableMonitizationLink . '">Disable</a>';
            } else {
                echo 'Disabled';
                echo ' <a href="' . $enableMonitizationLink . '">Enable</a>';
            }
            ?>
        </div>
    </div>
    <div class="mt20">
        <label class="spai-input_label"><?php
            _e('Your balance', SPAI_PLUGIN_NAME); ?>:</label>
        <div class="spai-input_division">
            <?php
            /** @var float $balance */
            echo esc_attr($balance); ?>
        </div>
    </div>
</div>
