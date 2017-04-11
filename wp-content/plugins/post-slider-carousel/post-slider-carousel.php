<?php
/*
  Plugin Name: Post Slider Carousel
  Plugin URI:https://www.i13websolution.com/wordpress-post-slider-carousel-pro.html
  Author URI:https://www.i13websolution.com/wordpress-post-slider-carousel-pro.html
  Description:Post Slider Carousel is beautiful responsive post thumbnail image slider.It support post exclusion,Categort exclusion and also support custom post type.
  Author:I Thirteen Web Solution
  Version:1.0

 */
//error_reporting(0);
add_theme_support('post-thumbnails');
add_filter('widget_text', 'do_shortcode');
add_action('admin_menu', 'psc_admin_menu');
register_activation_hook(__FILE__, 'psc_install_post_slider_carousel');
add_action('wp_enqueue_scripts', 'psc_post_slider_carousel_load_styles_and_js');
add_shortcode('psc_print_post_slider_carousel', 'psc_print_post_slider_carousel_func');
add_action('admin_notices', 'psc_post_slider_carousel_admin_notices');

function psc_post_slider_carousel_admin_notices() {

    if (is_plugin_active('post-slider-carousel/post-slider-carousel.php')) {

        $uploads = wp_upload_dir();
        $baseDir = $uploads['basedir'];
        $baseDir = str_replace("\\", "/", $baseDir);
        $pathToImagesFolder = $baseDir . '/post-slider-carousel';

        if (file_exists($pathToImagesFolder) and is_dir($pathToImagesFolder)) {

            if (!is_writable($pathToImagesFolder)) {
                echo "<div class='updated'><p>Post Slider Carousel is active but does not have write permission on</p><p><b>" . $pathToImagesFolder . "</b> directory.Please allow write permission.</p></div> ";
            }
        } else {

            wp_mkdir_p($pathToImagesFolder);
            if (!file_exists($pathToImagesFolder) and ! is_dir($pathToImagesFolder)) {
                echo "<div class='updated'><p>Post Slider Carousel is active but plugin does not have permission to create directory</p><p><b>" . $pathToImagesFolder . "</b> .Please create post-slider-carousel directory inside upload directory and allow write permission.</p></div> ";
            }
        }
    }
}

function psc_post_slider_carousel_load_styles_and_js() {

    if (!is_admin()) {


        wp_enqueue_style('p_s_c_bx', plugins_url('/css/p_s_c_bx.css', __FILE__));
        wp_enqueue_script('jquery');
        wp_enqueue_script('p_s_c_bx', plugins_url('/js/p_s_c_bx.js', __FILE__));
     
    }
}

function psc_install_post_slider_carousel() {

    global $wpdb;
    
    
    $psc_slider_settings=array('linkimage' => '1','open_link_in'=>0,'min_post'=>1,'max_post'=>3,'max_post_retrive'=>'-1','postype'=>'','post_category'=>'','post_exclude'=>'','show_caption'=>1,'show_pager'=>0,'pauseonmouseover'=>1,'auto'=>0,'speed'=>1000,'pause'=>1000,'circular'=>1,'imageheight'=>'','imagewidth'=>'','imageMargin'=>15,'scroll'=>1,'sort_by'=>'date','sort_direction'=>2,'scollerBackground'=>'#FFFFFF');

    if( !get_option( 'psc_slider_settings' ) ) {

         update_option('psc_slider_settings',$psc_slider_settings);
     } 


    $uploads = wp_upload_dir();
    $baseDir = $uploads['basedir'];
    $baseDir = str_replace("\\", "/", $baseDir);
    $pathToImagesFolder = $baseDir . '/psc_post_slider_carousel';
    wp_mkdir_p($pathToImagesFolder);
}

function psc_admin_menu() {

    $hook_suffix_c_r_l = add_menu_page(__('Post Slider Carousel'), __('Post Slider Carousel'), 'administrator', 'psc_post_slider_carousel', 'psc_post_slider_carousel_options_func');
    $hook_suffix_r_l_2=add_submenu_page( 'psc_post_slider_carousel', __( 'Preview Slider'), __( 'Preview Slider'),'administrator', 'psc_post_slider_carousel_preview', 'psc_post_slider_carousel_preview_func' );
    add_action('load-' . $hook_suffix_c_r_l, 'psc_admin_init');
    add_action('load-' . $hook_suffix_r_l_2, 'psc_admin_init');
}

function psc_admin_init() {


    $url = plugin_dir_url(__FILE__);
    wp_enqueue_script('jquery.validate', $url . 'js/jquery.validate.js');
    wp_enqueue_style('p_s_c_bx', plugins_url('/css/p_s_c_bx.css', __FILE__));

    wp_enqueue_script('jquery');
    wp_enqueue_script('p_s_c_bx', plugins_url('/js/p_s_c_bx.js', __FILE__));
    psc_post_slider_carousel_admin_scripts_init();
}


