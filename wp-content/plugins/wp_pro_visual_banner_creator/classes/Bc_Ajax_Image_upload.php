<?php
class Bc_Ajax_Image_Upload
{
	public function add_script()
    {
        $default_options = array(
            "max_upload_size" => "100 ",
            "max_upload_no" => "1",
            "allow_ext" => "jpg,gif,png"
        );

        wp_enqueue_script('jquery');
        wp_enqueue_script('plupload-handlers');

        $max_file_size = intval($default_options['max_upload_size']) * 1000 * 1000;
        $max_upload_no = intval($default_options['max_upload_no']);
        $allow_ext = $default_options['allow_ext'];

        wp_enqueue_script('visualbannercreator_upload', WP_BC_INC_URL . '/js/AjaxUpload.js', array('jquery'));

        wp_localize_script('visualbannercreator_upload', 'visualbannercreator_upload', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('visualbannercreator_upload'),
            'remove' => wp_create_nonce('visualbannercreator_remove'),
            'number' => $max_upload_no,
            'upload_enabled' => true,
            'confirmMsg' => __('Are you sure you want to delete this?'),
            'plupload' => array(
                'runtimes' => 'html5,flash,html4',
                'browse_button' => 'visualbannercreator-uploader',
                'container' => 'visualbannercreator-upload-container',
                'file_data_name' => 'visualbannercreator_upload_file',
                'max_file_size' => $max_file_size . 'b',
                'url' => admin_url('admin-ajax.php') . '?action=visualbannercreator_upload&nonce=' . wp_create_nonce('visualbannercreator_allow'),
                'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
                'filters' => array(array('title' => __('Allowed Files'), 'extensions' => $allow_ext)),
                'multipart' => true,
                'urlstream_upload' => true,
            )
        ));

    }

    public function upload()
    {
        check_ajax_referer('visualbannercreator_allow', 'nonce');

        $file = array(
            'name' => $_FILES['visualbannercreator_upload_file']['name'],
            'type' => $_FILES['visualbannercreator_upload_file']['type'],
            'tmp_name' => $_FILES['visualbannercreator_upload_file']['tmp_name'],
            'error' => $_FILES['visualbannercreator_upload_file']['error'],
            'size' => $_FILES['visualbannercreator_upload_file']['size']
        );
        $file = $this->fileupload_process($file);


    }

    public function fileupload_process($file)
    {
        $attachment = $this->handle_file($file);

        if (is_array($attachment)) {
            $html = $this->getHTML($attachment);

            $response = array(
                'success' => true,
                'html' => $html
            );

            echo json_encode($response);
			//echo 'err1';$:
			
            exit;
        }

        //$response = array('success' => false);
		$response = array('success' => 'oi_test');
        echo json_encode($response);
		
        exit;
    }

    function handle_file($upload_data)
    {

        $return = false;
        $uploaded_file = wp_handle_upload($upload_data, array('test_form' => false));

        if (isset($uploaded_file['file'])) {
            $file_loc = $uploaded_file['file'];
            $file_name = basename($upload_data['name']);
            $file_type = wp_check_filetype($file_name);

            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $file_loc);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_loc);
            wp_update_attachment_metadata($attach_id, $attach_data);

            $return = array('data' => $attach_data, 'id' => $attach_id);

            return $return;
        }

        return $return;
    }
	
	
	public function delete_file()
    {
        $attach_id = $_POST['attach_id'];
        wp_delete_attachment($attach_id, true);
        exit;
    }
	

    function getHTML($attachment)
    {
		// http://codex.wordpress.org/Function_Reference/wp_generate_attachment_metadata
        $attach_id = $attachment['id'];
		$post = get_post($attach_id);
        /*$file = explode('/', $attachment['data']['file']);
        $file = array_slice($file, 0, count($file) - 1);
        $path = implode('/', $file);
        $image = $attachment['data']['sizes']['medium']['file'];
        $dir = wp_upload_dir();
        $path = $dir['baseurl'] . '/' . $path;*/
		$image_url = wp_get_attachment_url( $attach_id ); //wp_get_attachment_image_src( $attach_id );

        $html = '';
        $html .= '<li class="visualbannercreator-uploaded-files">';
			$html .= '<div class="adzone_preview">';
        		$html .= sprintf('<img src="%s" name="' . $post->post_title . '" width="25" />', $image_url); // $path . '/' . $image
			$html .= '</div>';
 			// Replaced to function: form_items
			//$html .= '<input type="hidden" name="banner_img" value="'.$image_url.'" />'; 
			//$html .= '<input type="hidden" name="attach_id" value="'.$attach_id.'" />'; 
			$html .= sprintf('<br /><a href="#" class="action-delete" data-upload_id="%d">%s</a></span>', $attach_id, __('Delete','wpproads'));
			$html .= '<input type="hidden" id="image_url" name="image_url" value="'.$image_url.'" />'; 
			$html .= '<input type="hidden" name="attach_id" value="'.$attach_id.'" />'; 
		$html .= '</li>';
			

        return $html;
    }
	
}
?>