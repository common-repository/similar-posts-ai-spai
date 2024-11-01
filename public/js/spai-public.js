(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(function() {
        $('body').on('click', '.spai-one_related_post a', function () {
            let $this = $(this); //.spai-one_related_post a

            //var spai_ajaxurl defined in class-spai-public.php

            var spai_one_related_post = $this.closest('.spai-one_related_post');
            var spai_one_related_posts = spai_one_related_post.parent();
            var spai_post_id = spai_one_related_posts.attr('for_post_id');
            var spai_imp_id = spai_one_related_posts.attr('imp_id');
            var spai_clicked_related_post_id = spai_one_related_post.attr('related_post_id');

            event.preventDefault();

            if (spai_clicked_related_post_id === undefined) {
                location.href = $this.attr('href');
                return;
            }

            var data = new FormData();
            data.append('action', 'spai_save_related_post_click');
            data.append('spai_post_id', spai_post_id);
            data.append('spai_imp_id', spai_imp_id);
            data.append('spai_clicked_related_post_id', spai_clicked_related_post_id);
            $.ajax({
                url: spai_ajaxurl,
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {
                    console.log(response);
                },
            }).done(function(){
                location.href = $this.attr('href');
            });
        });

        $('body').on('click', '.spai-history-window-blackout', function () {
            var $this = $(this);
            clearInterval(window.spaiIntervalId);
            $this.parent('.spai-history-window').addClass('spai-hidden');
            $('body').removeClass('overflow-hidden');
            $('.spai-one_related_post').removeClass('historyActive');
        });

        $('body').on('vmousedown', '.spai-history-window-content', function () {
            clearInterval(window.spaiIntervalId);
        }).on('vmouseup', '.spai-history-window-content', function () {
            spaiTimerStart();
        });

        $('body').on('click', '.spai-history-window-close-button', function () {
            event.stopPropagation();
            $('.spai-history-window-blackout').click();
        })

        $('body').on('contextmenu', '.spai-history-window-image',function(){return false;});

        $('body').on('click', '.spai-tmpl3 .spai-one_related_post', function () {
            var $this = $(this); //.spai-one_related_post
            var spai_related_post_id = $this.attr('related_post_id');
            var $spai_widget_content = $this.closest('.spai-widget_content');
            var spai_data_hash = $spai_widget_content.attr('id');
            var spai_ads_id = $this.attr('ads_id');

            $('body').addClass('overflow-hidden');

            spaiSetPrevHistory($this.prev(), $spai_widget_content);
            spaiSetCurrentHistory($this, $spai_widget_content);
            spaiSetNextHistory($this.next(), $spai_widget_content);
            console.log(spai_data_hash);

            var $spaiHistoryWindow = $('.spai-history-window', $spai_widget_content);
            $spaiHistoryWindow.removeClass('spai-hidden');

            $this.addClass('historyActive');

            window.spaiSliderProgressCounter = 0;
            spaiTimerStart();
        });

        $('body').on('click', '.spai-history-window-next', function () {
            var $this = $(this);
            var spai_data_hash = $this.closest('.spai-history-window').attr('id').replace('spai-history-window-','');
            var $currentItem = $('.spai-one_related_post.historyActive');
            var $nextItem = $currentItem.next();
            if ($nextItem.length !== 0) {
                $('.spai-history-window-blackout').click();
                $currentItem.removeClass('historyActive');
                $nextItem.click();
            } else {
                $('.spai-history-window-blackout').click();
            }
        });

        $('body').on('click', '.spai-history-window-prev', function () {
            var $currentItem = $('.spai-one_related_post.historyActive');
            var $prevItem = $currentItem.prev();
            if ($prevItem.length !== 0) {
                $('.spai-history-window-blackout').click();
                $currentItem.removeClass('historyActive');
                $prevItem.click();
            } else {
                $('.spai-history-window-blackout').click();
            }
        });

        $('body').on('click', '.spai-history-window-content-block-next', function () {
            var $this = $(this);
            var spai_data_hash = $this.closest('.spai-history-window').attr('id').replace('spai-history-window-','');
            $('#spai-history-window-'+spai_data_hash+' .spai-history-window-next').click();
        });

        $('body').on('click', '.spai-history-window-content-block-prev', function () {
            var $this = $(this);
            var spai_data_hash = $this.closest('.spai-history-window').attr('id').replace('spai-history-window-','');
            $('#spai-history-window-'+spai_data_hash+' .spai-history-window-prev').click();
        });
    });

    function spaiSetPrevHistory($this, $spai_widget_content)
    {
        if ($this.length !== 0) {
            $('.spai-history-window-content-block-prev', $spai_widget_content).removeClass('spai-hidden');
            var $spaiHistoryImageDiv = $('.spai-related_post_img', $this);
            var $spaiHistoryWindowImageDiv = $('.spai-history-window-content-prev .spai-history-window-image');
            var $spaiBackgroundImage = $spaiHistoryImageDiv.css('background-image');
            $spaiHistoryWindowImageDiv.css('background-image', $spaiBackgroundImage);
        } else {
            $('.spai-history-window-content-block-prev', $spai_widget_content).addClass('spai-hidden');
        }

    }

    function spaiSetCurrentHistory($this, $spai_widget_content)
    {
        var $spaiHistoryImageDiv = $('.spai-related_post_img', $this);
        var $spaiHistoryWindowImageDiv = $('.spai-history-window-content .spai-history-window-image', $spai_widget_content);
        var $spaiBackgroundImage = $spaiHistoryImageDiv.css('background-image');
        $spaiHistoryWindowImageDiv.css('background-image', $spaiBackgroundImage);

        var $spaiHistoryWindowTitleDiv = $('.spai-history-window-content .spai-history-window-title', $spai_widget_content);
        var $spaiHistoryTitleUrl = $('.spai-related_post_title a', $this)
            .clone(); //без clone почему-то удаляется родительский DOM
        $spaiHistoryWindowTitleDiv.html($spaiHistoryTitleUrl);

        var $spaiHistoryWindowCategoriesDiv = $('.spai-history-window-content .spai-history-window-categories', $spai_widget_content);
        var $spaiHistoryCategories = $('.spai-categories', $this)
            .clone(); //без clone почему-то удаляется родительский DOM
        $spaiHistoryWindowCategoriesDiv.html($spaiHistoryCategories);
    }

    function spaiSetNextHistory($this, $spai_widget_content)
    {
        if ($this.length !== 0) {
            $('.spai-history-window-content-block-next', $spai_widget_content).removeClass('spai-hidden');
            var $spaiHistoryImageDiv = $('.spai-related_post_img', $this);
            var $spaiHistoryWindowImageDiv = $('.spai-history-window-content-next .spai-history-window-image', $spai_widget_content);
            var $spaiBackgroundImage = $spaiHistoryImageDiv.css('background-image');
            $spaiHistoryWindowImageDiv.css('background-image', $spaiBackgroundImage);
        } else {
            $(' .spai-history-window-content-block-next', $spai_widget_content).addClass('spai-hidden');
        }
    }

    function spaiTimerStart()
    {
        var spaiIntervalId = null;
        window.spaiSliderProgressCounter = window.spaiSliderProgressCounter ? window.spaiSliderProgressCounter : 0;

        var spaiProgressSlider = function () {
            if (window.spaiSliderProgressCounter > 99) {
                clearInterval(spaiIntervalId);
                //console.log('clearInterval');
                $('.spai-history-window-top-slider-runner').css('width', window.spaiSliderProgressCounter + '%');

                $('.spai-history-window-next').click();
            } else {
                //console.log(window.spaiSliderProgressCounter);
                $('.spai-history-window-top-slider-runner').css('width', window.spaiSliderProgressCounter + '%');
                window.spaiSliderProgressCounter++;
            }
        }

        window.spaiIntervalId = setInterval(spaiProgressSlider, 50);
    }

})(jQuery);