function psc_post_slider_carousel_options_func(){
       
        if(isset($_POST['btnsave'])){
         
          if(!check_admin_referer( 'action_settings_add_edit','add_edit_nonce' )){

                wp_die('Security check fail'); 
           }

            $show_caption = htmlentities(strip_tags($_POST['show_caption']), ENT_QUOTES);
            $show_pager = htmlentities(strip_tags($_POST['show_pager']), ENT_QUOTES);
            
            $scollerBackground = trim(htmlentities(strip_tags($_POST['scollerBackground']), ENT_QUOTES));
         
             if (isset($_POST['circular']))
                $circular = 1;
            else
                $circular = 0;
          
            if (isset($_POST['pauseonmouseover']))
                $pauseonmouseover = 1;
            else
                $pauseonmouseover = 0;
             
            $auto = trim(htmlentities(strip_tags($_POST['isauto']), ENT_QUOTES));

            if ($auto == 'auto')
                $auto = 1;
            else if ($auto == 'manuall')
                $auto = 0;
            else
                $auto = 2;

            $speed = (int) trim(htmlentities(strip_tags($_POST['speed']), ENT_QUOTES));
            
            if($_POST['pause']==""){
               
                $pause=1000;
                
            }
            else{
                
                $pause = (int) trim(htmlentities(strip_tags($_POST['pause']), ENT_QUOTES));
            }
            
            $min_post = trim(htmlentities(strip_tags($_POST['min_post']), ENT_QUOTES));
            $max_post = trim(htmlentities(strip_tags($_POST['max_post']), ENT_QUOTES));
            $max_post_retrive = trim(htmlentities(strip_tags($_POST['max_post_retrive']), ENT_QUOTES));
            
            
            $postype='';
            if(isset($_POST['postype'])){
                
                    $postype =implode(",",$_POST['postype']);
            
            }
            $post_category='';
            if(isset($_POST['post_category'])){
                
                $post_category=implode(",",$_POST['post_category']);
            }
            
            $post_exclude=trim(htmlentities(strip_tags($_POST['post_exclude']), ENT_QUOTES));
          
            $sort_by=trim(htmlentities(strip_tags($_POST['sort_by']), ENT_QUOTES));
         
            $sort_direction=trim(htmlentities(strip_tags($_POST['sort_direction']), ENT_QUOTES));
            if($sort_direction=='desc'){
                
             $sort_direction=2;   
            }
            else{
                $sort_direction=1;
            }
           
            
            if (isset($_POST['linkimage']))
                $linkimage = 1;
            else
                $linkimage = 0;
            
            if (isset($_POST['open_link_in']))
                $open_link_in = 1;
            else
                $open_link_in = 0;

            $imageheight = (int) trim(htmlentities(strip_tags($_POST['imageheight']), ENT_QUOTES));
            $imagewidth = (int) trim(htmlentities(strip_tags($_POST['imagewidth']), ENT_QUOTES));
          
            $scroll = trim(htmlentities(strip_tags($_POST['scroll']), ENT_QUOTES));

            if ($scroll == "")
                $scroll = 1;

            $imageMargin = (int) trim(htmlentities(strip_tags($_POST['imageMargin']), ENT_QUOTES));
         
            $options=array();
            
             $options['linkimage'] = $linkimage;
             $options['open_link_in'] = $open_link_in;
             $options['min_post'] = $min_post;
             $options['max_post'] = $max_post;
             $options['max_post_retrive'] = $max_post_retrive;
             $options['postype'] =$postype;
             $options['post_category'] =$post_category; 
             $options['post_exclude'] =$post_exclude;
             $options['show_caption'] = $show_caption;
             $options['show_pager'] = $show_pager;
             $options['pauseonmouseover'] = $pauseonmouseover;
             $options['auto'] = $auto;
             $options['speed'] = $speed;
             $options['pause'] = $pause;
             $options['circular'] = $circular;
             $options['imageheight'] =$imageheight;
             $options['imagewidth'] = $imagewidth;
             $options['imageMargin'] = $imageMargin;
             $options['scroll'] = $scroll;
             $options['sort_by'] = $sort_by;
             $options['sort_direction'] = $sort_direction;
             $options['scollerBackground'] =$scollerBackground;

             $settings=update_option('psc_slider_settings',$options); 
             $psc_messages=array();
             $psc_messages['type']='succ';
             $psc_messages['message']='Settings saved successfully.';
             update_option('psc_messages', $psc_messages);

        
         
     }  
     
      $settings=get_option('psc_slider_settings');
      $postypeSelected=array();
      $post_categorySelected=array();
      
       if($settings['postype']!=''){
               
            $postypeSelected=explode(",",$settings['postype']);
        }

        if($settings['post_category']!=''){

            $post_categorySelected=explode(",",$settings['post_category']);

        }
        
        
      
?>      
<div id="poststuff" > 
   <div id="post-body" class="metabox-holder columns-2" >  
      <div id="post-body-content">
          <style>
            #cat_list{height: 200px;overflow: auto}
            #namediv input {
                width: auto;
            }

            #cat_list .children {
                padding-left: 11px;
                padding-top: 8px;
            }

            cat_list.ul {
                padding: 0;
                margin: 0;
                list-style-type: none;
                position: relative;
              }
               li[id*="category"] {
                list-style-type: none;
                border-left: 2px solid #000;
                margin-left: 1em;
                margin-bottom: 0px;
              }
              li[id*="category"] label {
                padding-left: 1em;
                position: relative;
              }
              li[id*="category"] label::before {
                content:'';
                position: absolute;
                top: 0;
                left: -2px;
                bottom: 50%;
                width: 0.75em;
                border: 2px solid #000;
                border-top: 0 none transparent;
                border-right: 0 none transparent;
              }
              ul > li[id*="category"]:last-child {
                border-left: 2px solid transparent;
                margin-bottom: 0px;
                vertical-align:unset;
              }
              .selectit{vertical-align: top}

                .fieldsetAdmin {
                    margin: 10px 0px;
                    padding: 10px;
                    border: 1px solid rgb(221, 221, 221);
                    font-size: 15px;
                }
                    .fieldsetAdmin legend {
                        font-weight: bold;
                        color: #222222;

                    }
        </style>
          <div class="wrap">
              <table><tr><td><a href="https://twitter.com/FreeAdsPost" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @FreeAdsPost</a>
                          <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td>
                      <td>
                          <a target="_blank" title="Donate" href="http://www.i13websolution.com/donate-wordpress_image_thumbnail.php">
                              <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                          </a>
                      </td>
                  </tr>
              </table>

              <?php
                  $messages=get_option('psc_messages'); 
                  $type='';
                  $message='';
                  if(isset($messages['type']) and $messages['type']!=""){

                      $type=$messages['type'];
                      $message=$messages['message'];

                  }  


                  if($type=='err'){ echo "<div class='errMsg'>"; echo $message; echo "</div>";}
                  else if($type=='succ'){ echo "<div class='succMsg'>"; echo $message; echo "</div>";}


                  update_option('psc_messages', array());     
              ?>      
              <span><h3 style="color: blue;"><a target="_blank" href="https://www.i13websolution.com/wordpress-post-slider-carousel-pro.html">UPGRADE TO PRO VERSION</a></h3></span>
              <h2>Slider Settings</h2>
              <div id="poststuff">
                  <div id="post-body" class="metabox-holder columns-2">
                      <div id="post-body-content">
                          <form method="post" action="" id="scrollersettiings" name="scrollersettiings" >
                                        
                                          <fieldset class="fieldsetAdmin">
                                            <legend>Slider Settings</legend>
                                            
                                            
                                          
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                            <h3><label>Show Caption ?</label></h3>
                                            <div class="inside">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <input style="width:20px;" type='radio' <?php if ($settings['show_caption'] == true) {
                                                            echo "checked='checked'";
                                                            } ?>  name='show_caption' value='1' >yes &nbsp;<input style="width:20px;" type='radio' name='show_caption' <?php if ($settings['show_caption'] == false) {
                                                                echo "checked='checked'";
                                                            } ?> value='0' >No
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                            
                                          <div class="stuffbox" id="Show_Pager_div" style="width:100%;">
                                            <h3><label>Show Pager ?</label></h3>
                                            <div class="inside">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <input style="width:20px;" type='radio' <?php if ($settings['show_pager'] == 1) {
                                                            echo "checked='checked'";
                                                        } ?>  name='show_pager' value='1' >yes &nbsp;<input style="width:20px;" type='radio' name='show_pager' <?php if ($settings['show_pager'] ==0) {
                                                            echo "checked='checked'";
                                                        } ?> value='0' >No
                                                            <div style="clear:both"></div>
                                                            <div></div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div style="clear:both"></div>
                                            </div>
                                        </div>

                                        <div class="stuffbox" id="namediv" style="width:100%;">
                                            <h3><label>Slider Background color</label></h3>
                                            <div class="inside">
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <input type="text" id="scollerBackground" size="30" name="scollerBackground" value="<?php echo ($settings['scollerBackground'] != '' and $settings['scollerBackground'] != null) ? $settings['scollerBackground'] : '#ffffff'; ?>"  style="width:100px;">
                                                            <div style="clear:both"></div>
                                                            <div></div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <div style="clear:both"></div>
                                            </div>
                                        </div>
                                            <div class="stuffbox" id="Circular_Slider" style="width:100%;">
                                                <h3><label >Circular Slider ?</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" id="circular" size="30" name="circular" value="" <?php if ($settings['circular'] == true) {
                                                                echo "checked='checked'";
                                                            } ?> style="width:20px;">&nbsp;Circular Slider ? 
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both"></div>

                                                </div>
                                            </div>
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Pause On Mouse Over ?</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" id="pauseonmouseover" size="30" name="pauseonmouseover" value="" <?php if ($settings['pauseonmouseover'] == true) {
                                                                echo "checked='checked'";
                                                            } ?> style="width:20px;">&nbsp;Pause On Mouse Over ? 
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                            <div class="stuffbox" id="Auto_Scroll" style="width:100%;">
                                                <h3><label>Auto Scroll ?</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input style="width:20px;" type='radio' <?php if ($settings['auto'] == 1) {
                                                                echo "checked='checked'";
                                                            } ?>  name='isauto' value='auto' >Auto &nbsp;<input style="width:20px;" type='radio' name='isauto' <?php if ($settings['auto'] == 0) {
                                                                echo "checked='checked'";
                                                            } ?> value='manuall' >Scroll By Left & Right Arrow &nbsp; &nbsp;<input style="width:20px;" type='radio' name='isauto' <?php if ($settings['auto'] == 2) {
                                                                echo "checked='checked'";
                                                            } ?> value='both' >Scroll Auto With Arrow
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>

                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label >Speed</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="speed" size="30" name="speed" value="<?php echo $settings['speed']; ?>" style="width:100px;">
                                                                <div style="clear:both;margin-top:3px" id="speed_example">Example 1000</div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both"></div>

                                                </div>
                                            </div>
                                            <div class="stuffbox" id="Pause_div" style="width:100%;">
                                                <h3><label >Pause</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="pause" size="30" name="pause" value="<?php echo $settings['pause']; ?>" style="width:100px;">
                                                                <div style="clear:both;margin-top:3px">Example 1000</div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both">The amount of time (in ms) between each auto transition</div>
                                                </div>
                                            </div>
                                            
                                        </fieldset>
                                        <fieldset class="fieldsetAdmin">
                                            <legend>Post Settings</legend>
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                            <h3><label>Min Post</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="min_post" size="30" name="min_post" value="<?php echo $settings['min_post']; ?>" style="width:100px;">
                                                                <div style="clear:both">This will decide your slider width in responsive layout.It will show number of post at time.For example 2</div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>

                                                </div>
                                            </div>
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Max Post</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="max_post" size="30" name="max_post" value="<?php echo $settings['max_post']; ?>" style="width:100px;">
                                                                <div style="clear:both">This will decide your slider width automatically.It will show number of post at time.For example 5</div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    specifies the number of items visible at all times within the slider.
                                                    <div style="clear:both"></div>

                                                </div>
                                            </div> 
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Maximum Post To be Retrieve From </label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="max_post_retrive" size="30" name="max_post_retrive" value="<?php echo $settings['max_post_retrive']; ?>" style="width:100px;">
                                                                <div style="clear:both">-1 will retrieve all post from wp_query</div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    specifies the number of post to be retrieved from WP_Query
                                                    <div style="clear:both"></div>

                                                </div>
                                            </div> 
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Post Types to Exclude </label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <ul id="cat_list_">
                                                                <?php
                                                                  
                                                                  $args = array(
                                                                        'public'   => true,
                                                                      
                                                                     );

                                                                    
                                                                     $post_types = get_post_types( $args ); 
                                                                   
                                                                ?>
                                                                    <select id="postype" name="postype[]" style="min-width:200px" multiple="multiple">
                                                                      <option value="">Select</option>    
                                                                        <?php foreach($post_types as $key=>$p):?>
                                                                          <option <?php if(in_array($p,$postypeSelected)):?>  selected="selected" <?php endif;?>  value="<?php echo $p;?>"><?php echo $p;?></option>  
                                                                        <?php endforeach;?> 
                                                                    ?>
                                                                  </select>
                                                                </ul>
                                                                
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>

                                                </div>
                                            </div> 
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Categories To Exclude</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <ul id="cat_list">
                                                                 <?php 
                                                                     echo wp_category_checklist(0,0,$post_categorySelected,false,null,false) ;
                                                                  ?>
                                                                </ul>
                                                                
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>

                                                </div>
                                            </div> 
                                            
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Posts To Exclude</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input id="post_exclude" value="<?php echo $settings['post_exclude'];?>"  size="30" name="post_exclude" value="" type="text">
                                                                
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                     comma separated post id's to exclude.    
                                                    <div style="clear:both"></div>

                                                </div>
                                            </div> 
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                            <h3><label>Sort By</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <select name="sort_by" id="sort_by">
                                                                    <option value="date" <?php if($settings['sort_by']=="date"):?> selected="selected" <?php endif;?> >Date</option>
                                                                    <option value="ID" <?php if($settings['sort_by']=="ID"):?> selected="selected" <?php endif;?>   >ID</option>
                                                                    <option value="author" <?php if($settings['sort_by']=="author"):?> selected="selected" <?php endif;?>>Author</option>
                                                                    <option value="title" <?php if($settings['sort_by']=="title"):?> selected="selected" <?php endif;?>>Title</option>
                                                                    <option value="name" <?php if($settings['sort_by']=="name"):?> selected="selected" <?php endif;?>>Name</option>
                                                                    <option value="rand" <?php if($settings['sort_by']=="rand"):?> selected="selected" <?php endif;?>>Random</option>
                                                                    <option value="menu_order" <?php if($settings['sort_by']=="menu_order"):?> selected="selected" <?php endif;?>>Menu order</option>
                                                                    <option value="comment_count" <?php if($settings['sort_by']=="comment_count"):?> selected="selected" <?php endif;?>>Comment count</option>
                                                                  </select>
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Sort Direction</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <select name="sort_direction" id="sort_direction">
                                                                <option value="asc" <?php if($settings['sort_direction']=="1"):?> selected="selected" <?php endif;?> >Ascending</option>
                                                                <option value="desc" <?php if($settings['sort_direction']=="2"):?> selected="selected" <?php endif;?> >Descending</option>
                                                              </select>
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                            
                                        </fieldset>  
                                         <fieldset class="fieldsetAdmin">
                                           <legend>Image Settings</legend>
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Link images with url ?</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" id="linkimage" size="30" name="linkimage" value="" <?php if ($settings['linkimage'] == true) {
                                                                        echo "checked='checked'";
                                                                    } ?> style="width:20px;">&nbsp;Add link to image ? 
                                                                <div style="clear:both;margin-top:3px">Add link to image? On click user will redirect to post url</div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                           <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Open Post Link In New Tab ?</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" id="open_link_in" size="30" name="open_link_in" value="" <?php if ($settings['open_link_in'] == true) {
                                                                        echo "checked='checked'";
                                                                    } ?> style="width:20px;">&nbsp;Open Link In New Tab? 
                                                                
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Thumbnail Image Height</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="imageheight" size="30" name="imageheight" value="<?php echo $settings['imageheight']; ?>" style="width:100px;">
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Thumbnail Image Width</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="imagewidth" size="30" name="imagewidth" value="<?php echo $settings['imagewidth']; ?>" style="width:100px;">
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>


                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Scroll</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="scroll" size="30" name="scroll" value="<?php echo $settings['scroll']; ?>" style="width:100px;">
                                                                <div style="clear:both"></div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    You can specify the number of items to scroll when you click the next or prev buttons.
                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>


                                            <div class="stuffbox" id="namediv" style="width:100%;">
                                                <h3><label>Image Margin</label></h3>
                                                <div class="inside">
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <input type="text" id="imageMargin" size="30" name="imageMargin" value="<?php echo $settings['imageMargin']; ?>" style="width:100px;">
                                                                <div style="clear:both;padding-top:5px">Gap between two images </div>
                                                                <div></div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                         </fieldset>
                                         
                                        <?php wp_nonce_field('action_settings_add_edit', 'add_edit_nonce'); ?>       
                                        <input type="submit"  name="btnsave" id="btnsave" value="Save Changes" class="button-primary">

                                    </form> 
                                    <script type="text/javascript">

                                        var $n = jQuery.noConflict();
                                        $n(document).ready(function() {
                                        //$n('input[type=radio][name=is_continues]').trigger("change")    
                                        $n("#scrollersettiings").validate({
                                        rules: {
                                        show_caption: {
                                                required:true,
                                                number:true
                                              
                                        },
                                        show_pager: {
                                                required:true,
                                                number:true
                                              
                                        },
                                        scollerBackground:{
                                                required:true,
                                                maxlength:7  
                                            },
                                        isauto: {
                                                required:true


                                        },     
                                        speed: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        pause: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        min_post: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        max_post: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        max_post_retrive: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        imageheight: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        imagewidth: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        scroll: {
                                                 required:true,
                                                 number:true
                                               
                                        },     
                                        imageMargin: {
                                                 required:true,
                                                 number:true
                                               
                                        }    
                                       
                                    },
                                                errorClass: "image_error",
                                                errorPlacement: function(error, element) {
                                                error.appendTo(element.next().next());
                                                }


                                        });

                                                $n('#scollerBackground').wpColorPicker();
                                       
                                        });
                                    </script> 
                                    

                      </div>
                  </div>
              </div>  
          </div>      
      </div>
      <div id="postbox-container-1" class="postbox-container" > 

        <div class="postbox"> 
              <h3 class="hndle"><span></span>Access All Themes In One Price</h3> 
              <div class="inside">
                  <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                  <div style="margin:10px 5px">

                  </div>
              </div></div>
          <div class="postbox"> 
              <h3 class="hndle"><span></span>Best WordPress Themes</h3> 
              
              <div class="inside">
                   <center><a href="https://mythemeshop.com/?ref=nik_gandhi007" target="_blank"><img src="<?php echo plugins_url( 'images/300x250.png', __FILE__ );?>" width="250" height="250" border="0"></a></center>
                  <div style="margin:10px 5px">
                  </div>
              </div></div> 

      </div>      
     <div class="clear"></div>
  </div>  
 </div> 
