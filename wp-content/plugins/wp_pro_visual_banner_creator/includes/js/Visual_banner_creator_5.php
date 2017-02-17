/*
 * VISUAL BANNER CREATOR functions
*/
var my_object = {};
var canvas = this.__canvas = new fabric.Canvas('bcCanvas');


/*
 * IMPORT BANNER ELEMENTS
*/
function vbc_json_import( json_import ){
	
	jQuery(function($){
		
		if( json_import != '' )
		{
			$.each(json_import.objects, function(arrayID,group) {
				
				import_functions(group);
			});
			
			//canvas.loadFromJSON($('#banner_json').val(),canvas.renderAll.bind(canvas));
			//canvas.renderAll();
		}
	});
}
// end vbc_json_import()
// END IMPORT


/*
 * IMPORT FUNCTIONS
*/
function import_functions( group, groupID ){
	
	var error = [];
	
	if( group.type != 'text'){
	
		if(group.type == 'circle'){
						
			var layerID = get_layer_id('circle', group.radius);
			var type = 'circle';
			my_object[layerID] = new fabric.Circle({ 
				id: layerID,
				radius: group.radius,
				originX: group.originX,
				originY: group.originY, 
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				fill: group.fill,
				fillRule: group.fillRule, 
				stroke: group.stroke,
				strokeWidth: group.strokeWidth,
				left: group.left, 
				top: group.top,
				opacity: group.opacity,
				transparentCorners: false
			});
		}
		else if( group.type == 'rect'){
			
			var layerID = get_layer_id('rectangle', '');
			var type = 'rectangle';
			my_object[layerID] = new fabric.Rect({
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				rx: group.rx, 
				ry: group.ry,
				angle: group.angle,
				fill: group.fill,
				fillRule: group.fillRule,
				stroke: group.stroke,
				strokeWidth: group.strokeWidth,
				opacity: group.opacity,
				transparentCorners: false
			});	
		}else if( group.type == 'polygon'){
			var layerID = get_layer_id('polygon', '');
			var type = 'polygon';
			my_object[layerID] = new fabric.Polygon(group.points, {
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				angle: group.angle,
				fill: group.fill,
				fillRule: group.fillRule,
				stroke: group.stroke,
				strokeWidth: group.strokeWidth,
				opacity: group.opacity,
				transparentCorners: false
			});
			
		}else if( group.type == 'polyline'){
			var layerID = get_layer_id('polyline', '');
			var type = 'polyline';
			my_object[layerID] = new fabric.Polyline(group.points, {
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				angle: group.angle,
				fill: group.fill,
				stroke: group.stroke,
				strokeWidth: group.strokeWidth,
				opacity: group.opacity,
				transparentCorners: false
			});
			
		}else if( group.type == 'ellipse'){
			var layerID = get_layer_id('ellipse', '');
			var type = 'ellipse';
			my_object[layerID] = new fabric.Ellipse({
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				angle: group.angle,
				fill: group.fill,
				rx: group.rx, 
				ry: group.ry,
				cx: group.cx, 
				cy: group.cy,
				opacity: group.opacity,
				transparentCorners: false
			});
			
		}else if( group.type == 'line'){
			var layerID = get_layer_id('line', '');
			var type = 'line';
			console.log(group);
			my_object[layerID] = new fabric.Line([group.x1, group.y1, group.x2, group.y2], {
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				angle: group.angle,
				fill: group.fill,
				stroke: group.stroke,
				strokeWidth: group.strokeWidth,
				opacity: group.opacity,
				transparentCorners: false
			});
			
		}else if( group.type == 'triangle'){
			var layerID = get_layer_id('triangle', '');
			var type = 'triangle';
			my_object[layerID] = new fabric.Triangle({
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				angle: group.angle,
				fill: group.fill,
				opacity: group.opacity,
				transparentCorners: false
			});	
		}else if( group.type == 'path'){
			var layerID = get_layer_id('path', '');
			var type = 'path';
			my_object[layerID] = new fabric.Path(group.path);
			my_object[layerID].set({ 
				id: layerID,
				groupID: groupID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				rx: group.rx, 
				ry: group.ry,
				angle: group.angle,
				fill: group.fill,
				stroke: group.stroke,
				strokeWidth: group.strokeWidth,
				transparentCorners: false
			});
		}else if( group.type == 'group'){
			
			var layerID = get_layer_id('group', '');
			var type = 'group';
			
			create_selection( group.objects );
			my_object[layerID] = group_selection();
			
			my_object[layerID].set({ 
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				width: group.width,
				height: group.height,
				//rx: group.rx, 
				//ry: group.ry,
				angle: group.angle,
				//fill: group.fill,
				transparentCorners: false
			});
			
		}else if( group.type == 'i-text'){
			var layerID = get_layer_id( 'text' );
			
			addGoogleFont(group.fontFamily, group.fontWeight);
			
			my_object[layerID] = new fabric.IText(group.text, {
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				fontFamily: group.fontFamily,
				fontSize: group.fontSize,
				fontWeight: group.fontWeight,
				textAlign: group.textAlign,
				lineHeight: group.lineHeight,
				fontWeight: group.fontWeight,
				textDecoration: group.textDecoration,
				strokeWidth: group.strokeWidth,
				stroke: group.stroke,
				angle: group.angle,
				fill: group.fill,
				opacity: group.opacity,
				hasRotatingPoint: true,
				centerTransform: true,
				transparentCorners: false
			});
		}else if( group.type == 'image'){
			var layerID = get_layer_id( 'image' );
			
			fabric.Image.fromURL(group.src, function( oimg ) {
				my_object[layerID] = oimg;
		
				canvas.add(my_object[layerID]);
			},{
				id: layerID,
				left: group.left,
				top: group.top,
				originX: group.originX,
				originY: group.originY,
				scaleX: group.scaleX,
				scaleY: group.scaleY,
				flipX: group.flipX,
				flipY: group.flipY,
				angle: group.angle
			});
		}
		
		if( group.type != 'image' ){
			canvas.add(my_object[layerID]);
			canvas.renderAll();	
		}
		
		if( group.type == 'circle' || group.type == 'rect' || group.type == 'triangle' ){
			var type_icon = get_type_icon( type );
			edit_layer_btn(layerID, '<i class="fa fa-'+type_icon+'" style="color:'+group.fill+'; opacity:'+group.opacity+';"></i>');
			//canvas.add(my_object[layerID]);
		}else if( group.type == 'path' ){
			var img = my_object[layerID].toDataURL('png');
			edit_layer_btn(layerID, '<img src="'+img+'" class="edit_li_img" style="opacity:'+group.opacity+';" />', group.scale);
		}else if( group.type == 'group'){
			var img = my_object[layerID].toDataURL('png');
			edit_layer_btn(layerID, '<img src="'+img+'"class="edit_li_img" />');
		}else if( group.type == 'i-text' ){
			var stroke_css = group.stroke != '' ? 'text-shadow: -1px 0 '+group.stroke+', 0 1px '+group.stroke+', 1px 0 '+group.stroke+', 0 -1px '+group.stroke+';' : '';
			edit_layer_btn(layerID, '<strong style="font-family:'+group.fontFamily+'; color:'+group.fill+'; opacity:'+group.opacity+'; '+stroke_css+'">'+group.text+'</strong');
			//canvas.add(my_object[layerID]);	
		}else if( group.type == 'image' ){
			edit_layer_btn(layerID, '<img src="'+group.src+'" class="edit_li_img" style="opacity:'+group.opacity+';" />', group.scale);	
		}
		
		return my_object[layerID];
	}else{
		error['error'] = 'Text type is not supported.';
		return error;	
	}
}





