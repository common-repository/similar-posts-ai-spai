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
        spaiLoadRelatedPosts();
    });

    function spaiLoadRelatedPosts() {
        var startTime = new Date().getTime();
        var $spaiWidgetContent = $('.spai-widget_content');
        $spaiWidgetContent.each(function() {
            var $this = $( this );
            var $spaiWidgetContentId = $this.attr('id');
            var $instance = eval("spai_instance_" + $spaiWidgetContentId);
            var $postId = eval("spai_post_id");

            var data = new FormData();
            data.append('action', 'spai_get_related_posts');

            Object.keys($instance).forEach(function(key) {
                data.append('instance['+ key +']', $instance[key]);

            });

            if ($postId !== undefined) {
                data.append('postId', $postId);
            }

            $.ajax({
                url: spai_ajaxurl,
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    var endTime = new Date().getTime();
                    var getPostsTime = (endTime - startTime) / 1000;
                    console.log('Get related posts time = '+ getPostsTime + 's');

                    $this.append(response);
                    spaiSendImpIsLoaded($this, $postId);
                },
                error: function (response) {
                    console.log('error');
                },
            }).done(function(response){
            });
        });
    }

    function spaiSendImpIsLoaded($spaiWidgetContent, $postId) {
        var $spaiWidgetPosts = $spaiWidgetContent.children('.spai-widget-posts');
        var $spaiImpId = $spaiWidgetPosts.attr('imp_id');

        var data = new FormData();
        data.append('action', 'spai_send_imp_is_loaded');

        if ($postId !== undefined) {
            data.append('postId', $postId);
            data.append('impId', $spaiImpId);
        }

        $.ajax({
            url: spai_ajaxurl,
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                console.info('success');
            },
            error: function (response) {
                console.log('error');
            },
        });
    }
})(jQuery);