<?php
}   
function psc_post_slider_carousel_preview_func(){
        
           $settings=get_option('psc_slider_settings');            
           
           $rand_Numb=uniqid('psc_thumnail_slider');
           $rand_Num_td=uniqid('psc_divSliderMain');
           $rand_var_name=uniqid('rand_');
           $uploads = wp_upload_dir();
           $baseDir = $uploads ['basedir'];
           $baseDir = str_replace ( "\\", "/", $baseDir );

           $baseurl=$uploads['baseurl'];
           $baseurl.='/psc_post_slider_carousel/';
           $pathToImagesFolder = $baseDir . '/psc_post_slider_carousel';
           $upload_dir_n=$uploads['basedir'];
                                      
           
     ?>     
          
        <style type='text/css' >
         #<?php echo $rand_Num_td;?> .bx-wrapper .bx-viewport {
             background: none repeat scroll 0 0 <?php echo $settings['scollerBackground']; ?> !important;
             border: 0px none !important;
             box-shadow: 0 0 0 0 !important;
             /*padding:<?php echo $settings['imageMargin'];?>px !important;*/
           }
         </style>
       <?php
            $wpcurrentdir=dirname(__FILE__);
            $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
        ?>
       <div style="width: 100%;">  
            <div style="float:left;width:100%;">
                <div class="wrap">
                        <h2>Slider Preview</h2>
               
                <?php if(is_array($settings)){?>
                <div id="poststuff">
                  <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                         <div style="clear: both;"></div>
                        <?php $url = plugin_dir_url(__FILE__);  ?>           
                      
                          <div style="width: auto;postion:relative" id="<?php echo $rand_Num_td;?>">
                             <div id="<?php echo $rand_Numb;?>" class="post_slider_carousel" style="margin-top: 2px !important;visibility: hidden;">
                                  
                          <?php
                         
                             
                              global $wpdb;
                              $imageheight=$settings['imageheight'];
                              $imagewidth=$settings['imagewidth'];
                              $exs_post_types=$settings['postype'];
                              $exs_post_typesArr=explode(",",$exs_post_types);
                              $postTypesTouse=array();
                              $args = array('public'   => true);
                              $post_types = get_post_types( $args ); 
                              foreach($post_types as $pt){
                                  
                                  if(!in_array($pt,$exs_post_typesArr)){
                                      
                                      $postTypesTouse[]=$pt;
                                  }
                                  
                              }
                              
                              $wp_query_args=array();
                              $wp_query_args['post_type']=$postTypesTouse;
                              $wp_query_args['posts_per_page']=$settings['max_post_retrive'];
                              $wp_query_args['orderby']=$settings['sort_by'];
                              if($settings['sort_direction']=='2'){
                                $wp_query_args['order']= 'desc';
                              }
                              else if($settings['sort_direction']=='1'){
                                  
                                  $wp_query_args['order']= 'asc';
                              }
                              
                              $exs_posts=$settings['post_exclude'];
                              if(trim($exs_posts)!=''){
                                  
                                  $exs_postsArr=explode(",",$exs_posts);
                                  if(sizeof($exs_postsArr)>0){
                                      
                                     $wp_query_args['post__not_in']=$exs_postsArr; 
                                  }
                                  
                              }
                              
                              $exs_categories=$settings['post_category'];
                              if(trim($exs_categories)!=''){
                                  
                                  $exs_catArr=explode(",",$exs_categories);
                                   if(sizeof($exs_catArr)>0){
                                       
                                       $wp_query_args['category__not_in']=$exs_catArr; 
                                   }
                              }
                              
                              
                             $my_query = new WP_Query($wp_query_args);  
                            
                             if ( $my_query->have_posts() ){
                                 
                                while ($my_query->have_posts()) {
                                    
                                    $my_query->the_post();
                                    if(has_post_thumbnail()){
                                        
                                       // $img=wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array($imagewidth,$imageheight),true); 
                                        //$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "Full");
                                        $postThumbnailID = get_post_thumbnail_id( get_the_ID() );
                                        $photoMeta = wp_get_attachment_metadata( $postThumbnailID );
                                        
                                        if(is_array($photoMeta) and isset($photoMeta['file'])) {
                                            
                                            
                                             $fileName=$photoMeta['file'];
                                             $fname=$upload_dir_n.'/'.$fileName;
                                             $image=str_replace("\\","/",$fname);
                                             
                                             $imageNameArr=pathinfo($image);
                                             $imagename=$imageNameArr['basename'];
                                             $filenamewithoutextension=$imageNameArr['filename'];
                                             $extension=$imageNameArr['extension'];
                                             $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
                                             $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
                                           
                                           
                                             if(file_exists($imagetoCheck)){
                                                  
                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
                                                }
                                                else if(file_exists($imagetoCheckSmall)){

                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
                                                }
                                                else{
                                                    
                                                        
                                                        $image = wp_get_image_editor($image); 
                                                        
                                                        if ( ! is_wp_error( $image ) ) {

                                                            
                                                              $image->resize( $imagewidth, $imageheight, true );
                                                              $image->save( $imagetoCheck );
                                                              //$outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                               if(file_exists($imagetoCheck)){
                                                                  $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
                                                              }
                                                              else if(file_exists($imagetoCheckSmall)){
                                                                  $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
                                                              }

                                                          }
                                                          else{
                                                              
                                                             
                                                              $outputimg=psc_get_no_img_url($imageheight,$imagewidth);
                                                          }
                                                    }
                                        }
                                        else{
                                            
                                            $outputimg=psc_get_no_img_url($imageheight,$imagewidth);
                                        }
                                    }
                                    else{
                                        
                                         $outputimg=psc_get_no_img_url($imageheight,$imagewidth);
                                        
                                    }
                                    
                                    $rowTitle=get_the_title();
                                    $rowTitle=str_replace("'","’",$rowTitle); 
                                    $rowTitle=str_replace('"','”',$rowTitle); 

                                              
                                 ?>         
                                 
                                    <div>   
                              
                                      <?php if($settings['linkimage']==true){ ?>                                                                                                                                                                                                                                                                                     
                                        <a data-post_id="<?php echo get_the_ID();?>" <?php if($settings['open_link_in']==true): ?>target="_blank" <?php endif;?>  href="<?php echo get_permalink();?>" title="<?php echo $rowTitle; ?>" ><img src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   /></a>
                                      <?php }else{ ?>
                                        <img  src="<?php echo $outputimg;?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   />
                                      <?php } ?> 
                                   </div>
                               
                           <?php }?>   
                      <?php 
                        }
                       wp_reset_query();
                      ?>   
                    </div>
                        </div>
                    <script>
                    var $n = jQuery.noConflict();  
                    $n(document).ready(function(){
                        
                                
                            var <?php echo $rand_var_name;?>=$n('#<?php echo $rand_Num_td;?>').html();   
                            $n('#<?php echo $rand_Numb;?>').bxSlider({
                                <?php if($settings['min_post']==1 and $settings['max_post']==1):?>
                                  mode:'fade',
                                <?php endif;?>
                                  slideWidth: <?php echo $settings['imagewidth'];?>,
                                   minSlides: <?php echo $settings['min_post'];?>,
                                   maxSlides: <?php echo $settings['max_post'];?>,
                                   moveSlides: <?php echo $settings['scroll'];?>,
                                   slideMargin:<?php echo $settings['imageMargin'];?>,  
                                   speed:<?php echo $settings['speed']; ?>,
                                   pause:<?php echo $settings['pause']; ?>,
                                   adaptiveHeight:false,
                                   <?php if($settings['pauseonmouseover'] and ($settings['auto']==1 or $settings['auto']==2) ){ ?>
                                     autoHover: true,
                                   <?php }else{ if($settings['auto']==1 or $settings['auto']==2){?>   
                                     autoHover:false,
                                   <?php }} ?>
                                   <?php if($settings['auto']==1):?>
                                    controls:false,
                                   <?php else: ?>
                                     controls:true,
                                   <?php endif;?>
                                   pager:false,
                                   useCSS:false,
                                   <?php if($settings['auto']==1 or $settings['auto']==2):?>
                                    autoStart:true,
                                    autoDelay:200,
                                    auto:true,       
                                   <?php endif;?>
                                   <?php if($settings['circular']):?> 
                                    infiniteLoop: true,
                                   <?php else: ?>
                                     infiniteLoop: false,
                                   <?php endif;?>
                                   <?php if($settings['show_caption']):?>
                                     captions:true,  
                                   <?php else:?>
                                     captions:false,
                                   <?php endif;?>
                                   <?php if($settings['show_pager']):?>
                                     pager:true,  
                                   <?php else:?>
                                     pager:false,
                                   <?php endif;?>
                                 

                             });

                             $n("#<?php echo $rand_Numb;?>").css('visibility','visible');
                          
                        
                            
                    });         
                    
                     </script>         
                        
                    </div>
              </div>
            </div>  
                <?php }?>
         </div>      
    </div>                                      
    <div class="clear"></div>
    </div>
    <?php if(is_array($settings)){?>
    
        <h3>To print this slider into WordPress Post/Page use below code</h3>
        <input type="text" value='[psc_print_post_slider_carousel"] ' style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
        <div class="clear"></div>
        <h3>To print this slider into WordPress theme/template PHP files use below code</h3>
        <?php
            $shortcode='[psc_print_post_slider_carousel]';
        ?>
        <input type="text" value="&lt;?php echo do_shortcode('<?php echo htmlentities($shortcode, ENT_QUOTES); ?>'); ?&gt;" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
       
    <?php } ?>
    <div class="clear"></div>
