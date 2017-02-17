<?php
?>

<tr class="rr_form_row" style="line-height: 0px ! important;">
	<td class="rr_form_heading rr_required"><?php echo $label; ?></td>
	<td class="rr_form_input">
		
		<div class="rr_stars_container">
			<span class="rr_star glyphicon glyphicon-star-empty" id="rr_star_1"></span>
			<span class="rr_star glyphicon glyphicon-star-empty" id="rr_star_2"></span>
			<span class="rr_star glyphicon glyphicon-star-empty" id="rr_star_3"></span>
			<span class="rr_star glyphicon glyphicon-star-empty" id="rr_star_4"></span>
			<span class="rr_star glyphicon glyphicon-star-empty" id="rr_star_5"></span>
		</div>
                <span  id="rating-err" class="form-err<?php if ($error != '') { echo ' shown'; } ?>"><?php echo $error; ?></span>
	</td>
</tr>

<?php render_custom_styles($options);

