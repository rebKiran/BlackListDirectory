<?php
session_start();
/*
WC()->cart->empty_cart();
print_r(WC()->cart);*/

/**
 * Template Name: Submission
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<?php 	
global $wpdb, $woocommerce;
  unset($_SESSION['number']);
  unset($_SESSION['questionnaire_id']);
  unset($_SESSION['banner_price']);
  unset($_SESSION['product']);

   /* $wp_session = WP_Session::get_instance();
    $wp_session->reset();
    
   if(isset($_COOKIE[WP_SESSION_COOKIE])) {
      unset($_COOKIE[WP_SESSION_COOKIE]);
   } */

?>
<section class="ads-main-page ">
	<div class="container">
		<div class="tabs-stage no_border">
			<form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
				<!-- <div id="upload-ad" > -->
				<h2 style="margin-left:10px">THANK YOU FOR YOUR SUBMISSION!!!</h2>
				
				
				<h4 style="margin-left:10px"> Please give THE BLACK LIST LLC. 48 hours To review your submission.</h4>
				<h5>CHANGE STARTS NOW!!!</h5>
			   
			</form>
		</div>
	</div>	
</section>
<script type="text/javascript">//<![CDATA[

/*$(function(){
// From http://learn.shayhowe.com/advanced-html-css/jquery

// Change tab class and display content


	$('.tabs-nav a:first').trigger('click'); // Default
});//]]> 
*/
</script>  
<style>

.step-tab {
	padding: 39px 30px;
}

ul, li, div {
    background: hsla(0, 0%, 0%, 0);
    border: 0;
    font-size: 100%;
    margin: 0;
    outline: 0;
    padding: 0;
    vertical-align: baseline;
    font: 13px/20px "Droid Sans", Arial, "Helvetica Neue", "Lucida Grande", sans-serif;

}
li {
    display: list-item;
    text-align: -webkit-match-parent;
}
.tabs-nav {
    list-style: none;
    margin: 0;
    padding: 0;
}
.tabs-nav li:first-child a {
    border-right: 0;
    -moz-border-radius-topleft: 6px;
    -webkit-border-top-left-radius: 6px;
    border-top-left-radius: 6px;
}
.tabs-nav .tab-active a {
    background: hsl(0, 100%, 100%);
    border-bottom-color: hsla(0, 0%, 0%, 0);
    color: hsl(85, 54%, 51%);
    cursor: default;
}
.tabs-nav a {
    background: hsl(120, 11%, 96%);
    border: 1px solid hsl(210, 6%, 79%);
    color: hsl(215, 6%, 57%);
    display: block;
    font-size: 11px;
    font-weight: bold;
    height: 40px;
    line-height: 44px;
    text-align: center;
    text-transform: uppercase;
Success!
    width: 140px;
}
.tabs-nav li {
    float: left;
}
.tabs-stage {
    border: 1px solid hsl(210, 6%, 79%);
    -webkit-border-radius: 0 0 6px 6px;
    -moz-border-radius: 0 0 6px 6px;
    -ms-border-radius: 0 0 6px 6px;
    -o-border-radius: 0 0 6px 6px;
    border-radius: 0 0 6px 6px;

    clear: both;
    margin-bottom: 20px;
    position: relative;
    top: -1px;
    width: 100%;
}
.tabs-stage p {
    margin: 0;
    padding: 20px;
    color: hsl(0, 0%, 33%);
}
  .Table
    {
        display: table;
		  width:100%;
    }
    .Title
    {
        display: table-caption;
        text-align: center;
        font-weight: bold;
        font-size: larger;
    }
    .Heading
    {
        display: table-row;
        font-weight: bold;
        text-align: center;
    }
    .Row
    {
        display: table-row;
    }
    .Cell
    {
        display: table-cell;
        border: solid;
        border-width: thin;
        padding-left: 28px;
		  padding-right: 5px;
		  padding-top: 10px;
		  padding-bottom: 10px;
    }
	.table_Cell
    {
        border: solid;
        border-width: thin;
        padding-left: 28px;
		  padding-right: 5px;
		  padding-top: 10px;
		  padding-bottom: 10px;
    }
	.cell_heading {
    color: #000;
    font-size: 15px;
    font-weight: bold;
	}
	.table_background {
    background-color: #1e73be;
  }
  .size_container {
    font-size: 15px;
    font-weight: 500;
    color: #000;
  }
  .table_label {
		font-size: 20px;
		font-weight: bold;
		color: #1e73be;
		border-bottom: 1px solid;
		width: 96%;
		margin:10px;
		padding-bottom: 8px;
		display:inline-block;
		text-align:center;
  }
 form.form-item div {
	 margin-top:25px;
 }  
 .label_header {
    text-align: center;
    border: 1px solid #000;
    width: 100%;
    color: #000;
    font-size: 16px;
    font-weight: bold;
    padding-top: 5px;
    padding-bottom: 5px;
}
.submit-ad-button {
    float: left;
    width: 100%;
    margin-left: 400px;
    margin-bottom: 25px;
}
.ad-button {
    margin-left: 10px;
}
.abc {
	font-size: 20px !important;
	font-weight: bold !important;
	color: #1e73be !important;
	border-bottom: 1px solid;
	width: 96%;
	margin:10px;
	padding-bottom: 8px;display:inline-block
}	
form h5 {
    text-align: center;
    font-size: 15px;
    font-weight: 900;
}		
form h4 {
    text-align: center;
    font-weight: 900;
}	
form h2 {
    text-align: center;
    font-weight: 900;
}	
#il_business {
	text-align: center;
}						
</style>
<?php get_footer(); ?>
