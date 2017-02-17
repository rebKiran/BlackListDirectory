<?php
?>

	<tr class="rr_form_row" style="line-height: 0px ! important;">
		<td class="rr_form_heading <?php echo 'rr_required';  ?>" >
			<?php echo $label; ?>
		</td>
		<td class="rr_form_input">
			
		      <input class="rr_small_input" type="text" name="r<?php echo $inputId; ?>" value="<?php echo $rFieldValue ; ?>" <?php if($disable) { echo 'disabled'; } ?> />
                       <span class="form-err<?php if ($error != '') { echo ' shown'; } ?>"><?php echo $error; ?></span>
		</td>
	</tr>
