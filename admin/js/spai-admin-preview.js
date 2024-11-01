(function( $ ) {
  'use strict';
  $(document).ready(function(){

    $('#spai-heading').keyup(function(){
      var $this = $(this);
      var $value = $($this).val();
      $('.spai-widget-header').text($value);
    });

    $("#spai-heading_color").on( "change.spectrum move.spectrum", function(){
      var $this = $(this);
      var $value = $($this).val();
      $('.spai-widget-header').css('color', $value);
    });

    $("#spai-category_color").on( "change.spectrum move.spectrum", function(){
      var $this = $(this);
      var $value = $($this).val();
      $('.spai-one_category').css('color', $value);
    });

    $("#spai-category_background_color").on( "change.spectrum move.spectrum", function(){
      var $this = $(this);
      var $value = $($this).val();
      $('.spai-one_category').css('background-color', $value);
    });

    $("#spai-show_category").on( "change", function(){
      var $this = $(this);
      var $value = ($($this).is(':checked') === true) ? 'block' : 'none';
      $('.spai-categories').css('display', $value);
      //console.log($value);
    });
  });
})( jQuery );
