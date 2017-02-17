<?php 

function all_transactions_page_hook() {
	add_submenu_page('edit.php?post_type=price_plan', 'All Transactions', 'All Transactions', 'edit_posts', basename(__FILE__), 'all_transactions_page');
}
add_action('admin_menu',"all_transactions_page_hook");

function all_transactions_page() {
	
	if(isset($_GET['cancel']) && !empty($_GET['trans_id'])) {
		$sql = "update wpcads_paypal set status='cancelled',ads='-1' where main_id=".intval($_GET['trans_id']);
		mysql_query($sql) or die(mysql_error());
	}
	global $wpdb;
	$result = $wpdb->get_results( "SELECT * FROM wpcads_paypal ORDER BY main_id DESC" );
	echo '<div class="wrap">
<h2>All Transactions</h2><br/>';
	if ( $result ) { ?>
    <table class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th class="manage-column" id="title" scope="col" style="width: 20px;"><span><?php esc_html_e( 'ID', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Username', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Plan Name', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Ads', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Used', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Active', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Price', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Status', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Date', 'classify' ); ?></span></th>
				<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Actions', 'classify' ); ?></span></th>
			</tr>
		</thead>
        <tbody id="the-list">
		<?php 

			foreach ( $result as $info ) { 
		?>
        <tr class="type-post" <?php if($info->status == "pending" || $info->status == "in progress" || $info->status == "cancelled") {  ?>style="background: #fce3e3;"<?php } ?>>	<?php  $user_info = get_userdata($info->user_id); ?>
			<td class="manage-column" style="width: 20px;"><span><?php echo  $user_info->ID; ?></span></td>								
            <td class="manage-column price-table-header-name"><span><?php echo  $user_info->user_login; ?></span></td>
            <td class="manage-column price-table-row-name" ><span><?php echo $info->name; ?></span></td>
            <td class="manage-column price-table-row-ads"><span><?php if( empty($info->ads)) { ?> ∞ <?php } else { echo $info->ads; } ?></span></td>
            <td class="manage-column price-table-row-used"><span><?php echo $info->used; ?></span></td>
            <td class="manage-column price-table-row-days"><span><?php if(empty($info->days)) { ?>∞<?php } else { echo $info->days; } ?> Days</span></td>
            <td class="manage-column price-table-row-price"><span><?php echo $info->price; ?> <?php echo $info->currency; ?></span></td>
            <td class="manage-column price-table-row-status"><span <?php if($info->status == "success") {  ?> style="color: #40a000;"<?php } elseif($info->status == "pending") {  ?>style="color: #a02600;"<?php } ?>><?php echo $info->status; ?></span></td>
            <td class="manage-column price-table-row-date"><span><?php echo $info->date; ?></span></td>
			
		<td class="manage-column price-table-row-date"><input type="button" class="btn btn-default button" value="Cancel" onclick=" window.location = './edit.php?post_type=price_plan&page=all_transactions.php&cancel=1&trans_id=<?php echo $info->main_id; ?>';"/></td>
		</tr>  

			<?php 
		} ?>
	</tbody>
    <tfoot>
        <tr>
			<th class="manage-column" id="title" scope="col" style="width: 20px;"><span><?php esc_html_e( 'ID', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Username', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Plan Name', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Ads', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Used', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Active', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Price', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Status', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Date', 'classify' ); ?></span></th>
			<th class="manage-column" id="title" scope="col"><span><?php esc_html_e( 'Actions', 'classify' ); ?></span></th>
		</tr>
	</tfoot>
    </table>
<?php } 
echo '</div>';
}