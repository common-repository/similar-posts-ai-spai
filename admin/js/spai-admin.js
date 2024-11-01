(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
        $('.nav-link').click(function() {
            event.preventDefault();
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            var tab = $(this).attr('href');

            $('.tab-pane').removeClass('active');
            $('.tab-pane'+tab).addClass('active');
        });
     });

	$(document).ready(function(){
		var spai_heading_color = $("#spai-heading_color");
		spai_heading_color.spectrum({
			color: spai_heading_color.val(),
			showAlpha: true,
			type: 'text'
		});

		var spai_category_color = $("#spai-category_color");
		spai_category_color.spectrum({
			color: spai_category_color.val(),
			showAlpha: true,
			type: 'text'
		});

		var spai_category_background_color = $("#spai-category_background_color");
		spai_category_background_color.spectrum({
			color: spai_category_background_color.val(),
			showAlpha: true,
			type: 'text'
		});
	});

	$(document).ready(function(){
		$('.template-division select').change(function(){
			var $this = $(this);
			var $value = $('option:selected', $this).val();
			if (
				$value == 3
				|| $value == 7
			) {
				$('.display_type-division').hide();
				$('.show_category-division').hide();
				$('.effect_of_increasing_the_image_size-division').hide();
				$('.category_color-division').hide();
				$('.category_background_color-division').hide();
				$('.showOn-division').show();
			} else {
				$('.display_type-division').show();
				$('.show_category-division').show();
				$('.effect_of_increasing_the_image_size-division').show();
				$('.category_color-division').show();
				$('.category_background_color-division').show();
				$('.showOn-division').hide();
			}
		});
	});

})( jQuery );
