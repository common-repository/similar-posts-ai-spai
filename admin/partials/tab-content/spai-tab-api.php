<div class="">
    <div class="mt20">
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-api_key' );?>">
            <?php _e( 'API', SPAI_PLUGIN_NAME );?>
        </label>
        <div class="spai-input_division">
            <input type="password"
               class="regular-text" id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-api_key' );?>"
               name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[api_key]' );?>"
               value="<? if( !empty( $api_key ) ) { echo esc_attr( $api_key ); } ?>"
               placeholder="<?php esc_attr_e( 'Enter your API key', SPAI_PLUGIN_NAME );?>">
               <div class="toggle_api_key" onclick="toggleApiKey(this)">Show</div>
        </div>
    </div>
    <div class="mt20">
        <label class="spai-input_label"><?php _e( 'Total posts', SPAI_PLUGIN_NAME );?>:</label>
        <div class="spai-input_division">
			<?php
            /** @var integer $total_posts_in_spai_posts_data_sync */
			echo esc_attr( $total_posts_in_spai_posts_data_sync ); ?>
        </div>
    </div>
    <div class="mt20">
        <label class="spai-input_label"><?php _e( 'Synchronized post', SPAI_PLUGIN_NAME );?>:</label>
        <div class="spai-input_division">
	        <?php
            /** @var integer $synced */
	        echo esc_attr( $synced ); ?>
        </div>
    </div>
</div>

<script>
    function toggleApiKey(e)
    {
        var api_key = document.getElementById("<?php echo esc_attr( SPAI_PLUGIN_NAME . '-api_key' );?>");
        if (api_key.type === "password") {
            api_key.type = "text";
            e.innerHTML = 'Hide';
        } else {
            api_key.type = "password";
            e.innerHTML = 'Show';
        }
    }
</script>