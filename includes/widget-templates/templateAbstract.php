<?php

class templateAbstract
{
    CONST RELATED_POSTS_DEFAULT_HEADING = 'Related posts:';
    CONST RELATED_POSTS_DEFAULT_IMAGE = __SPAI_PUBLIC__ . 'img/default-picture.png';

    protected $titleMaxDefault = 50;
    protected $instance;
    protected $data;
    protected $imp_id;
    protected $post_id;
    protected $hash;
    protected $showOn;

    /**
     * @param WidgetInstance $instance
     * @param $data
     * @param $imp_id
     * @param $post_id
     * @param $hash
     * @param $showOn
     */
    public function __construct(
        $instance,
        $data,
        $imp_id,
        $post_id,
        $hash,
        $showOn = 1
    )
    {
        $this->instance = $instance;
        $this->data = $data;
        $this->imp_id = $imp_id;
        $this->post_id = $post_id;
        $this->hash = $hash;
        $this->showOn = $showOn;
    }

    public function run()
    {
        $show_category = $this->instance->getShowCategory();
        $html = '';

        if( $this->data ) {

            $html .= $this->setHeadingHtml();
            $html .= '<div class="spai-widget-posts" for_post_id="' . $this->post_id . '" imp_id="' . $this->imp_id . '">';
            foreach ($this->data as $item ) {
                if ($item->type === 'ads') {
                    $extIdHtml = '';
                } else {
                    $extIdHtml = ' related_post_id="' . $item->external_id . '"';
                }
                $html .= '<div class="spai-one_related_post" '.$extIdHtml.'>';
                    $html .= '<div class="spai-related_post_img_block">';
                        $html .= $this->setUrlHtml(
                            $item->url,
                            $this->setImgHtml( $item->img ),
                            $item->type
                        );

                        if ($item->type === 'post') {
                            if ($show_category === true) {
                                $html .= $this->setCategoriesHtml($item->categories);
                            }
                        } else {
                            $html .= $this->setAdsNotifierHtml();
                        }
                    $html .= '</div>';

                    $title = $this->setTitleMax( $item->title );
                    $titleUrl= $this->setUrlHtml($item->url, $title, $item->type);
                    $html .= $this->setTitleHtml($titleUrl);

                $html .= '</div>';

            }
            $html .= '</div>';
        }

        return $html;
    }

    protected function setHeadingHtml()
    {
        $heading = $this->instance->getHeading();

        return '<h3 class="spai-widget-header">' . $heading . '</h3>';
    }

    protected function setAdsNotifierHtml()
    {
        $styles = [];
        if ( !empty( $this->instance->getCategoryColor() ) ) {
            $styles[] = 'color: ' . esc_attr( $this->instance->getCategoryColor() );
        }
        if ( !empty( $this->instance->getCategoryBackgroundColor() ) ) {
            $styles[] = 'background-color: ' . esc_attr( $this->instance->getCategoryBackgroundColor() );
            $styles[] = 'border: 1px solid ' . esc_attr( $this->instance->getCategoryBackgroundColor() );
        }

        $html = '<div class="spai-ads-notifier">';
        $html .= '<span class="spai-ads-notifier-block" style="' . implode(';', $styles) . '">Реклама</span>';
        $html .= '</div>';
        return $html;
    }

    protected function setUrlHtml( $url, $urlHtml, $type )
    {
        $html = '';
        $rel = '';

        if ($type === 'ads') {
            $html .= '<noindex>';
            $rel = 'rel="sponsored nofollow"';
        }

        $html .= '<a href="' . esc_url( $url ) . '" '.$rel.'>';
        $html .= $urlHtml;
        $html .= '</a>';

        if ($type === 'ads') {
            $html .= '</noindex>';
        }
        return $html;
    }

    protected function setImgHtml( $img_url )
    {
        $img_url = $this->setImgUrl( $img_url );

        $img_additional_classes = [];
        if ( $this->instance->getEffectOfIncreasingTheImageSize() === true ) {
            $img_additional_classes[] = 'increase';
        }

        return '<div class="spai-related_post_img ' . implode(' ', $img_additional_classes) . '" style="background-image: url(' . $img_url . ')"></div>';
    }

    protected function setImgUrl( $img_url )
    {
        return $img_url ?
            esc_url( $img_url ) :
            (
            $this->instance->getDefaultImage()  ?
                esc_url( $this->instance->getDefaultImage() ) :
                self::RELATED_POSTS_DEFAULT_IMAGE
            );
    }

    protected function setTitleHtml( $title )
    {
        $styles = [];
        if (!empty($this->instance->getHeadingColor())) {
            $styles[] = 'color: ' . esc_attr( $this->instance->getHeadingColor() );
        }

        return '<div class="spai-related_post_title" style="' . implode(';', $styles) . '">' . $title . '</div>';
    }

    protected function setTitleMax( $title )
    {
        $title_max = $this->instance->getTitleMax() !== null ? (int)$this->instance->getTitleMax() : $this->titleMaxDefault;

        if (extension_loaded('mbstring')) {
            $title_to_show = ( mb_strlen( $title, 'UTF-8' ) > $title_max ) ?
                mb_strimwidth( $title, 0, $title_max, "...", 'UTF-8' ) :
                $title;
        } else {
            $title_to_show = ( strlen( $title ) > $title_max ) ?
                substr( $title, 0, $title_max) :
                $title;
        }
        return esc_html( $title_to_show );
    }

    protected function setCategoriesHtml($categories) {
        $html = '';

        if(count($categories) > 0) {
            $styles = $this->getCategoryStyles();

            $html .= '<div class="spai-categories">';
            $category_links_html = [];
            foreach ($categories as $category) {
                $category_links_html[] = $this->setOneCategoryHtml($category, $styles);
            }
            $html .= implode(' ', $category_links_html);
            $html .= '</div>';
        }
        return $html;
    }

    protected function setOneCategoryHtml($category, $styles) {
        $category_link = get_category_link( $category->external_id ) ?: '';
        return '<a class="spai-one_category" style="' . implode(';', $styles) . '" href="' . esc_url( $category_link ) . '">'
                    . $category->name
                . '</a>';
    }

    protected function getCategoryStyles()
    {
        $styles = [];
        if (!empty($this->instance->getHeadingColor())) {
            $styles[] = 'color: ' . esc_attr( $this->instance->getHeadingColor() );
        }
        if (!empty($this->instance->getCategoryBackgroundColor())) {
            $styles[] = 'background-color: ' . esc_attr( $this->instance->getCategoryBackgroundColor() );
            $styles[] = 'border: 1px solid ' . esc_attr( $this->instance->getCategoryBackgroundColor() );
        }
        return $styles;
    }
}
