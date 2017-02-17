/*
 * AJAX UPLOADER
*/


function vbc_load_ajax_upload(ajaxurl){
jQuery(function($){
	
    var visualbannercreator_Upload = {
        init:function () {
			
            window.visualbannercreatorUploadCount = typeof(window.visualbannercreatorUploadCount) == 'undefined' ? 0 : window.visualbannercreatorUploadCount;
            this.maxFiles = parseInt(visualbannercreator_upload.number);

            $('#visualbannercreator-upload-imagelist').on('click', 'a.action-delete', this.removeUploads);

            this.attach();
            this.hideUploader();
           $('#add_image').hide();
        },
        attach:function () {
            // wordpress plupload if not found
            if (typeof(plupload) === 'undefined') {
                return;
            }

            if (visualbannercreator_upload.upload_enabled !== '1') {
                return
            }

            var uploader = new plupload.Uploader(visualbannercreator_upload.plupload);
			

            $('#visualbannercreator-uploader').click(function (e) {
                uploader.start();
				
                // To prevent default behavior of a tag
                e.preventDefault();
            });

            //initilize  wp plupload
            uploader.init();

            uploader.bind('FilesAdded', function (up, files) {
                $.each(files, function (i, file) {
                    $('#visualbannercreator-upload-imagelist').append(
                        '<div id="' + file.id + '">' +
                            file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                            '</div>');
                });

                up.refresh(); // Reposition Flash/Silverlight
                uploader.start();
            });

            uploader.bind('UploadProgress', function (up, file) { $('#add_image').hide();
                $('#' + file.id + " b").html(file.percent + "%");
            });

            // On erro occur
            uploader.bind('Error', function (up, err) {
                $('#visualbannercreator-upload-imagelist').append("<div>Error: " + err.code +
                    ", Message: " + err.message +
                    (err.file ? ", File: " + err.file.name : "") +
                    "</div>"
                );

                up.refresh(); // Reposition Flash/Silverlight
            });

            uploader.bind('FileUploaded', function (up, file, response) {
               /* var result = $.parseJSON(response.response);  */
                
                 var result = eval(JSON.parse(response.response));          

                $('#' + file.id).remove();
				
                if (result.success) {
                    $('#add_image').show();
                    window.visualbannercreatorUploadCount += 1;
					
                    $('#visualbannercreator-upload-imagelist ul').append(result.html);

                    visualbannercreator_Upload.hideUploader();
                }
            });


        },

        hideUploader:function () {

            if (visualbannercreator_Upload.maxFiles !== 0 && window.visualbannercreatorUploadCount >= visualbannercreator_Upload.maxFiles) {
                $('#visualbannercreator-uploader').hide();
            }
        },

        removeUploads:function (e) {
            e.preventDefault();

            if (confirm(visualbannercreator_upload.confirmMsg)) {

                var el = $(this),
                    data = {
                        'attach_id':el.data('upload_id'),
                        'nonce':visualbannercreator_upload.remove,
                        'action':'visualbannercreator_delete'
                    };

                $.post(visualbannercreator_upload.ajaxurl, data, function () {
                    el.parent().remove();

                    window.visualbannercreatorUploadCount -= 1;
                    if (visualbannercreator_Upload.maxFiles !== 0 && window.visualbannercreatorUploadCount < visualbannercreator_Upload.maxFiles) {
                        $('#visualbannercreator-uploader').show();
                    }
                });
            }
        }

    };
	
	visualbannercreator_Upload.init();
	
	
});

}