<?php       
   }    
   

function psc_get_no_img_url($imageheight,$imagewidth){
    
        
        $uploads = wp_upload_dir();
        $baseDir = $uploads['basedir'];
        $baseDir = str_replace("\\", "/", $baseDir);
        $pathToImagesFolder = $baseDir . '/post-slider-carousel';
        
        $baseurl=$uploads['baseurl'];
        $baseurl.='/psc_post_slider_carousel/';
        $pathToImagesFolder = $baseDir . '/psc_post_slider_carousel';
        $upload_dir_n=$uploads['basedir'];
        
        $image=plugin_dir_path(__FILE__)."images/no-image-available.jpg";
        $image = str_replace("\\", "/", $image);
        
        $extension='jpg';
        $filenamewithoutextension='no-image-available';
        $imagetoCheck=$pathToImagesFolder.'/'.'no-image-available'.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
        $imagetoCheckSmall=$pathToImagesFolder.'/'.'no-image-available'.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
        
        if(file_exists($imagetoCheck)){
            
            $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
            
        }
        else if(file_exists($imagetoCheckSmall)){
            
                      $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
             }
        else{
            
                
                $image = wp_get_image_editor($image); 
                if ( ! is_wp_error( $image ) ) {


                  $image->resize( $imagewidth, $imageheight, true );
                  $image->save( $imagetoCheck );
                  //$outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                   if(file_exists($imagetoCheck)){
                      $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
                  }
                  else if(file_exists($imagetoCheckSmall)){
                      $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
                  }

              }
        }
       
       return $outputimg;   
                                        
}
function psc_print_post_slider_carousel_func($atts) {

    global $wpdb;
    $rand_Numb=uniqid('psc_thumnail_slider');
    $rand_Num_td=uniqid('psc_divSliderMain');
    $rand_var_name=uniqid('rand_');
    $settings=get_option('psc_slider_settings');  

    $uploads = wp_upload_dir();
    $baseDir = $uploads ['basedir'];
    $baseDir = str_replace ( "\\", "/", $baseDir );

    $baseurl=$uploads['baseurl'];
    $baseurl.='/psc_post_slider_carousel/';
    $pathToImagesFolder = $baseDir . '/psc_post_slider_carousel';
    $upload_dir_n=$uploads['basedir'];
    ob_start();
    ?>  
        
        <style type='text/css' >
         #<?php echo $rand_Num_td;?> .bx-wrapper .bx-viewport {
             background: none repeat scroll 0 0 <?php echo $settings['scollerBackground']; ?> !important;
             border: 0px none !important;
             box-shadow: 0 0 0 0 !important;
             /*padding:<?php echo $settings['imageMargin'];?>px !important;*/
           }
         </style>
      <?php
            $wpcurrentdir=dirname(__FILE__);
            $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
        ?>
     <?php if(is_array($settings)){?>
                <div id="poststuff">
                  <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                         <div style="clear: both;"></div>
                        <?php $url = plugin_dir_url(__FILE__);  ?>           
                        
                       
                            <div style="width: auto;postion:relative" id="<?php echo $rand_Num_td;?>">
                              <div id="<?php echo $rand_Numb;?>" class="post_slider_carousel" style="margin-top: 2px !important;visibility: hidden;">
                                  
                         <?php
                         
                             
                              global $wpdb;
                              $imageheight=$settings['imageheight'];
                              $imagewidth=$settings['imagewidth'];
                              $exs_post_types=$settings['postype'];
                              $exs_post_typesArr=explode(",",$exs_post_types);
                              $postTypesTouse=array();
                              $args = array('public'   => true);
                              $post_types = get_post_types( $args ); 
                              foreach($post_types as $pt){
                                  
                                  if(!in_array($pt,$exs_post_typesArr)){
                                      
                                      $postTypesTouse[]=$pt;
                                  }
                                  
                              }
                              
                              $wp_query_args=array();
                              $wp_query_args['post_type']=$postTypesTouse;
                              $wp_query_args['posts_per_page']=$settings['max_post_retrive'];
                              $wp_query_args['orderby']=$settings['sort_by'];
                              if($settings['sort_direction']=='2'){
                                $wp_query_args['order']= 'desc';
                              }
                              else if($settings['sort_direction']=='1'){
                                  
                                  $wp_query_args['order']= 'asc';
                              }
                              
                              $exs_posts=$settings['post_exclude'];
                              if(trim($exs_posts)!=''){
                                  
                                  $exs_postsArr=explode(",",$exs_posts);
                                  if(sizeof($exs_postsArr)>0){
                                      
                                     $wp_query_args['post__not_in']=$exs_postsArr; 
                                  }
                                  
                              }
                              
                              $exs_categories=$settings['post_category'];
                              if(trim($exs_categories)!=''){
                                  
                                  $exs_catArr=explode(",",$exs_categories);
                                   if(sizeof($exs_catArr)>0){
                                       
                                       $wp_query_args['category__not_in']=$exs_catArr; 
                                   }
                              }
                              
                              
                             $my_query = new WP_Query($wp_query_args);  
                            
                             if ( $my_query->have_posts() ){
                                 
                                while ($my_query->have_posts()) {
                                    
                                    $my_query->the_post();
                                    if(has_post_thumbnail()){
                                        
                                       // $img=wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array($imagewidth,$imageheight),true); 
                                        //$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "Full");
                                        $postThumbnailID = get_post_thumbnail_id( get_the_ID() );
                                        $photoMeta = wp_get_attachment_metadata( $postThumbnailID );
                                        
                                        if(is_array($photoMeta) and isset($photoMeta['file'])) {
                                            
                                            
                                             $fileName=$photoMeta['file'];
                                             $fname=$upload_dir_n.'/'.$fileName;
                                             $image=str_replace("\\","/",$fname);
                                             
                                             $imageNameArr=pathinfo($image);
                                             $imagename=$imageNameArr['basename'];
                                             $filenamewithoutextension=$imageNameArr['filename'];
                                             $extension=$imageNameArr['extension'];
                                             $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
                                             $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
                                           
                                           
                                             if(file_exists($imagetoCheck)){
                                                  
                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
                                                }
                                                else if(file_exists($imagetoCheckSmall)){

                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
                                                }
                                                else{
                                                    
                                                        
                                                        $image = wp_get_image_editor($image); 
                                                        
                                                        if ( ! is_wp_error( $image ) ) {

                                                            
                                                              $image->resize( $imagewidth, $imageheight, true );
                                                              $image->save( $imagetoCheck );
                                                              //$outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                               if(file_exists($imagetoCheck)){
                                                                  $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$extension;
                                                              }
                                                              else if(file_exists($imagetoCheckSmall)){
                                                                  $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($extension);
                                                              }

                                                          }
                                                          else{
                                                              
                                                             
                                                              $outputimg=psc_get_no_img_url($imageheight,$imagewidth);
                                                          }
                                                    }
                                        }
                                        else{
                                            
                                            $outputimg=psc_get_no_img_url($imageheight,$imagewidth);
                                        }
                                    }
                                    else{
                                        
                                         $outputimg=psc_get_no_img_url($imageheight,$imagewidth);
                                        
                                    }
                                    
                                    $rowTitle=get_the_title();
                                    $rowTitle=str_replace("'","’",$rowTitle); 
                                    $rowTitle=str_replace('"','”',$rowTitle); 

                                              
                                 ?>         
                                    <div>   
                                      <?php if($settings['linkimage']==true){ ?>                                                                                                                                                                                                                                                                                     
                                        <a data-post_id="<?php echo get_the_ID();?>" <?php if($settings['open_link_in']==true): ?>target="_blank" <?php endif;?>  href="<?php echo get_permalink();?>" title="<?php echo $rowTitle; ?>" ><img src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   /></a>
                                      <?php }else{ ?>
                                        <img  src="<?php echo $outputimg;?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   />
                                      <?php } ?> 
                                   </div>
                               
                           <?php }?>   
                      <?php 
                        }
                       wp_reset_query();
                      ?>   
                    </div>
                        </div>
                    <script>
                    var $n = jQuery.noConflict();  
                    $n(document).ready(function(){
                        
                                
                            var <?php echo $rand_var_name;?>=$n('#<?php echo $rand_Num_td;?>').html();   
                            $n('#<?php echo $rand_Numb;?>').bxSlider({
                                <?php if($settings['min_post']==5 and $settings['max_post']==5):?>
                                  mode:'fade',
                                <?php endif;?>
                                  slideWidth: <?php echo $settings['imagewidth'];?>,
                                   minSlides: <?php echo $settings['min_post'];?>,
                                   maxSlides: <?php echo $settings['max_post'];?>,
                                   moveSlides: <?php echo $settings['scroll'];?>,
                                   slideMargin:<?php echo $settings['imageMargin'];?>,  
                                   speed:<?php echo $settings['speed']; ?>,
                                   pause:<?php echo $settings['pause']; ?>,
                                   adaptiveHeight:false,
                                   <?php if($settings['pauseonmouseover'] and ($settings['auto']==1 or $settings['auto']==2) ){ ?>
                                     autoHover: true,
                                   <?php }else{ if($settings['auto']==1 or $settings['auto']==2){?>   
                                     autoHover:false,
                                   <?php }} ?>
                                   <?php if($settings['auto']==1):?>
                                    controls:false,
                                   <?php else: ?>
                                     controls:true,
                                   <?php endif;?>
                                   pager:false,
                                   useCSS:false,
                                   <?php if($settings['auto']==1 or $settings['auto']==2):?>
                                    autoStart:true,
                                    autoDelay:200,
                                    auto:true,       
                                   <?php endif;?>
                                   <?php if($settings['circular']):?> 
                                    infiniteLoop: true,
                                   <?php else: ?>
                                     infiniteLoop: false,
                                   <?php endif;?>
                                   <?php if($settings['show_caption']):?>
                                     captions:true,  
                                   <?php else:?>
                                     captions:false,
                                   <?php endif;?>
                                   <?php if($settings['show_pager']):?>
                                     pager:true,  
                                   <?php else:?>
                                     pager:false,
                                   <?php endif;?>
                                   

                             });

                             $n("#<?php echo $rand_Numb;?>").css('visibility','visible');
                          
                          
                            
                    });         
                    
                     </script>         
                        
                    </div>
              </div>
            </div>  
     <?php }?>                     
    <?php
    $output = ob_get_clean();
    return $output;
}


//also we will add an option function that will check for plugin admin page or not
function psc_post_slider_carousel_is_plugin_page() {
    $server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

    foreach (array('psc_post_slider_carousel') as $allowURI) {
        if (stristr($server_uri, $allowURI))
            return true;
    }
    return false;
}

//add media WP scripts
function psc_post_slider_carousel_admin_scripts_init() {
    if (psc_post_slider_carousel_is_plugin_page()) {
        //double check for WordPress version and function exists
        if (function_exists('wp_enqueue_media')) {
            //call for new media manager
            wp_enqueue_media();
        }
        wp_enqueue_style('media');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }
}

?>