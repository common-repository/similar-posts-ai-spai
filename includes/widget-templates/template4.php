<?php
require_once plugin_dir_path( __FILE__ ) . 'templateAbstract.php';

class template4 extends TemplateAbstract
{
    protected function setHeadingHtml()
    {
        $heading = $this->instance->getHeading() !== null ? esc_attr( $this->instance->getHeading() ) : self::RELATED_POSTS_DEFAULT_HEADING;

        return '<div class="spai-widget-header_block"><h3 class="spai-widget-header">' . $heading . '</h3></div>';
    }
}
