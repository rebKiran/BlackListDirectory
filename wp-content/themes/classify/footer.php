<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage classify
 * @since classify 1.0
 */


?>

</script>
	<footer>

		<div class="container">
					
			<div class="full">
				
				<?php get_sidebar( 'footer-one' ); ?>

			</div>

			

		</div>
					
	</footer>

	<section class="socket">

		<div class="container">

			<div class="site-info">
				<?php 

					global $redux_demo; 
					$footer_copyright = $redux_demo['footer_copyright'];

				?>

				<?php if(!empty($footer_copyright)) { 
						echo $footer_copyright;
					} else {
				?>
				 &#x00040; 2014 Classify - by <a class="target-blank" href="http://themeforest.net/user/joinwebs">Joinwebs</a>
				<?php } ?>
				
			</div><!-- .site-info -->
			
			<div class="backtop">
				<a href="#backtop"><i class="fa fa-angle-up"></i></a>
			</div>

		</div>

	</section>
	<?php wp_footer(); ?>
</div>
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog" style="display:none;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Rate & Review</h4>
            <span id="review_msg" style="text-align: center;color: #6dab3c;margin-left:65px;"></span>
        </div>
        <div class="modal-body" style="width: 69%;overflow:hidden">
          <p><?php echo do_shortcode('[RICH_REVIEWS_FORM]'); ?>.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</body>
<script type="text/javascript">
jQuery( document ).ready(function() {
    jQuery('.textwidget #contact_us_btn').click(function() {

          var name = jQuery(".textwidget #contactName").val();
          name = name.trim();

	  var email = jQuery(".textwidget #email").val();
	  email = email.trim();
				
	  var comments = jQuery(".textwidget #commentsText").val();
	  comments = comments.trim();
				
	 jQuery(".textwidget .error_msg").empty();	
         jQuery(".textwidget #msg").empty();
				
	  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			
          var valid = true;
			 
	 if( '' == name) {
		jQuery(".textwidget #contactName").parent('div').find('div').html("<font size='2' color='#ff672a;'>Name is required.</font>");
	        jQuery(".textwidget #contactName").focus();
		valid = false;
	  } 
	  if( '' == email) {
		jQuery(".textwidget #email").parent('div').find('div').html("<font size='2' color='#ff672a;'>Email is required.</font>");
	        jQuery(".textwidget #email").focus();
		valid = false;
	  } 
			
	  if( '' != email ) {
		if( false == re.test(email)) {
		     jQuery(".textwidget #email").parent('div').find('div').html("<font size='2' color='#ff672a;'>Please enter valid email.</font>");
		     jQuery(".textwidget #email").focus();
		     valid = false;
					 
		 } 
	  } 
			 
	 if( '' == comments) {
		jQuery(".textwidget #commentsText").parent('div').find('div').html("<font size='2' color='#ff672a;'>Description is required.</font>");
			   
		valid = false;
	 } 
				
	 if( false == valid ) {
		return false;
			 
         } else {
				 
		jQuery.ajax({
				type: "POST",
				url: "http://blacklistdir.rebelute.in/wp-admin/admin-ajax.php",
				data: "action=send_contact&name="+name+"&email="+email+"&comments="+comments,
				success: function( obj ){

				      jQuery(".textwidget #msg").html(obj);	
                                      jQuery(".textwidget #contactName").val('');	
                                      jQuery(".textwidget #email").val('');	
                                      jQuery(".textwidget #commentsText").val('');	
				}
			});
				
			
		}
	
    });
});
jQuery(document).ready(function($) {

	$("#edit-ad-category").change(function(e) { 
		$("#select-category").val("");
	});
	$("#select-category").change(function(e) { 
		$("#edit-ad-category").val("");
	});
	
	/*$(".rs-advanced-search-form .search-submit-input").click(function(e) { 
		 $('#search_result').empty();
		 var quick_search = $('#edit-ad-category').val();
		 quick_search = quick_search.trim();
		 var advanced_search = '';
		 if( '' == quick_search ) {
			  advanced_search = $("#select-category").val();
		 }
		 var s = $("input[name='s']").val();
		 $('#search_result').html('');
		 $.ajax({
			type: "POST",
			url: "http://blacklistdir.rebelute.in/wp-admin/admin-ajax.php",
			data: "action=search_category&quick_search="+quick_search+"&advanced_search="+advanced_search+"&s="+s,
			success: function( obj ){

				$('#search_result').empty();
				$('#search_result').html('');
				$('#search_result').html(obj);
				
				
			}
		});
	});	*/					
});

</script>

<script>
jQuery(document).ready(function(){
        jQuery(".hidden-div").hide();

    jQuery(".show-div").click(function(){
        jQuery(".hidden-div").show();
    });
});
</script>
</html>