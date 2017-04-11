(function ($) {
    'use strict';

    $(document).ready(function () {

		$(".wpd-responsive-mode .wpc-editor-menu").toggle(
			function wpc_editor_menu_on(){
				
				$(".wpd-responsive-mode .wpc-editor-col.col").css("display","inline-block");
			},
			function wpc_editor_menu_off(){
				
				$(".wpd-responsive-mode .wpc-editor-col.col").css("display","none");
				
			}
		
		);

    });

})(jQuery);