<?php
require_once plugin_dir_path( __FILE__ ) . 'templateAbstract.php';


/**
 * Stories
 */
class template3 extends TemplateAbstract
{
    public function __construct($instance, $data, $imp_id, $post_id, $hash, $showOn = 1)
    {
        parent::__construct($instance, $data, $imp_id, $post_id, $hash, $showOn);

        $this->prepareHistoryWindow();
    }

    protected $titleMaxDefault = 10;

    protected function setCategoriesHtml($categories) {
        $html = '';

        if(count($categories) > 0) {
            $styles = $this->getCategoryStyles();

            $html .= '<div class="spai-hidden"><div class="spai-categories">';
                $category_links_html = [];
                foreach ($categories as $category) {
                    $category_links_html[] = $this->setOneCategoryHtml($category, $styles);
                }
                $html .= implode(' ', $category_links_html);
            $html .= '</div></div>';
        }
        return $html;
    }

    protected function setAdsNotifierHtml()
    {
        return '';
    }

    protected function setHeadingHtml()
    {
        return '';
    }

    protected function setTitleHtml( $title )
    {
        $styles = [];
        if (!empty($this->instance->getHeadingColor())) {
            $styles[] = 'color: ' . esc_attr( $this->instance->getHeadingColor() );
        }

        return '<div class="spai-related_post_title spai-hidden" style="' . implode(';', $styles) . '">' . $title . '</div>';
    }

    public function run()
    {
        if (!$this->showOnValidate()) {
            return '';
        }

        $html = '';

        if( $this->data ) {

            $html .= $this->setHeadingHtml();
            $html .= '<div class="spai-widget-posts" for_post_id="' . $this->post_id . '" imp_id="' . $this->imp_id . '">';
            foreach ($this->data as $item ) {
                if ($item->type === 'ads') {
                    $extIdHtml = ' ads_id="' . $item->external_id . '"';
                } else {
                    $extIdHtml = ' related_post_id="' . $item->external_id . '"';
                }
                $html .= '<div class="spai-one_related_post" '.$extIdHtml.'>';
                    $html .= '<div class="spai-one_related_post_gradient">';
                        $html .= $this->setImgHtml( $item->img );

                        $title = $this->setTitleMax( $item->title );
                        $titleUrl= $this->setUrlHtml($item->url, $title, $item->type);
                        $html .= $this->setTitleHtml($titleUrl);

                        if ($item->type === 'post') {
                                $html .= $this->setCategoriesHtml($item->categories);
                        } else {
                            $html .= $this->setAdsNotifierHtml();
                        }

                    $html .= '</div>';
                $html .= '</div>';

            }
            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    private function showOnValidate()
    {
        require_once plugin_dir_path( __FILE__ ) . '../libs/Mobile_Detect.php';
        $detect = new Mobile_Detect;

        //если разрешено показывать только на телефонах, а пользователь зашел не через телефон
        if ($this->showOn === 2 && !$detect->isMobile() ) {
            return false;
        }
        //если разрешено показывать только на пк, а пользователь зашел не через пк
        if ($this->showOn === 3 && $detect->isMobile() ) {
            return false;
        }

        return true;
    }

    private function prepareHistoryWindow()
    {
        ?>
        <div class="spai-history-window spai-hidden" id="spai-history-window-<?=$this->hash ?>">
            <div class="spai-history-window-blackout"></div>
            <div class="spai-history-window-content-block-prev">
                <div class="spai-history-window-content-prev">
                    <div class="spai-history-window-image-block">
                        <div class="spai-history-window-image"></div>
                    </div>
                </div>
            </div>

            <div class="spai-history-window-content-block-current">
                <div class="spai-history-window-content">
                    <div class="spai-history-window-top-slider">
                        <div class="spai-history-window-top-slider-runner"></div>
                    </div>
                    <div class="spai-history-window-close-button"></div>
                    <div class="spai-history-window-categories"></div>
                    <div class="spai-history-window-image-block">
                        <div class="spai-history-window-image"></div>
                    </div>
                    <div class="spai-history-window-title-block">
                        <div class="spai-history-window-title"></div>
                    </div>
                    <div class="spai-history-window-prev"></div>
                    <div class="spai-history-window-next"></div>
                </div>
            </div>

            <div class="spai-history-window-content-block-next">
                <div class="spai-history-window-content-next">
                    <div class="spai-history-window-image-block">
                        <div class="spai-history-window-image"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
