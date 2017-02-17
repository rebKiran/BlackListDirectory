<?php
if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	
	if( isset( $_POST['1'] ))
	{
		update_option( 'wpbc_available_for', $_POST['available'] );
		
		// Administrators
		if( $_POST['available'] == 'add_users' )
		{
			$available_role = 'Administrators';
		}
		// Editors
		elseif( $_POST['available'] == 'publish_posts' )
		{
			$available_role = 'Editors';
		}
		// Authors
		elseif( $_POST['available'] == 'edit_posts' )
		{
			$available_role = 'Authors';
		}
		// Contributors
		elseif( $_POST['available'] == 'delete_posts' )
		{
			$available_role = 'Contributers';
		}
		// Subscribers
		else
		{
			$available_role = 'Subscibers';
		}
		
		$err[] = sprintf( __('Settings Updated. %s and above are able to create banners.', 'wpbc'), $available_role );
	}
}
?>

<div class="wrap">

	<div id="pro_bc">
    	
        <div id="icon-ivc" class="icon32"></div>
        <h2><?php echo $page_title; ?></h2>
    
    
        <?php
        if( $err )
        {
            ?>
            <div class="updated err">
                <?php
                foreach( $err as $error )
                {
                    ?>
                    <div><?php echo $error; ?></div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
		?>

               
        <table id="tuna_tab_customization">
            <tr>
            	<td valign="top">
                    <div id="tuna_tab_left">
                        <ul>
                            <?php 
							if( current_user_can('add_users') )
							{
								?>
								<li><a class="focused" data-target="admin-settings"><?php _e('Admin Settings', 'wpproivc'); ?></a></li>
								<?php
							}
							?>
                        </ul>					
                    </div>
                </td>
                <td width="100%" valign="top">
                    <div id="tuna_tab_right">
                        <p id="tuna_tab_arrow" style="top:4px"></p>
                        <div class="customization_right_in">

							<?php
							if( current_user_can('add_users') )
							{
								?>
                                <div id="admin-settings" class="nfer">
                                    <h3 style="margin-bottom:10px"><?php _e('Admin Settings', 'wpbc'); ?></h3>
                                    <em class="hr_line"></em>
                                    
                                    <p class="intro">
                                        <?php _e('Info.', 'wpbc'); ?> 
                                    </p>
                                    <form action="" method="post">
                                        <div class="tuna_meta metabox-holder">
                                
                                            <div class="postbox nobg">
                                                <div class="inside">
                                                    <table class="form-table">
                                                        <tbody>
                                                            <tr valign="top">
                                                                <th scope="row">
                                                                    <?php _e('Which users can create banners?', 'wpbc'); ?>
                                                                    <span class="description"><?php _e('','wpbc'); ?></span>
                                                                </th>
                                                                <td>
                                                                    <select id="available" name="available" style="width:200px;">
                                                        
                                                                        <option value="add_users" <?php echo get_option('wpbc_available_for', 'add_users') == 'add_users' ? 'selected="selected"' : ''; ?>><?php _e('Administrators','wpbc'); ?></option>
                                                                        <option value="publish_posts" <?php echo get_option('wpbc_available_for', '') == 'publish_posts' ? 'selected="selected"' : ''; ?>>>= <?php _e('Editors','wpbc'); ?></option>
                                                                        <option value="edit_posts" <?php echo get_option('wpbc_available_for', '') == 'edit_posts' ? 'selected="selected"' : ''; ?>>>= <?php _e('Authors','wpbc'); ?></option>
                                                                        <option value="delete_posts" <?php echo get_option('wpbc_available_for', '') == 'delete_posts' ? 'selected="selected"' : ''; ?>>>= <?php _e('Contributers','wpbc'); ?></option>
                                                                        <option value="read" <?php echo get_option('wpbc_available_for', '') == 'read' ? 'selected="selected"' : ''; ?>>>= <?php _e('Subscibers','wpbc'); ?></option>
                                                                        
                                                                    </select>
                                                                    <span class="description"><?php _e('','wpbc'); ?></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end .postbox -->
                                        </div>
                                        <!-- end .tuna_meta -->
                                        
                                        <div class="btn_container_with_menu" style="margin-top:40px;">
                                            <input type="submit" value="<?php _e('Save Settings', 'wpbc'); ?>" class="button-primary" name="1" />
                                        </div>
                                    </form>
                                    
                                </div>
                                <!-- end #admin-settings -->
                                <?php
							}
							?>
                            
                            
                        </div>
                        <!-- end .customization_right_in -->
                    </div>
                    <!-- end #tuna_tab_right -->
                </td>
            </tr>
        </table>
        
        
        
	</div> <!-- end #pro_ivc -->
</div> <!-- end .wrap -->