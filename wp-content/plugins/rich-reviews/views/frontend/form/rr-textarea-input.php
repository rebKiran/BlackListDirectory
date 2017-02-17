<?php
?>
	<tr class="rr_form_row" style="line-height: 0px ! important;">
		<td class="rr_form_heading <?php if($require){ echo 'rr_required'; } ?>">
			<?php echo $label; ?>
		</td>
		<td class="rr_form_input">
			
			<textarea class="rr_large_input" name="rText" id="rText123" rows="10" style="resize: vertical;height: 70px;"><?php echo $rFieldValue; ?></textarea>
                        <span class="form-err<?php if ($error != '') { echo ' shown'; } ?>"><?php echo $error; ?></span>
		</td>
	</tr>
