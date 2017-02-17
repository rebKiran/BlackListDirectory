jQuery(document).ready(function($){
	
	/*
	 * Media Popup - works for admins only
	*/
	$('#select_image_url').click(function()
	{
		wp.media.editor.send.attachment = function(props, attachment)
		{
			$('#image_url').val(attachment.url);
			$('#img_url').html('<img src="'+attachment.url+'" width="25" />');
		}
		wp.media.editor.open(this);
		
		return false;
	});
	
	
	$('.imge-color-field').wpColorPicker();
	
});