/*
 * SAVE BANNER
*/
function bc_save_banner( export_png ){
	canvas.deactivateAllWithDispatch().renderAll();
	
	var data_uri = canvas.toDataURL('png');
	var json = JSON.stringify(canvas);
	var banner_id = jQuery('#banner_id').val();
	var ajaxurl = jQuery('#bc_ajaxurl').val();
	//var price = '';
	
	///alert( 'price : ');
	//if('#hid_formatted_price').length > 0) {
		var price = jQuery('#hid_formatted_price').val();
	//}
	var postTitle = jQuery('#postTitle').val();	
	var banner_id = jQuery('#banner_id').val();
	
	jQuery('#banner_data_uri').val(data_uri);
	jQuery('#banner_json').val(json);
	
	jQuery('.pro_ads_banner_creator').css({ opacity : .5});
	
	jQuery.ajax({
	   type: "POST",
	   url: ajaxurl,
	   data: "action=bc_save_banner&banner_id="+banner_id+"&price="+price+"&postTitle="+postTitle+"&data_uri="+ data_uri+"&json="+json,
	   success: function( obj ){  
		   jQuery('#image_save_id').val(1);
		  // jQuery('input[type="hidden"][name="add-to-cart"]').val(banner_id);
		  // jQuery('.cart .single_add_to_cart_button').show();
		   
		 
		   msg = JSON.parse( obj );
		   
		   jQuery('#banner_json_64').val(msg.json);
		   
		   if( export_png ){
			   jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: "action=bc_export_image&img="+ msg.data_uri+"&banner_id="+msg.banner_id,
					success: function( obj ){
						// jQuery('.cart .single_add_to_cart_button').show();
						
						//  jQuery('input[type="hidden"][name="add-to-cart"]').val(banner_id);
						   jQuery('#image_save_id').val(1);
						
						msg = JSON.parse( obj );
						//alert(msg.url);
						jQuery('#banner_url').val(msg.url);
						jQuery('.pro_ads_banner_creator').css({ opacity : 1});
					}
				});
		   }else{
			    jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: "action=bc_export_image&img="+ msg.data_uri+"&banner_id="+msg.banner_id,
					success: function( obj ){
						// jQuery('.cart .single_add_to_cart_button').show();
						
						//  jQuery('input[type="hidden"][name="add-to-cart"]').val(banner_id);
						   jQuery('#image_save_id').val(1);
						
						msg = JSON.parse( obj );
						//alert(msg.url);
						jQuery('#banner_url').val(msg.url);
						jQuery('.pro_ads_banner_creator').css({ opacity : 1});
					}
				});
			 //  jQuery('.pro_ads_banner_creator').css({ opacity : 1});   
			   //jQuery('#image_save_id').val(0);
		   }
			 
	   }
  });
}
// END SAVE BANNER


