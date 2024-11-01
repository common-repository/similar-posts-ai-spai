<?php
class Spai_Widget_Form extends WP_Widget
{
    private $instance = null;

    public function __construct($instance)
    {
        $this->instance = $instance;
        parent::__construct(
            'spai_widget',
            __( 'Spai - related posts', 'spai' ),
            array(
                'customize_selective_refresh' => false,
            )
        );
    }

    public function show() {
        require_once 'class-spai-widget-instance.php';
        $instanceObj = new WidgetInstance($this->instance);
        $display_type = $instanceObj->getDisplayType();
        $template = $instanceObj->getTemplate();
        $showOn = $instanceObj->getShowOn();
        ?>
        <div class="mt20 template-division">
            <label class="spai-input_label" for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"><?php _e( 'Template', SPAI_PLUGIN_NAME );?></label>
            <div class="spai-input_division">
                <select
                    name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>">
                    <option value="1" <?php if( $template === 1 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 1', SPAI_PLUGIN_NAME );?></option>
                    <option value="2" <?php if( $template === 2 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 2', SPAI_PLUGIN_NAME );?></option>
                    <option value="3" <?php if( $template === 3 ) {echo 'selected';} ?>><?php esc_attr_e( 'Stories', SPAI_PLUGIN_NAME );?></option>
                    <option value="4" <?php if( $template === 4 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 4', SPAI_PLUGIN_NAME );?></option>
                    <option value="5" <?php if( $template === 5 ) {echo 'selected';} ?>><?php esc_attr_e( 'Template 5', SPAI_PLUGIN_NAME );?></option>
                </select>
            </div>
        </div>

        <div
            class="mt20 showOn-division"
            <?php
            if ( $template !== 3 ) { echo 'style="display:none;"'; }
            ?>
        >
            <label class="spai-input_label" for="<?php echo esc_attr( $this->get_field_id( 'showOn' ) ); ?>"><?php _e( 'Show on', SPAI_PLUGIN_NAME );?></label>
            <div class="spai-input_division">
                <select
                    name="<?php echo esc_attr( $this->get_field_name( 'showOn' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'showOn' ) ); ?>">
                    <option value="1" <?php if( $showOn === 1 ) {echo 'selected';} ?>><?php esc_attr_e( 'All', SPAI_PLUGIN_NAME );?></option>
                    <option value="2" <?php if( $showOn === 2 ) {echo 'selected';} ?>><?php esc_attr_e( 'Only mobile', SPAI_PLUGIN_NAME );?></option>
                    <option value="3" <?php if( $showOn === 3 ) {echo 'selected';} ?>><?php esc_attr_e( 'Only web', SPAI_PLUGIN_NAME );?></option>
                </select>
            </div>
        </div>

        <div
            class="mt20 display_type-division"
            <?php
            if ( $template === 3 ) { echo 'style="display:none;"'; }
            ?>
        >
            <label class="spai-input_label" for="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>">
                <?php _e( 'Display type', SPAI_PLUGIN_NAME );?>
            </label>
            <div class="spai-input_division">
                <select
                    name="<?php echo esc_attr( $this->get_field_name( 'display_type' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'display_type' ) ); ?>">
                    <option value="1" <?php if( $display_type === 1 ) {echo 'selected';} ?>><?php esc_attr_e( '6 posts in 1 row', SPAI_PLUGIN_NAME );?></option>
                    <option value="2" <?php if( $display_type === 2 ) {echo 'selected';} ?>><?php esc_attr_e( '4 posts in 1 row', SPAI_PLUGIN_NAME );?></option>
                    <option value="3" <?php if( $display_type === 3 ) {echo 'selected';} ?>><?php esc_attr_e( '3 posts in 1 row', SPAI_PLUGIN_NAME );?></option>
                    <option value="4" <?php if( $display_type === 4 ) {echo 'selected';} ?>><?php esc_attr_e( '4 posts in 2 rows', SPAI_PLUGIN_NAME );?></option>
                    <option value="5" <?php if( $display_type === 5 ) {echo 'selected';} ?>><?php esc_attr_e( '3 posts in 2 rows', SPAI_PLUGIN_NAME );?></option>
                </select>
            </div>
        </div>
        <?php
    }
}
