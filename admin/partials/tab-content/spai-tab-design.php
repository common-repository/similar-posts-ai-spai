<?php
/* @var int $display_type */
/* @var int $template */
/* @var int $showOn */
?>
<div class="">
    <div class="mt20">
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-heading' );?>"><?php _e( 'Heading', SPAI_PLUGIN_NAME );?></label>
        <div class="spai-input_division">
            <input type="text"
               value="<?php if( !empty( $heading ) ) { echo esc_attr( $heading ); } else { echo 'Related posts:'; } ?>"
               name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[heading]' ); ?>"
               placeholder="<?php esc_attr_e( 'Heading', SPAI_PLUGIN_NAME ); ?>"
               id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-heading' ); ?>"
            >
        </div>
    </div>

    <div class="mt20">
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-heading_color' );?>"><?php _e( 'Heading color', SPAI_PLUGIN_NAME );?></label>
        <div class="spai-input_division">
            <input type="text"
                value="<?php if( !empty( $heading_color ) ) { echo esc_attr( $heading_color ); } else { echo ''; } ?>"
                name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[heading_color]' ); ?>"
                placeholder="<?php esc_attr_e( 'Color', SPAI_PLUGIN_NAME ); ?>"
                id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-heading_color' ); ?>"
            />
        </div>
    </div>

    <div class="mt20">
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-default_image' );?>"><?php _e( 'Default image', SPAI_PLUGIN_NAME ); ?></label>
        <div class="spai-input_division">
            <input type="text"
               value="<?php if( !empty( $default_image ) ) { echo esc_url( $default_image ); } ?>"
               name="<?php echo esc_attr( SPAI_PLUGIN_NAME .'[default_image]' ); ?>"
               placeholder="<?php esc_attr_e( 'Default image', SPAI_PLUGIN_NAME ); ?>"
               id="<?php echo esc_attr( SPAI_PLUGIN_NAME .'-default_image' ); ?>"
            >
        </div>
    </div>

    <div class="mt20 template-division">
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-template' );?>"><?php _e( 'Template', SPAI_PLUGIN_NAME );?></label>
        <div class="spai-input_division">
            <select name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[template]' );?>" id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-template' );?>">
                <option value="1" <?php if( $template === 1 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 1', SPAI_PLUGIN_NAME );?></option>
                <option value="2" <?php if( $template === 2 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 2', SPAI_PLUGIN_NAME );?></option>
                <option value="4" <?php if( $template === 4 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 4', SPAI_PLUGIN_NAME );?></option>
                <option value="5" <?php if( $template === 5 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 5', SPAI_PLUGIN_NAME );?></option>
                <option value="6" <?php if( $template === 6 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 6', SPAI_PLUGIN_NAME );?></option>
                <option value="7" <?php if( $template === 7 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 7 - List', SPAI_PLUGIN_NAME );?></option>
            </select>
        </div>
    </div>

    <div
        class="mt20 showOn-division"
        <?php
        if ( $template !== 3 ) { echo 'style="display:none;"'; }
        ?>
    >
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-showOn' );?>"><?php _e( 'Show on', SPAI_PLUGIN_NAME );?></label>
        <div class="spai-input_division">
            <select
                    name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[showOn]' );?>"
                    id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-showOn' );?>">
                <option value="1" <?php if( $showOn === 1 ) {echo 'selected';} ?>><?php esc_attr_e( 'All', SPAI_PLUGIN_NAME );?></option>
                <option value="2" <?php if( $showOn === 2 ) {echo 'selected';} ?>><?php esc_attr_e( 'Only mobile', SPAI_PLUGIN_NAME );?></option>
                <option value="3" <?php if( $showOn === 3 ) {echo 'selected';} ?>><?php esc_attr_e( 'Only web', SPAI_PLUGIN_NAME );?></option>
            </select>
        </div>
    </div>

    <div
        class="mt20 display_type-division"
        <?php
        if (  in_array($template, [3, 7]) ) { echo 'style="display:none;"'; }
        ?>
    >
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-display_type' );?>"><?php _e( 'Display type', SPAI_PLUGIN_NAME );?></label>
        <div class="spai-input_division">
            <select name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[display_type]' );?>" id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-display_type' );?>">
                <option value="1" <?php if( $display_type === 1 ) {echo 'selected';} ?>><?php esc_attr_e( '6 posts in 1 row', SPAI_PLUGIN_NAME );?></option>
                <option value="2" <?php if( $display_type === 2 ) {echo 'selected';} ?>><?php esc_attr_e( '4 posts in 1 row', SPAI_PLUGIN_NAME );?></option>
                <option value="3" <?php if( $display_type === 3 ) {echo 'selected';} ?>><?php esc_attr_e( '3 posts in 1 row', SPAI_PLUGIN_NAME );?></option>
                <option value="4" <?php if( $display_type === 4 ) {echo 'selected';} ?>><?php esc_attr_e( '4 posts in 2 rows', SPAI_PLUGIN_NAME );?></option>
                <option value="5" <?php if( $display_type === 5 ) {echo 'selected';} ?>><?php esc_attr_e( '3 posts in 2 rows', SPAI_PLUGIN_NAME );?></option>
            </select>
        </div>
    </div>

    <div class="mt20">
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-title_max' );?>"><?php _e( 'Maximum number of characters in a title', SPAI_PLUGIN_NAME ); ?></label>
        <div class="spai-input_division">
            <input type="number"
                value="<?php if( !empty( $title_max ) ) { echo esc_attr( $title_max ); } ?>"
                name="<?php echo esc_attr( SPAI_PLUGIN_NAME .'[title_max]' ); ?>"
                placeholder="50"
                style="width: 70px;"
                id="<?php echo esc_attr( SPAI_PLUGIN_NAME .'-title_max' ); ?>"
            >
        </div>
    </div>

    <div
        class="mt20 effect_of_increasing_the_image_size-division"
        <?php
        if (  in_array($template, [3, 7]) ) { echo 'style="display:none;"'; }
        ?>
    >
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-effect_of_increasing_the_image_size' );?>"><?php _e( 'Effect of increasing the image size', SPAI_PLUGIN_NAME ); ?></label>
        <div class="spai-input_division">
            <input type="checkbox"
                value="1"
                name="<?php echo esc_attr( SPAI_PLUGIN_NAME .'[effect_of_increasing_the_image_size]' ); ?>"
                id="<?php echo esc_attr( SPAI_PLUGIN_NAME .'-effect_of_increasing_the_image_size' ); ?>"
                <?php if( !empty( $effect_of_increasing_the_image_size ) && $effect_of_increasing_the_image_size === true ) { echo 'checked'; } ?>
            >
        </div>
    </div>

    <div
        class="mt20 show_category-division"
        <?php
        if (  in_array($template, [3, 7]) ) { echo 'style="display:none;"'; }
        ?>
    >
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-show_category' );?>"><?php _e( 'Show category', SPAI_PLUGIN_NAME ); ?></label>
        <div class="spai-input_division">
            <input type="checkbox"
                value="1"
                name="<?php echo esc_attr( SPAI_PLUGIN_NAME .'[show_category]' ); ?>"
                id="<?php echo esc_attr( SPAI_PLUGIN_NAME .'-show_category' ); ?>"
                <?php if( !empty( $show_category ) && $show_category === true ) { echo 'checked'; } ?>
            >
        </div>
    </div>

    <div
        class="mt20 category_color-division"
        <?php
        if (  in_array($template, [3, 7]) ) { echo 'style="display:none;"'; }
        ?>
    >
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-category_color' );?>">
            <?php _e( 'Category color', SPAI_PLUGIN_NAME );?>
        </label>
        <div class="spai-input_division">
            <input type="text"
                value="<?php if( !empty( $category_color ) ) { echo esc_attr( $category_color ); } else { echo ''; } ?>"
                name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[category_color]' ); ?>"
                placeholder="<?php esc_attr_e( 'Category color', SPAI_PLUGIN_NAME ); ?>"
                id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-category_color' ); ?>"
            />
        </div>
    </div>

    <div
        class="mt20 category_background_color-division"
        <?php
        if (  in_array($template, [3, 7]) ) { echo 'style="display:none;"'; }
        ?>
    >
        <label class="spai-input_label" for="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-category_background_color' );?>">
            <?php _e( 'Category background color', SPAI_PLUGIN_NAME );?>
        </label>
        <div class="spai-input_division">
            <input type="text"
                value="<?php if( !empty( $category_background_color ) ) { echo esc_attr( $category_background_color ); } else { echo ''; } ?>"
                name="<?php echo esc_attr( SPAI_PLUGIN_NAME . '[category_background_color]' ); ?>"
                placeholder="<?php esc_attr_e( 'Category background color', SPAI_PLUGIN_NAME ); ?>"
                id="<?php echo esc_attr( SPAI_PLUGIN_NAME . '-category_background_color' ); ?>"
            />
        </div>
    </div>
</div>
<div class="spai-widget-preview" style="display: none!important;">
    <div style="max-width: 700px;">
        <h3 class="spai-widget-preview-heading">
            Preview:
        </h3>
        <?php
            require_once plugin_dir_path( __FILE__ ) . '../../../includes/class-spai-widget-preview.php';
            $widgetPreview = new Spai_Widget_Preview();
            echo $widgetPreview->run();
        ?>
    </div>
</div>