function bc_save_image_banner(){
	canvas.deactivateAllWithDispatch().renderAll();
	
	var data_uri = canvas.toDataURL('png');
	var json = JSON.stringify(canvas);
	var banner_id = jQuery('#banner_id').val();
	var ajaxurl = jQuery('#bc_ajaxurl').val();
	var postTitle = jQuery('#postTitle').val();	
	var product_id = jQuery('#hid_product_id').val();
	var product_price = jQuery('#hid_banner_price').val();
	var banner_id = jQuery('#banner_id').val();
	var banner_url= jQuery('#banner_url').val();
	
	jQuery('#banner_data_uri').val(data_uri);
	jQuery('#banner_json').val(json);
	
	jQuery('.pro_ads_banner_creator').css({ opacity : .5});
	
	jQuery.ajax({
	   type: "POST",
	   url: ajaxurl,
	   data: "action=bc_save_banner_img&id="+product_id+"&banner_id="+banner_id+"&price="+product_price+"&postTitle="+postTitle+"&data_uri="+data_uri+"&json="+json+"&banner_url="+banner_url,
	   success: function( obj ){  
		  // jQuery('#image_save_id').val(1);
		  // jQuery('input[type="hidden"][name="add-to-cart"]').val(banner_id);
		  // jQuery('.cart .single_add_to_cart_button').show();
		   
		 
		   msg = JSON.parse( obj );
		   
		   jQuery('#banner_json_64').val(msg.json);
		   
			/*jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: "action=bc_export_image&img="+ msg.data_uri+"&banner_id="+msg.banner_id,
				success: function( obj ){
					// jQuery('.cart .single_add_to_cart_button').show();
					
					//  jQuery('input[type="hidden"][name="add-to-cart"]').val(banner_id);
					 //  jQuery('#image_save_id').val(1);
					
					msg = JSON.parse( obj );
					//alert(msg.url);
					jQuery('#banner_url').val(msg.url);
					jQuery('.pro_ads_banner_creator').css({ opacity : 1});
				}
			});*/
		 //  jQuery('.pro_ads_banner_creator').css({ opacity : 1});   
		   //jQuery('#image_save_id').val(0);
		   
		   var product_id = jQuery('#add-to-cart').val();
			//alert( 'product_id : '+product_id );
			var link_url = 'http://blacklistdir.rebelute.in/?add-to-cart='+product_id+'&quantity=1';
		
			//window.location =  'http://blacklistdir.rebelute.in/about-your-business/';
			window.location =  link_url;
		 
	   }
  });
}
// END SAVE BANNER

function addGoogleFont(FontName, FontWeight) {
	if( FontName != 'Verdana' && FontName != 'Verdana, sans-serif' && FontName != 'Arial' && FontName != 'Impact' ){
		//var weight = FontWeight != '' ? ':'+FontWeight : '';
		//jQuery("head").append("<link href='https://fonts.googleapis.com/css?family=" + FontName + weight + "' rel='stylesheet' type='text/css'>");
		jQuery("head").append("<link href='https://fonts.googleapis.com/css?family=" + FontName + ":100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic' rel='stylesheet' type='text/css'>");
	}
}






function get_layer_id( type, value ){
	var number = 1 + Math.floor(Math.random() * 100000);
	var value = !value ? '' : '_'+value;
	value = value.split('.').join("_");
	var layer = type+'_'+number+value;
	
	return layer;	
}


function get_type_icon( type ){
	var icon;
	
	if( type == 'circle'){
		icon = 'circle';	
	}else if( type == 'rectangle' || type == 'rect'){
		icon = 'square';	
	}else if( type == 'triangle' ){
		icon = 'play';	
	}
	
	return icon;
}


function edit_layer_btn( itemID, value, scale ){
	var value = !value ? '' : value;
	var scale = !scale ? 1 : scale;
	
	jQuery('#log').prepend(
		'<li itemid="'+itemID+'" class="edit_li_item item_cont_'+itemID+'">'+
		'<div style="float:left;">'+
		'<a class="remove_obj remove" item="'+itemID+'" title="Remove"><i class="fa fa-times"></i></a> '+
		'<small>ID: '+itemID +'</small>'+
		'</div>'+
		'<span style="display:inline-block; float:left; margin:0 0 0 5px;" class="bc_preview_icon_'+itemID+'">'+value+'</span>'+
		'<div style="float:right; color:#DDD;"><small>Drag to change layer index</small></div></li>');
	
	save_img_btn_status();	
}



function save_img_btn_status(){
	
	// Enable Save Image button
	if( jQuery("#log").children().length ){
		jQuery('#export_bc').show();
		jQuery('#save_bc').show();
		jQuery('.canvas_sidebar').hide();
		
	}else{
		jQuery('#export_bc').hide();
		jQuery('#save_bc').hide();
		jQuery('.canvas_sidebar').show();
	}
}



function bc_canvas_update_size(width, height){
	canvas.setHeight(height);
	canvas.setWidth(width);
}



function create_selection(objects){
	var svg_arr = []; 
	var error = [];
			
	jQuery.each(objects, function(i,obj) {
		//console.log(obj);
		var item = import_functions(obj);
		if( item && !item['error'] ){
			svg_arr.push( item );
		}else if(item['error']){
			error.push(item['error']);
		}
	});
	
	if( error[0] ){
		alert(error[0]);
	}
	//canvas.setActiveGroup(new fabric.Group(canvas.getObjects())).renderAll();
	canvas.setActiveGroup(new fabric.Group( svg_arr )).renderAll();	
}


function group_selection(){
	var group = {};
	var activegroup = canvas.getActiveGroup();
	var objectsInGroup = activegroup.getObjects();
	
	if( objectsInGroup.length > 0 ){
		activegroup.clone(function(newgroup) {
			canvas.discardActiveGroup();
			objectsInGroup.forEach(function(object) {
				canvas.remove(object);
				jQuery('.item_cont_'+object.get('id')).remove();  
			});
			
			group[0] = newgroup;
		});	
		
		return group[0];
	}
}












