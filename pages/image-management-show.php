<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_vssg2_display']) && $_POST['frm_vssg2_display'] == 'yes')
{
	$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$vssg2_success = '';
	$vssg2_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_VSSGV2_TABLE."
		WHERE `vssg_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'vertical-scroll-slideshow-gallery-v2'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('vssg2_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_VSSGV2_TABLE."`
					WHERE `vssg_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$vssg2_success_msg = TRUE;
			$vssg2_success = __('Selected record was successfully deleted.', 'vertical-scroll-slideshow-gallery-v2');
		}
	}
	
	if ($vssg2_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $vssg2_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Vertical scroll slideshow', 'vertical-scroll-slideshow-gallery-v2'); ?><a class="add-new-h2" href="<?php echo WP_VSSGV2_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'vertical-scroll-slideshow-gallery-v2'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_VSSGV2_TABLE."` order by vssg_type, vssg_order";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_vssg2_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
		  	<th scope="col"><?php _e('Title', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
			<th scope="col"><?php _e('Group', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
			<th scope="col"><?php _e('Image', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
            <th scope="col"><?php _e('URL', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
            <th scope="col"><?php _e('Order', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
            <th scope="col"><?php _e('Display', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
		    <th scope="col"><?php _e('Title', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
			<th scope="col"><?php _e('Group', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
			<th scope="col"><?php _e('Image', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
            <th scope="col"><?php _e('URL', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
            <th scope="col"><?php _e('Order', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
            <th scope="col"><?php _e('Display', 'vertical-scroll-slideshow-gallery-v2'); ?></th>
          </tr>
        </tfoot>
		<tbody>
		<?php 
		$i = 0;
		if(count($myData) > 0 )
		{
			foreach ($myData as $data)
			{
				?>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<td>
					<?php echo esc_html(stripslashes($data['vssg_title'])); ?>
					<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo WP_VSSGV2_ADMIN_URL; ?>&ac=edit&amp;did=<?php echo $data['vssg_id']; ?>"><?php _e('Edit', 'vertical-scroll-slideshow-gallery-v2'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:vssg2_delete('<?php echo $data['vssg_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'vertical-scroll-slideshow-gallery-v2'); ?></a></span> 
					</div>
					</td>
					<td><?php echo esc_html(stripslashes($data['vssg_type'])); ?></td>
					<td><a href="<?php echo esc_html($data['vssg_path']); ?>" target="_blank"><img src="<?php echo plugins_url( 'vertical-scroll-slideshow-gallery-v2/inc/image-icon.png'); ?>"  /></a></td>
					<td>
					<?php if ($data['vssg_link'] <> '#' and $data['vssg_link'] <> '') { ?>
						<a href="<?php echo esc_html($data['vssg_link']); ?>" target="_blank"><img src="<?php echo plugins_url( 'vertical-scroll-slideshow-gallery-v2/inc/link-icon.gif'); ?>"  /></a>
					<?php } else { ?>
						<img src="<?php echo plugins_url( 'vertical-scroll-slideshow-gallery-v2/inc/link-icon.gif'); ?>"  />
					<?php } ?>
					</td>
					<td><?php echo esc_html(stripslashes($data['vssg_order'])); ?></td>
					<td><?php echo esc_html(stripslashes($data['vssg_status'])); ?></td>
				</tr>
				<?php 
				$i = $i+1; 
			} 
		}
		else
		{
			?><tr><td colspan="6" align="center"><?php _e('No records available', 'vertical-scroll-slideshow-gallery-v2'); ?></td></tr><?php 
		}
		?>
		</tbody>
        </table>
		<?php wp_nonce_field('vssg2_form_show'); ?>
		<input type="hidden" name="frm_vssg2_display" value="yes"/>
      </form>	
	  <div class="tablenav bottom">
	  <a href="<?php echo WP_VSSGV2_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'vertical-scroll-slideshow-gallery-v2'); ?>" /></a>
	  <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/vertical-scroll-slideshow-gallery-v2/"><input class="button action" type="button" value="<?php _e('Help', 'vertical-scroll-slideshow-gallery-v2'); ?>" /></a>
	  <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/vertical-scroll-slideshow-gallery-v2/"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'vertical-scroll-slideshow-gallery-v2'); ?>" /></a>
	  </div>
	</div>
</div>