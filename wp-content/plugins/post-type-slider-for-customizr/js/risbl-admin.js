jQuery(document).ready(function($) {


  // Expand/close fields
  if($('.risbl-metabox').length > 0){

    var $switchBtn  = $('#risbl_prefix_set_as_slider');
    var $fields     = $('.risbl-metabox-slider-fields');

    $($switchBtn).change(function() {

      if( $switchBtn.is(':checked') ){
        $fields.slideDown();
      }else{
        $fields.slideUp();
      }

    });


  }

  // Enable color picker
  if($('#risbl_prefix_color_id').length > 0){
    $('#risbl_prefix_color_id').wpColorPicker();
  }


  // Slider ordering
  if($('#risbl_prefix_order_slider').length > 0){

    jQuery( '#risbl_prefix_order_slider tbody' ).sortable({
      items: 'tr',
  		cursor: 'move',
  		axis: 'y',
  		handle: 'td.sort',
  		scrollSensitivity: 40,
  		forcePlaceholderSize: true,
  		helper: 'clone',
  		opacity: 0.65
  	});

  }

  // Hide fields by default after page loaded
  if($('.risbl-metabox-slider-fields').length > 0){

    $(window).load(function(){
      $('.risbl-metabox-slider-fields').addClass('hide-fields');
    });

  }


});
