<?php
class Spai_Widget_Preview {
    private $spai_options;

    private $spai_posts_fixtures;

    public function __construct() {
        $this->spai_options = get_option( 'spai' );
        $this->spai_posts_fixtures = require plugin_dir_path( __FILE__ ) . 'fixtures/class-spai-related_posts-fixtures.php';
    }

    public function getSpaiInstance()
    {
        $spaiInstance = $this->spai_options;
        if (empty($spaiInstance)) { return [];}
        if (isset($spaiInstance['api_key'])) {
            unset($spaiInstance['api_key']);
        }
        return $spaiInstance;
    }

    public function run ()
    {
        $instance = $this->getSpaiInstance();

        require_once 'class-spai-widget-instance.php';
        $instanceObj = new WidgetInstance($instance);

        $template = $instanceObj->getTemplate();

        $prefix = $this->widgetPrefix($instance);
        $suffix = $this->widgetSuffix();

        require_once plugin_dir_path( __FILE__ ) . 'widget-templates/template' . $template .'.php';
        $templateName = 'template' . $template;
        /** @var templateAbstract $template */
        $templateClass = new $templateName(
            $instanceObj,
            $this->spai_posts_fixtures,
            0,
            0,
            'test-hash',
            $instanceObj->getShowOn()
        );
        return implode([$prefix, $templateClass->run(), $suffix]);
    }

    private function widgetPrefix($instance)
    {
        require_once 'class-spai-widget-instance.php';
        $instanceObj = new WidgetInstance($instance);
        $display_type = $instanceObj->getDisplayType();

        $template = $instanceObj->getTemplate();
        $show_category = $instanceObj->getShowCategory();

        $main_additional_classes = [];
        $main_additional_classes[] = 'spai-tmpl' . $template;
        if (!in_array($template, [3, 7]) && $show_category === true) {
            $main_additional_classes[] = 'with_cat';
        }

        if (!in_array($template, [3, 7])) {
            $main_additional_classes[] = 'spai-dt' . $display_type;
        }

        $id = 'id="hash-0"';

        return '<div class="spai-widget_content '.implode(' ', $main_additional_classes).'" ' . $id . '>';
    }

    private function widgetSuffix()
    {
        return '</div>';
    }
}
