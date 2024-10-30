<?php

// DELETE SHORTCODE
if (isset($_GET['delete_shortcode_key'])){
	$cbw_deleteShortcodeId 		= sanitize_text_field($_GET['delete_shortcode_key']);
	$cbw_shortcodesRawData 		= get_option('cbw_shortcodes_data');
	$cbw_shortcodesData			= json_decode($cbw_shortcodesRawData, true);
	unset($cbw_shortcodesData[$cbw_deleteShortcodeId]);
	$cbw_updateshortcodesData	= json_encode($cbw_shortcodesData);
	update_option('cbw_shortcodes_data',$cbw_updateshortcodesData);
	$cbw_shortcodes_msg 		= 'Shortcode Deleted';
	$cbw_shortcodes_msg_status 	= 'updated';

	$url = site_url( '/wp-admin/admin.php?page=vc_list_shortcode' );
	echo'<script> window.location="'.esc_url($url).'"; </script> ';
}

$cbw_shortcodesRawData 	= get_option('cbw_shortcodes_data');
$cbw_shortcodesData		= json_decode($cbw_shortcodesRawData, true);

?>
<?php if (!empty($cbw_shortcodes_msg)):?>
    <div class="<?php echo $shortcodes_msg_status; ?>" id="message"><p><?php echo esc_html($cbw_shortcodes_msg); ?></p></div>
<?php endif; ?>

<table class="wp-list-table">
    <thead>
        <tr>
            <th>         	
                <div style="float:right;"><a class="add-new-h2 notop" href="admin.php?page=vc_create_shortcodes">Add New Layout</a> </div>             
            </th>           
        </tr>
    </thead>
</table>

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Shortcodes</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<table class="widefat fixed" cellspacing="0">
                <thead>
                    <tr>
                        <th width="20"><strong>#</strong></th>
                        <th width="250"><strong>ShortCode Name</strong></th>
                        <th><strong>Shortcode</strong></th>
                        <th width="100"><strong>Actions</strong></th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if (!empty($cbw_shortcodesData)): ?>
                    <?php 
                    $id = 0;
                    foreach ($cbw_shortcodesData as $key=>$cbw_shortcodeData):
					$id++;
                    $pagination = $cbw_shortcodeData['vc_sg_pagination_style'];
                    ?>
                    <tr <?php echo $id % 2 == 0?'':'class="alternate"'; ?>>
                        <td><?php echo esc_html($id); ?></td>
                        <td><?php echo esc_html($cbw_shortcodeData['vc_sg_shortcode_name']); ?></td>
                        <td>
                        	<input type="text" class="" value='[vcpost name="<?php echo esc_html($cbw_shortcodeData['vc_sg_shortcode_name']) ?>" id="<?php echo esc_html($key); ?>"]' readonly="readonly" onfocus="this.select();" style="width:80%;">
                        </td>
                        <td><a href="admin.php?page=vc_create_shortcodes&edit_shortcode_key=<?php echo esc_html($key); ?>">Edit</a> | <a onclick="if (!confirm('Are you sure ?')){return false;}" href="admin.php?page=vc_list_shortcode&delete_shortcode_key=<?php echo esc_html($key); ?>">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4">No Shortcode Available.</td>
                    </tr>
                    <?php endif; ?>        
                </tbody>
                
            </table>
        </td>
     </tr>
  </tbody>
</table>