<?php

?>

<h3><strong><?php _e('Review Display Options', 'rich-reviews'); ?></strong></h3>
<div style="border: solid 2px black"></div>

<h4><strong><?php _e('Title Options', 'rich-reviews'); ?></strong></h4>
<input type="checkbox" name="show_form_post_title" value="checked" <?php echo $options['show_form_post_title'] ?> />
<label for="show_form_post_title">
	<?php _e('Include Post Titles - this will include the title and a link to the form page for every review.', 'rich-reviews'); ?>
</label>
<br />

<h4><strong><?php _e('Rating Options', 'rich-reviews'); ?></strong></h4>
<input type="checkbox" name="snippet_stars" value="checked" <?php echo $options['snippet_stars'] ?> />
<label for="snippet_stars">
	<?php _e('Star Snippets - this will change the averge rating displayed in the snippet shortcode to be stars instead of numerical values.', 'rich-reviews'); ?>
</label>
<br />

<input type="color" name="star_color" value="<?php echo $options['star_color'] ?>">
<label for="star_color">
	<?php _e('Star Color - the color of the stars on reviews', 'rich-reviews'); ?>
</label>
<br />

<h4><strong><?php _e('General Display Options', 'rich-reviews'); ?></strong></h4>
<input type="checkbox" name="display_full_width" value="checked" <?php echo $options['display_full_width'] ?> />
<label for="display_full_width">
	<?php _e('Display Full width - This option will display the reviews in full width block format. Default will dsplay the reviews in blocks of three.', 'rich-reviews'); ?>
</label>
<br />

<input type="checkbox" name="show_date" value="checked" <?php echo $options['show_date'] ?> />
<label for="show_date">
	<?php _e('Display the date that the review was submitted inside the review.', 'rich-reviews'); ?>
</label>
<br />

<input type="checkbox" name="credit_permission" value="checked" <?php echo $options['credit_permission'] ?> />
<label for="credit_permission">
	<?php _e('Give Credit to Nuanced Media - this option will add a small credit line and a link to Nuanced Media\'s website to the bottom of your reviews page', 'rich-reviews'); ?>
</label>
<br />


<label for="excerpt-length">
	<?php _e('Set the character length of the review excerpt to be displayed on page load. The remainder will be displayed and/or hidden via "More/Less" javascript elements.', 'rich-reviews'); ?>
</label>
<br />
<input type="number" name="excerpt-length" min="51" value="<?php if (isset($options['excerpt-length']) && $options['excerpt-length'] > 50 ) { echo $options['excerpt-length']; } else { echo 150; } ?>" />
<br/>


<label for="read-more-text">
	<?php _e('Text for Read More action: ', 'rich-reviews'); ?>
</label>
<input type="text" name="read-more-text" value="<?php if (isset($options['read-more-text']) && $options['read-more-text'] != '') { echo $options['read-more-text']; } else { echo ''; } ?>" />
<br />


<label for="show-less-text">
	<?php _e('Text for Show Less action: ', 'rich-reviews'); ?>
</label>
<input type="text" name="show-less-text" value="<?php if (isset($options['show-less-text']) && $options['show-less-text'] != '') { echo $options['show-less-text']; } else { echo ''; } ?>" />
<br />

<label for="reviews_order"><strong><?php _e('Review Display Order: ', 'rich-reviews'); ?></strong></label>
<select name="reviews_order" value="<?php echo $options['reviews_order'] ?>">
	<?php
	if ($options['reviews_order']==="ASC"){ ?><option value="ASC" selected="selected"><?php _e('Oldest First', 'rich-reviews'); ?></option><?php }else {?><option value="ASC" ><?php _e('Oldest First', 'rich-reviews'); ?></option><?php }
	if ($options['reviews_order']==="DESC"){ ?><option value="DESC" selected="selected"><?php _e('Newest First', 'rich-reviews'); ?></option><?php }else {?><option value="DESC" ><?php _e('Newest First', 'rich-reviews'); ?></option><?php }
	if ($options['reviews_order']==="random"){ ?><option value="random" selected="selected"><?php _e('Randomize', 'rich-reviews'); ?></option><?php }else {?><option value="random" ><?php _e('Randomize', 'rich-reviews'); ?></option><?php }
	if ($options['reviews_order']==="highest_rating"){ ?><option value="highest_rating" selected="selected"><?php _e('Highest Rating First', 'rich-reviews'); ?></option><?php }else {?><option value="highest_rating" ><?php _e('Highest Rating First', 'rich-reviews'); ?></option><?php }
	?>
</select>
<br />
