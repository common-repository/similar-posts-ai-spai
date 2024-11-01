<?php
require_once plugin_dir_path( __FILE__ ) . 'class-spai-logger.php';
if ( ! class_exists( 'Spai_Widget' ) ) {

    class Spai_Widget extends WP_Widget
    {
        CONST RELATED_POSTS_DEFAULT_HEADING = 'Related posts:';
        CONST RELATED_POSTS_DEFAULT_IMAGE = __SPAI_PUBLIC__ . 'img/default-picture.png';
        private $post_id;
        private $spai_options;
        private $token;
        private $count_posts = 0;
        private $max_ads_count = 0;
        private $asWidget; // 1 - на сайт добавляется как "виджет". 0 - автодобавление после контента
        private $hash = '';

        public $content = null;

        public function __construct($asWidget = 1) {
            $this->asWidget = $asWidget;
            parent::__construct(
                'spai_widget',
                __( 'Spai - related posts', 'spai' ),
                array(
                    'customize_selective_refresh' => false,
                )
            );

            $this->post_id = $this->getPostId();
            $this->spai_options = get_option( 'spai' );
            $this->token = isset($this->spai_options['api_key']) ? $this->spai_options['api_key'] : null;

            $this->setCountPosts();
            $this->setMaxAdsCount();
            $this->setHash();
        }

        // Сохранение настроек виджета (очистка)
        public function update( $new_instance, $old_instance) {
            $instance = array();

            $instance['display_type'] = ( !empty( $new_instance['display_type'] ) ) ? (int)$new_instance['display_type'] : 3;
            if( $instance['display_type'] > 5 || $instance['display_type'] < 1 ) { $instance['display_type'] = 3; }

            $instance['template'] = ( !empty( $new_instance['template'] ) ) ? (int)$new_instance['template'] : 2;
            if( $instance['template'] > 5 || $instance['template'] < 1 ) { $instance['display_type'] = 2; }

            $instance['showOn'] = ( !empty( $new_instance['showOn'] ) ) ? (int)$new_instance['showOn'] : 2;
            if( $instance['showOn'] > 3 || $instance['showOn'] < 1 ) { $instance['showOn'] = 1; }

            return $instance;
        }

        /**
         * Вывод виджета
         * @param $args
         * @param $instance
         * @return string|void
         */
        public function widget( $args = [], $instance = []) {
            $prefix = '';
            $suffix = '';
            if (!empty($this->token)) {
                if ($this->asWidget !== 1) {
                    $instance = $this->getSpaiInstance();
                }
                $this->injectInstanceJs($instance);
                $prefix = $this->widgetPrefix($instance);
                $suffix = $this->widgetSuffix();
            }

            $widgetContent = implode([$prefix, $suffix]);

            if ($this->asWidget === 1) {
                echo $widgetContent;
            } else {
                $this->content = $widgetContent;
                return $this->content;
            }
        }

        // html форма настроек виджета в Админ-панели
        function form( $instance ) {
            if ($this->asWidget !== 1) {
                $instance = $this->getSpaiInstance();
            }
            require_once plugin_dir_path( __FILE__ ) . 'class-spai-widget-form.php';

            $form = new Spai_Widget_Form($instance);

            echo $form->show();
        }

        /**
         * @return array|false
         */
        private function getRelatedData()
        {
            if (empty($this->token)) {
                return false;
            }
            $api = new Spai_Api( $this->token );

            $answer = $api->getRelatedData( $this->post_id, $this->count_posts );
            //echo '<pre>';var_dump($answer);

            if( is_wp_error( $answer ) ) {
                return false;
            }
            if (!isset($answer['response']['code']) or $answer['response']['code'] !== 200) {
                return false;
            }

            $json =  json_decode($answer['body']);
            if(!empty($json->result) and $json->result === true ) {
                return [
                    'posts' => $json->data->posts,
                    'ads' => $json->data->ads,
                    'impId' => $json->data->impId
                ];
            }

            return false;
        }

        private function widgetAsHtml($data, $imp_id, $instance )
        {
            require_once 'class-spai-widget-instance.php';
            $instanceObj = new WidgetInstance($instance);

            $template = $instanceObj->getTemplate();

            require_once plugin_dir_path( __FILE__ ) . 'widget-templates/template' . $template .'.php';
            $templateName = 'template' . $template;
            /** @var templateAbstract $template */
            $templateClass = new $templateName(
                $instanceObj,
                $data,
                $imp_id,
                $this->post_id,
                $this->hash,
                $instanceObj->getShowOn()
            );
            return $templateClass->run();
        }

        /**
         * @return string|null
         */
        private function getIp()
        {
            $ip = null;
            if (isset( $_SERVER['HTTP_CLIENT_IP'] ) and !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) and  !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset( $_SERVER['REMOTE_ADDR'] ) and  !empty( $_SERVER['REMOTE_ADDR']) ) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            return $ip;
        }

        /**
         * @param $relatedPosts
         * @param $ads
         * @return array
         */
        private function prepareDataToWidget($relatedPosts, $ads)
        {
            $result = [];
            $adsCount = count($ads);
            $countAllElements = $adsCount + count($relatedPosts);

            if ($countAllElements > $this->count_posts) {
                $iMax = $this->count_posts;
            } else {
                $iMax = $countAllElements;
            }

            for ($i = 1; $i <= $iMax; $i++) {
                if ($i === 2 and $adsCount > 0 and !empty(array_values($ads)[0])) {
                    $adsCurrent = array_values($ads)[0];
                    $currentElement = [
                        'type' => 'ads',
                        'external_id' => $adsCurrent->id,
                        'url' => $adsCurrent->link,
                        'img' => $adsCurrent->image,
                        'title' => $adsCurrent->title
                    ];
                } elseif ($i === 4 and $adsCount > 0 and !empty(array_values($ads)[1])) {
                    $adsCurrent = array_values($ads)[1];
                    $currentElement = [
                        'type' => 'ads',
                        'external_id' => $adsCurrent->id,
                        'url' => $adsCurrent->link,
                        'img' => $adsCurrent->image,
                        'title' => $adsCurrent->title
                    ];
                } else {
                    $postCurrent = array_shift($relatedPosts);
                    $currentElement = [
                        'type' => 'post',
                        'url' => $postCurrent->post_url,
                        'img' => $postCurrent->img_url,
                        'title' => $postCurrent->title,
                        'external_id' => $postCurrent->external_id,
                        'categories' =>  $postCurrent->categories
                    ];
                }
                $result[] = (object)$currentElement;
            }
            //echo '<pre>';var_dump($result);echo '</pre>';
            return $result;
        }

        private function setCountPosts()
        {
            $template = isset($this->spai_options['template']) ? (int)$this->spai_options['template'] : 1;
            $display_type = isset($this->spai_options['display_type']) ? (int)$this->spai_options['display_type'] : 3;
            if ($template === 3) {
                $this->count_posts = 12;
            } elseif (in_array($display_type, array(1, 5), true)) {
                $this->count_posts = 6;
            } elseif ($display_type === 2) {
                $this->count_posts = 4;
            } elseif ($display_type === 3) {
                $this->count_posts = 3;
            } elseif ($display_type === 4) {
                $this->count_posts = 8;
            } else {
                $this->count_posts = 6;
            }
        }

        private function setMaxAdsCount()
        {
            $display_type = isset($this->spai_options['display_type']) ? (int)$this->spai_options['display_type'] : 3;

            if (in_array($display_type, array(1, 5), true)) {
                $this->max_ads_count = 2;
            } elseif ($display_type === 2) {
                $this->max_ads_count = 1;
            } elseif ($display_type === 3) {
                $this->max_ads_count = 1;
            } elseif ($display_type === 4) {
                $this->max_ads_count = 3;
            } else {
                $this->max_ads_count = 2;
            }
        }

        private function setAdsNotifierHtml()
        {
            $html = '<div class="spai-ads-notifier">';
                $html .= '<a class="spai-ads-notifier-block">Реклама</a>';
            $html .= '</div>';
            return $html;
        }

        private function setHash()
        {
            $length = 10;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
            }

            $this->hash = $randomString;
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

            $id = 'id="' . $this->hash . '"';

            return '<div class="spai-widget_content '.implode(' ', $main_additional_classes).'" ' . $id . '>';
        }

        private function widgetSuffix()
        {
            return '</div>';
        }

        /**
         * @param $relatedData
         * @return array
         */
        private function getRelatedAds($relatedData)
        {
            $relatedAds = !empty($relatedData['ads']) ? $relatedData['ads'] : [];
            if (count($relatedAds) > 0) {
                $relatedAds = array_slice($relatedAds, 0, $this->max_ads_count); //ограничиваем массив макс допустимым количеством рекламы
            }

            return $relatedAds;
        }

        /**
         * @param $relatedData
         * @return array
         */
        private function getRelatedPosts($relatedData)
        {
            return !empty($relatedData['posts']) ? $relatedData['posts'] : [];
        }

        /**
         * @param $send_impression
         * @return int|null
         */
        private function getImpId($send_impression)
        {
            $imp_id = null;
            if( !empty( $send_impression ) and $send_impression->result === true) {
                $imp_id = (int) $send_impression->data->imp_id;
            }

            return $imp_id;
        }

        public function getContent($instance)
        {
            $related_posts_as_html = '';

            $relatedData = $this->getRelatedData();
            $relatedPosts = $this->getRelatedPosts($relatedData);
            $relatedAds = $this->getRelatedAds($relatedData);

            if( $relatedData !== false and !empty( $relatedPosts ) ) {
                //$send_impression = $this->sendImpression( $relatedPosts );
                $data = $this->prepareDataToWidget( $relatedPosts, $relatedAds );
                $related_posts_as_html = $this->widgetAsHtml( $data, $relatedData['impId'], $instance );
            }

            return $related_posts_as_html;
        }

        /**
         * @return false|int|null
         */
        public function getPostId()
        {
            $id = get_the_ID();

            if ($id === false and $this->asWidget === 1) {
                global $post;
                $id = isset($post) ? $post->ID : null;
            }

            return $id;
        }

        /**
         * @param int $postId
         * @return void
         */
        public function setPostId($postId)
        {
            $this->post_id = $postId;
        }

        private function injectInstanceJs($instance)
        {
            $script  = 'var spai_instance_' . $this->hash . ' = ' . wp_json_encode( $instance );

            wp_enqueue_script( SPAI_PLUGIN_NAME . '-load-related-posts', SPAI_PLUGIN_URL . 'public/js/spai-load-related-posts.js', array( 'jquery' ), SPAI_VERSION, true );
            wp_add_inline_script(SPAI_PLUGIN_NAME . '-load-related-posts', $script, 'before');
        }


        public function injectPostIdJs($id)
        {
            $script  = 'var spai_post_id = ' . $id ;

            wp_enqueue_script( SPAI_PLUGIN_NAME . '-load-related-posts', SPAI_PLUGIN_URL . 'public/js/spai-load-related-posts.js', array( 'jquery' ), SPAI_VERSION, true );
            wp_add_inline_script(SPAI_PLUGIN_NAME . '-load-related-posts', $script, 'before');
        }

        /**
         * @return array
         */
        public function getSpaiInstance()
        {
            $spaiInstance = $this->spai_options;
            if (empty($spaiInstance)) { return [];}
            if (isset($spaiInstance['api_key'])) {
                unset($spaiInstance['api_key']);
            }
            return $spaiInstance;
        }
    }

    function spai_register_widget()
    {
        register_widget( Spai_Widget::class );
    }
}