jQuery(document).ready(function($){
	
	
	$('.menu_icons a').on('click', function(){
		
		sidebar_menu_actions( this );
		
	});
	$('.vbc_tooltip').tooltipster({
		theme: 'tooltipster-light'
	});
	
	function sidebar_menu_actions( item ){
		$('.menu_icons a').removeClass('selected');
		$(item).addClass('selected');
		$('.vbc_menu_option').hide();
		$('.'+$(item).attr('data-target')).show();
	}
	
	$('.vbc_design_item').on('click', function(){
		var ajaxurl = $('#bc_ajaxurl').val();
		
		var svg = $(this).html();
		
		fabric.loadSVGFromString(svg, function(objects) {
				
				var layerID = get_layer_id('group', '');
				var type = 'group';
				
				create_selection(objects);	
				my_object[layerID] = group_selection();
				
				// Reset position to make sure its visible.
				my_object[layerID].setOptions({ top: 10, left:10 });
				
				if( my_object[layerID] ){
					//my_object[layerID].setOptions());
					canvas.add(my_object[layerID]);
					canvas.renderAll();	
					
					var img = my_object[layerID].toDataURL('png');
					edit_layer_btn(layerID, '<img src="'+img+'" width="10" />');
				}
				});
		
		/*
		$.ajax({
		   type: "POST",
		   url: ajaxurl,
		   data: "action=vbc_base64_decode&svg="+$(this).html(),
		   success: function( svg ){
			   alert(svg);
			  
			   
		   }
		});
		*/
	});
	
	
	$('#start_drawing').on('click', function(){
		$(this).hide();
		$('#stop_drawing').show();
		$('.drawing_settings_cont').show();
		canvas.isDrawingMode = true;
		canvas.freeDrawingBrush.width = $('#vbc_drawing_line_width').val();
		canvas.freeDrawingBrush.color = $('#update_drawing_color').val();
	});
	$('#stop_drawing').on('click', function(){
		$(this).hide();
		$('#start_drawing').show();
		$('.drawing_settings_cont').hide();
		canvas.isDrawingMode = false;
	});
	
	$('#update_drawing_settings').on('click', function(){
		canvas.freeDrawingBrush.width = parseInt($('#vbc_drawing_line_width').val(), 10);
		canvas.freeDrawingBrush.color = $('#update_drawing_color').val();
		
		if( $('.vbc_drawing_brush_type').val() == 'hline'){
			canvas.freeDrawingBrush = vLinePatternBrush;
		}else if( $('.vbc_drawing_brush_type').val() == 'vline' ){
			canvas.freeDrawingBrush = hLinePatternBrush;
		}else if( $('.vbc_drawing_brush_type').val() == 'square' ){
			canvas.freeDrawingBrush = squarePatternBrush;
		}else if( $('.vbc_drawing_brush_type').val() == 'diamond' ){
			canvas.freeDrawingBrush = diamondPatternBrush;
		}else if( $('.vbc_drawing_brush_type').val() == 'texture' ){
			canvas.freeDrawingBrush = texturePatternBrush;
		}else{
			canvas.freeDrawingBrush = new fabric[$('.vbc_drawing_brush_type').val() + 'Brush'](canvas);
		}
		
	});
	
	
	// BRUSHES
	if (fabric.PatternBrush) {
		var vLinePatternBrush = new fabric.PatternBrush(canvas);
		vLinePatternBrush.getPatternSrc = function() {
			var patternCanvas = fabric.document.createElement('canvas');
			patternCanvas.width = patternCanvas.height = 10;
			var ctx = patternCanvas.getContext('2d');
			
			ctx.strokeStyle = this.color;
			ctx.lineWidth = 5;
			ctx.beginPath();
			ctx.moveTo(0, 5);
			ctx.lineTo(10, 5);
			ctx.closePath();
			ctx.stroke();
			
			return patternCanvas;
		};
		
		var hLinePatternBrush = new fabric.PatternBrush(canvas);
		hLinePatternBrush.getPatternSrc = function() {
			var patternCanvas = fabric.document.createElement('canvas');
			patternCanvas.width = patternCanvas.height = 10;
			var ctx = patternCanvas.getContext('2d');
			
			ctx.strokeStyle = this.color;
			ctx.lineWidth = 5;
			ctx.beginPath();
			ctx.moveTo(5, 0);
			ctx.lineTo(5, 10);
			ctx.closePath();
			ctx.stroke();
			
			return patternCanvas;
		};
		
		var squarePatternBrush = new fabric.PatternBrush(canvas);
		squarePatternBrush.getPatternSrc = function() {
			var squareWidth = 10, squareDistance = 2;
			
			var patternCanvas = fabric.document.createElement('canvas');
			patternCanvas.width = patternCanvas.height = squareWidth + squareDistance;
			var ctx = patternCanvas.getContext('2d');
			
			ctx.fillStyle = this.color;
			ctx.fillRect(0, 0, squareWidth, squareWidth);
			
			return patternCanvas;
		};
		
		var diamondPatternBrush = new fabric.PatternBrush(canvas);
		diamondPatternBrush.getPatternSrc = function() {
				var squareWidth = 10, squareDistance = 5;
				var patternCanvas = fabric.document.createElement('canvas');
				var rect = new fabric.Rect({
				width: squareWidth,
				height: squareWidth,
				angle: 45,
				fill: this.color
			});
			
			var canvasWidth = rect.getBoundingRectWidth();
			
			patternCanvas.width = patternCanvas.height = canvasWidth + squareDistance;
			rect.set({ left: canvasWidth / 2, top: canvasWidth / 2 });
			
			var ctx = patternCanvas.getContext('2d');
			rect.render(ctx);
			
			return patternCanvas;
		};
  }
	


	
	
	
	/* 
	 * WIDGET ACTIONS
	*/
	var val;
	var arr;
	
	// Remove objects
	$('body').on('click', '.remove_obj', function(){
		canvas.remove( my_object[jQuery(this).attr('item')] );
		$('.item_cont_'+jQuery(this).attr('item')).remove();
		
		save_img_btn_status();
	});
	
	// Banner Size
	$('#banner_size').change(function(){
	
	
		val = $(this).val();
	
		if( val == 'custom')
		{
			$('#custom_size').show();
		}
		else
		{
			$('#custom_size').hide();
			arr = val.split('x');
		}
	});
	
	$('#banner_size').click(function(){
		
		var size = $(this).val();
		size = size.trim();
		var banner_id = $('#banner_id').val();
		
		var ajaxurl = $('#bc_ajaxurl').val();
		
		
		if( size ){
		
			$.ajax({
			   type: "POST",
			   url: ajaxurl,
			   data: 'action=bc_show_banner_price&banner_id='+banner_id+'&banner_size='+size,
			   dataType:'json',
			 
			   success: function( obj ){ 
			   
                            var arr = JSON.parse(JSON.stringify(obj));
			/*  var arr = JSON.parse(obj); */
			  
			  /*  var arr = $.parseJSON(obj);*/
			  
				
					
			      $('#hid_banner_price').val( arr.price);
				 
				  $('#hid_product_id').val( arr.id);
				  $('#banner_price').show();
				 $('#banner_price').html( 'Price :-   <span class="woocommerce-Price-currencySymbol">$</span> ' + arr.price);
				  
				 $('#add-to-cart').val( arr.id );
				 // alert( 'add-to-cart : ' + $('#add-to-cart').val() );
				 
			   }
			});
		} else {
			
				$('#hid_banner_price').val( '');
				$('#banner_price').hide();
				$('#banner_price').html( '' );
		}
		
		
	});
	
	
	// Update Canvas size
	$('#update_canvas').click(function(){
		
		var val = $('#banner_size').val();
		
		if( val == 'custom' || arr[0] == '')
		{
			var width = $('#canvas_width').val();
			var height = $('#canvas_height').val();
		}
		else
		{
			var width = arr[0];
			var height = arr[1];
		}
		
		bc_canvas_update_size(width, height);
		
		var banner_id = $('#banner_id').val();
		var ajaxurl = $('#bc_ajaxurl').val();
		if( banner_id ){
			$.ajax({
			   type: "POST",
			   url: ajaxurl,
			   data: "action=bc_save_banner_size&banner_id="+banner_id+"&width="+width+"&height="+height,
			   success: function( obj ){
				   //alert(obj)
			   }
			});
		}
	});
	
	
	// Update Canvas size
	$('#add-to-listing').click(function(){
		
		bc_save_image_banner();
		
		
			canvas.deactivateAllWithDispatch().renderAll();
		
			var data_uri = canvas.toDataURL('png');
			var json = JSON.stringify(canvas);
			var product_id = $('#hid_product_id').val();
			var product_price = $('#hid_banner_price').val();
			var banner_id = jQuery('#banner_id').val();
			var banner_url= jQuery('#banner_url').val();
			//alert( 'banner_id: -' + banner_id);
			//var banner_id = $('#banner_id').val();
			//var ajaxurl = $('#bc_ajaxurl').val();
			var ajaxurl = "http://blacklistdir.rebelute.in/wp-admin/admin-ajax.php";
			if( product_id ){
				$.ajax({
				   type: "POST",
				   url: ajaxurl,
				   data: "action=bc_add_to_listing&id="+product_id+"&banner_id="+banner_id+"&price="+product_price+"&data_uri="+ data_uri+"&json="+json+'&banner_url='+banner_url,
				   success: function( obj ){
					   //alert(obj)
				   }
				});
			} 
			window.location =  'http://blacklistdir.rebelute.in/about-your-business/';
		
	});
	
	
	
	$('#add-to-cart_product').click(function(){
		
		canvas.deactivateAllWithDispatch().renderAll();
	
		var data_uri = canvas.toDataURL('png');
		var json = JSON.stringify(canvas);
		var banner_id = jQuery('#banner_id').val();
		var ajaxurl = jQuery('#bc_ajaxurl').val();
		var postTitle = jQuery('#postTitle').val();	
		var product_id = jQuery('#hid_product_id').val();
		var product_price = jQuery('#hid_banner_price').val();
		var banner_id = jQuery('#banner_id').val();
		var banner_url= jQuery('#banner_url').val();
		
		jQuery('#banner_data_uri').val(data_uri);
		jQuery('#banner_json').val(json);
	
			jQuery('.pro_ads_banner_creator').css({ opacity : .5});
			
			jQuery.ajax({
			   type: "POST",
			   url: ajaxurl,
			   data: "action=bc_save_banner_img&id="+product_id+"&banner_id="+banner_id+"&price="+product_price+"&postTitle="+postTitle+"&data_uri="+data_uri+"&json="+json+"&banner_url="+banner_url,
                           dataType:'json',
			   success: function( obj ){  
				 
				   msg = JSON.parse(JSON.stringify(obj));
				 /*  msg = JSON.parse( obj );  */
				   
				   jQuery('#banner_json_64').val(msg.json);
				   
				
				  var product_id = jQuery('#add-to-cart').val();
					
					var link_url = 'http://blacklistdir.rebelute.in/?add-to-cart='+product_id+'&quantity=1';
				
					
					window.location =  link_url; 
				 
			   }
		  });
	
	});
	
	/*
	 * Canvas Area BUTTONS
	*/
	$('#preview_bc').click(function(){
		canvas.deactivateAllWithDispatch().renderAll();
		
		var img = canvas.toDataURL('png');
		window.open( img );
	});
	
	
	$('#save_bc').click(function(){
		
		bc_save_banner(0);
	});
	
	
	$('#export_bc').click(function(){
		
		bc_save_banner(1);
	});
	
	
	$('#clear_bc').click(function(){
		
		if (confirm('Are you sure?')) {
			canvas.clear();
			$('#log').html('');
			var banner_id = $('#banner_id').val();
			var ajaxurl = $('#bc_ajaxurl').val();
			save_img_btn_status();
			
			$.ajax({
			   type: "POST",
			   url: ajaxurl,
			   data: "action=bc_save_banner&banner_id="+banner_id+"&data_uri=&json=",
			   success: function( obj ){
				   
				   $('#banner_data_uri').val('');
				   $('#banner_json').val('');
			   }
			});
		}
	});
	
	
	/*
	 * ADD ELEMENTS 
	*/
	$('#add_image').click(function(){
                
		var scale = 1;
		var layerID = get_layer_id('image');
		var image = $('#image_url').val();
		var opacity = $('#image_opacity').val() != '' ? $('#image_opacity').val() : 1;
		//$('#image_loader').attr('src', image);
		$('#img_loader_cont').append('<img id="image_loader_'+layerID+'" src="'+image+'" />');
		// source: $('#image_loader_'+layerID)[0],
		
		fabric.Image.fromURL(image, function( oimg ) {
			my_object[layerID] = oimg;
			my_object[layerID].set({
				id: layerID,
				opacity: opacity,
				transparentCorners: false
			})
			.scale(scale)
			.setCoords();
			canvas.add( my_object[layerID] );
		});
		
		edit_layer_btn(layerID, '<img src="'+image+'" width="10" style="opacity:'+opacity+';" />', scale);
		$('#image_url').val('');
		$('#img_url').html('');
		// frontend upload
		$('#visualbannercreator-upload-imagelist ul').html('');
		$('#visualbannercreator-uploader').show();
	});

	$('#add_text').click(function(){
		
		var text = $('#text_ipt').val();
		if( text != '' ){
			var color = $('#text_color').val() != '' ? $('#text_color').val() : '#9cf';
			var stroke_color = $('#text_border_color').val() != '' ? $('#text_border_color').val() : 'transparent';
			var stroke_width = $('#font_border_width').val() != '' ? $('#font_border_width').val() : 1;
			var font = $('#font_name').val() != '' ? $('#font_name').val() : 'Verdana, sans-serif';
			var opacity = $('#font_opacity').val() != '' ? $('#font_opacity').val() : 1;
			var font_weight = $('#font_weight').val() != '' ? $('#font_weight').val() : '';
			
			addGoogleFont(font, font_weight); // for example
			
			var font_size = $('#font_size').val() != '' ? $('#font_size').val() : 48;
			var text_align = $('#text_align').val() != '' ? $('#text_align').val() : 'left';
			var line_height = $('#line_height').val() != '' ? $('#line_height').val() : 1;
			var text_decoration = $('#text_decoration').val() != '' ? $('#text_decoration').val() : '';
			var layerID = get_layer_id( 'text' );
			
			
			
			my_object[layerID] = new fabric.IText(text, {
				id: layerID,
				left: 10,
				top: 10,
				fontFamily: font,
				fontSize: font_size,
				strokeWidth: stroke_width,
				stroke: stroke_color,
				//angle: getRandomInt(-10, 10),
				fill: color,
				opacity: opacity,
				//scaleX: 0.5,
				//scaleY: 0.5,
				textAlign: text_align,
				lineHeight: line_height,
				fontWeight: font_weight,
				textDecoration: text_decoration,
				originX: 'left',
				hasRotatingPoint: true,
				centerTransform: true,
				transparentCorners: false
			});
			canvas.add(my_object[layerID]);
			canvas.renderAll();
			
			var stroke_css = stroke_color != '' ? 'text-shadow: -1px 0 '+stroke_color+', 0 1px '+stroke_color+', 1px 0 '+stroke_color+', 0 -1px '+stroke_color+';' : '';
			edit_layer_btn(layerID, '<strong style="font-family:'+font+'; color:'+color+'; opacity:'+opacity+'; '+stroke_css+'">'+text+'</strong');
		}
	});
		
	$('#add_object').click(function(){
		
		var type = $('#object_type').val();
		var color = $('#object_bg_color').val() != '' ? $('#object_bg_color').val() : '#000';
		var opacity = $('#object_opacity').val() != '' ? $('#object_opacity').val() : 1;
		
		if( type == 'circle') {
			
			var radius = 50;
			var layerID = get_layer_id('circle', radius);
			
			my_object[layerID] = new fabric.Circle({ 
				id: layerID,
				radius: radius, 
				fill: color, 
				left: 20, 
				top: 20,
				opacity: opacity,
				transparentCorners: false
			});
		}
		else if( type == 'rectangle') {
			
			var layerID = get_layer_id('rectangle', '');
			var border_radius = $('#object_border_radius').val() != '' ? $('#object_border_radius').val() : 0;
			
			my_object[layerID] = new fabric.Rect({
				id: layerID,
				left: 20,
				top: 20,
				originX: 'left',
				originY: 'top',
				rx: border_radius, 
				ry: border_radius,
				width: 100,
				height: 100,
				angle: 0,
				fill: color,
				opacity: opacity,
				transparentCorners: false
			});
		}
		else if( type == 'triangle' ) {
			
			var layerID = get_layer_id('triangle', '');
			
			my_object[layerID] = new fabric.Triangle({
				id: layerID,
				left: 20, 
				top: 20,
				width: 100,
				height: 100,
				fill: color,
				opacity: opacity,
				transparentCorners: false
			});
		}
		
		canvas.add(my_object[layerID]);
		
		var type_icon = get_type_icon( type );
		edit_layer_btn(layerID, '<i class="fa fa-'+type_icon+'" style="color:'+color+'; opacity:'+opacity+';"></i>');
	});
	
	$('#object_type').on('change', function(){
		if( $(this).val() == 'rectangle'){
			$('.border_radius_box').show();
		}else{
			$('.border_radius_box').hide();
		}
	});	
	
	
	
	/*
	 * Load SVG button
	*/
	$('#load_svg').on('click', function(){
		var svg = $('#load_svg_code').val();
		var keep_layers = $('.vbc_keep_layers').is(':checked')
		
		if( keep_layers){ 
			fabric.loadSVGFromString(svg, function(objects) {
			
				var layerID = get_layer_id('group', '');
				var type = 'group';
				
				create_selection(objects);	
			});
		}else{
			fabric.loadSVGFromString(svg, function(objects) {
				
				var layerID = get_layer_id('group', '');
				var type = 'group';
				
				create_selection(objects);	
				my_object[layerID] = group_selection();
				
				// Reset position to make sure its visible.
				my_object[layerID].setOptions({ top: 10, left:10 });
				
				if( my_object[layerID] ){
					//my_object[layerID].setOptions());
					canvas.add(my_object[layerID]);
					canvas.renderAll();	
					
					var img = my_object[layerID].toDataURL('png');
					edit_layer_btn(layerID, '<img src="'+img+'" width="10" />');
				}
			});
		}
		
		$('#load_svg_code').val('');
	});
	
	
	
	
	// Create Drawed object
	if( canvas ){
		canvas.on('path:created', function(e){
			var drawn_path = e.path;
			//var layerID = get_layer_id('group', '');
			//var type = 'group';
			
			obj = import_functions(drawn_path);
			canvas.remove(drawn_path);
		});	
	}
	
	
	
	
	
	/*
	 * UNDO / REDO
	*/
	if( canvas ){
		
		$('#undo_action').on('click', function(){
			undo();
		});
		
		var current;
		var list = [];
		var state = [];
		var index = 0;
		var index2 = 0;
		
		canvas.on("object:added", function (e) {
			var object = e.target;
			
			state[index] = JSON.stringify(object.originalState);
			list[index] = object;
			index++;
			index2 = index - 1;
		});
		
		canvas.on("object:modified", function (e) {
			var object = e.target;
			
			state[index] = JSON.stringify(object.originalState);
			list[index] = object;
			index++;
			index2 = index - 1;
		});
	}
	
	function undo() {
		if (index <= 0) {
			index = 0;
			return;
		}
		
		index2 = index - 1;
		current = list[index2];
		//current.setOptions(JSON.parse(state[index2]));
		var obj = JSON.parse(state[index2]);
		current = reset_obj(current, obj);
	
		index--;
		current.setCoords();
		canvas.renderAll();
	}
	
	function reset_obj(current, obj){
		
		if( current.type == 'group'){
			current.setOptions({
				left: obj.left,
				top: obj.top,
			});
		}else{
			current.setOptions({
				left: obj.left,
				top: obj.top,
				fill: obj.fill
			});
		}
		
		return current;
	}
	
	
	
	
	
	
	/*
	 * GROUP SELECTED ITEMS
	 * http://jsfiddle.net/softvar/NuE78/1/
	*/
	if( canvas){
		canvas.on('selection:created', function(e) {
			
			var layerID = get_layer_id('group', '');
			var type = 'group';
			
			my_object[layerID] = group_selection();
			
			canvas.add(my_object[layerID]);
			canvas.renderAll();	
			
			var img = my_object[layerID].toDataURL('png');
			edit_layer_btn(layerID, '<img src="'+img+'" width="10" />');
			
			/*var group1 = new fabric.Group([ circle, rect ], { left: 100, top: 100 });
			canvas.add(group1);*/
		});
	}
	
	
	
	
	
	/*
	 * UPDATE SELECTED ELEMENTS 
	*/
	// On item select
	if( canvas){
		canvas.observe('mouse:down', function(){
			var activeObject = canvas.getActiveObject();
			if (activeObject) {
				//alert(canvas.toSVG());
				$('.edt_no_item_selected').hide();
				$('.edt_container').show();
				sidebar_menu_actions( '.vbc_edit_element_btn' );
				
				if( $('.edit-element-tab').hasClass('open') ){
					
				}else{ 
					$('.edit-element-tab').toggleClass('open'); 
					$('.edit-element-tab').next().slideToggle(); 
					open_sidebar('.edit-element-tab'); 
				}
				
				var type = activeObject.get('type');
				$('#update_object_bg_color').iris('color', activeObject.get('fill'));
				$('#update_object_stroke_color').iris('color', activeObject.get('stroke'));
				
				// default options
				var opacity = activeObject.get('opacity');
				$('.edt_object_opacity').val(opacity);
				
				
				// if text item
				if( type == 'i-text'){
					$('.text_values').show();
					var lineHeight = activeObject.get('lineHeight') != '' ? activeObject.get('lineHeight') : 1;
					var fontWeight = activeObject.get('fontWeight') != '' ? activeObject.get('fontWeight') : 400;
					var fontFamily = activeObject.get('fontFamily') != '' ? activeObject.get('fontFamily') : 'Arial';
					
					$('.txt_line_height').val(lineHeight);
					$('.txt_font_weight').val(fontWeight);
					$('.edt_font_name').val(fontFamily);
					
					$('.edt_font_name').trigger('chosen:updated');
				}else{
					$('.text_values').hide();
				}
				
				// if object Rectangle
				if( type == 'rect'){
					$('.edt_border_radius_box').show();
					var rx = activeObject.get('rx') != '' ? activeObject.get('rx') : 0;
					$('.edt_object_border_radius').val(rx);
				}else{
					$('.edt_border_radius_box').hide();
				}
				
			}else{
				$('.edt_no_item_selected').show();
				$('.edt_container').hide();
			}
		});
	}
	
	
	$('.bc_remove_selected_element').click(function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			canvas.remove(activeObject);
			$('.item_cont_'+activeObject.get('id')).remove();
		}else{
			alert('No element selected.')	
		}
	});
	$('.bc_bring_to_front_selected_element').click(function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			
		  canvas.bringToFront(activeObject);
		}
	});
	$('.bc_send_to_back_selected_element').click(function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
		  canvas.sendToBack(activeObject);
		}
	});
	
	// Text values
	$('.txt_align_left').click(function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			activeObject.set('textAlign', 'left');
			canvas.renderAll();
		}
	});
	$('.txt_align_center').click(function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			activeObject.set('textAlign', 'center');
			canvas.renderAll();
		}
	});
	$('.txt_align_right').click(function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			activeObject.set('textAlign', 'right');
			canvas.renderAll();
		}
	});
	$('.txt_line_height').on('change', function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			activeObject.set('lineHeight', $(this).val());
			canvas.renderAll();
		}
	});
	$('.txt_font_weight').on('change', function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			
			activeObject.set('fontWeight', $(this).val());
			canvas.renderAll();
		}
	});
	$('.edt_font_name').on('change', function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			
			addGoogleFont($(this).val(), '');
			activeObject.set('fontFamily', $(this).val());
			canvas.renderAll();
		}
	});
	
	$('.edt_object_border_radius').on('change', function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			
			activeObject.set('rx', $(this).val());
			activeObject.set('ry', $(this).val());
			canvas.renderAll();
		}
	});
	
	// default options
	$('.edt_object_opacity').on('change', function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			
			activeObject.set('opacity', $(this).val());
			canvas.renderAll();
		}
	});
	
	
	$('#update_element').click(function(){
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			var item_id = activeObject.get('id');
			type_icon = type_icon == 'rect' ? 'rectangle' : type_icon;
			
			// bg color/ fill
			var color = $('#update_object_bg_color').val();
			var stroke_color = $('#update_object_stroke_color').val();
			var type_icon = get_type_icon( activeObject.get('type') );
		
			activeObject.set('fill', color);
			activeObject.set('stroke', stroke_color);
			canvas.renderAll();
			
			if( activeObject.get('type') == 'group' ){
				var img = my_object[activeObject.get('id')].toDataURL('png');
				$('.bc_preview_icon_'+item_id).html('<img src="'+img+'"class="edit_li_img" />');
			}else{
				$('.bc_preview_icon_'+item_id).html('<i class="fa fa-'+type_icon+'" style="color:'+color+';"></i>'); // opacity:'+opacity+';
			}
		}else{
			alert('No element selected.')	
		}
	});
	
	
	
	
	
	
	
	
	
	
	/*
	 * LOG
	*/
	$( "#log" ).sortable({
		update: function( event, ui ) {
			//alert("New position: " + ui.item.index());	
		},
		stop: function(e, ui) {
			$.map($(this).find('li'), function(el) {
				
				canvas.sendToBack( my_object[$(el).attr('itemid')] );
			});
		},
		placeholder: "ui-state-highlight"
	});
	$( "#log" ).disableSelection();
	
	
	
	
	
	
	
	
	
	/*
	 * SIDEBAR OPTIONS
	*/			
	$(".sidebar-name").click(function(){
		
		$(this).toggleClass('open');
		$(this).next().slideToggle();
		
		if($(this).hasClass('open')){
			open_sidebar( this )
		}else{
			close_sidebar( this );
		}
	});
	
	
	/*
	$(".option-cancel").click(function(){
		if( $(".option-title-toggle").hasClass('open') ){
			$(".option-title-toggle").removeClass('open');
			$(".option-title-toggle").next().slideToggle(500);
		}
	});*/
	
	
	
	$('.imge-color-field').wpColorPicker();
	$(".pro_ads_banner_creator .chosen-select").chosen({width: '100%'});
	
	
	/* https://github.com/dellsala/Combo-Box-jQuery-Plugin */
	/*
	$('#font_name').combobox([
		'Verdana',
		'Arial',
		'Impact'
	]);
	*/
});


function open_sidebar( item ){
	jQuery( item ).find('.vbc_btn_icon').html('-');
	
	/*if( jQuery(item).hasClass('edit-element-tab')){
		jQuery('.edit-element-tab .fa').toggleClass('fa-pencil-square-o');
		jQuery('.edit-element-tab .fa').toggleClass('fa-pencil-square');
	}*/
}
function close_sidebar( item ){
	jQuery( item ).find('.vbc_btn_icon').html('+');
	
	if( jQuery(item).hasClass('edit-element-tab')){
		jQuery('.edt_no_item_selected').show();
		jQuery('.edt_container').hide();
	}
	canvas.deactivateAllWithDispatch().renderAll();
}