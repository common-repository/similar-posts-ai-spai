<?php
require_once plugin_dir_path( __FILE__ ) . 'templateAbstract.php';

class template7 extends TemplateAbstract
{
    public function run()
    {
        $show_category = $this->instance->getShowCategory();
        $html = '';

        if( $this->data ) {

            $html .= $this->setHeadingHtml();
            $html .= '<ul class="spai-widget-posts" for_post_id="' . $this->post_id . '" imp_id="' . $this->imp_id . '">';
            foreach ($this->data as $item ) {
                if ($item->type === 'ads') {
                    $extIdHtml = '';
                } else {
                    $extIdHtml = ' related_post_id="' . $item->external_id . '"';
                }
                $html .= '<li class="spai-one_related_post" '.$extIdHtml.'>';

                $title = $this->setTitleMax( $item->title );
                $titleUrl= $this->setUrlHtml($item->url, $title, $item->type);
                $html .= $this->setTitleHtml($titleUrl);

                $html .= '</li>';

            }
            $html .= '</ul>';
        }

        return $html;
    }
}
