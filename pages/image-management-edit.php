<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }

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
	$vssg2_errors = array();
	$vssg2_success = '';
	$vssg2_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_VSSGV2_TABLE."`
		WHERE `vssg_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'vssg_path' => $data['vssg_path'],
		'vssg_link' => $data['vssg_link'],
		'vssg_target' => $data['vssg_target'],
		'vssg_title' => $data['vssg_title'],
		'vssg_order' => $data['vssg_order'],
		'vssg_status' => $data['vssg_status'],
		'vssg_type' => $data['vssg_type']
	);
}
// Form submitted, check the data
if (isset($_POST['vssg2_form_submit']) && $_POST['vssg2_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('vssg2_form_edit');
	
	$form['vssg_path'] = isset($_POST['vssg_path']) ? esc_url_raw($_POST['vssg_path']) : '';
	if ($form['vssg_path'] == '')
	{
		$vssg2_errors[] = __('Please enter the image path.', 'vertical-scroll-slideshow-gallery-v2');
		$vssg2_error_found = TRUE;
	}

	$form['vssg_link'] = isset($_POST['vssg_link']) ? esc_url_raw($_POST['vssg_link']) : '';
	if ($form['vssg_link'] == '')
	{
		$vssg2_errors[] = __('Please enter the target link.', 'vertical-scroll-slideshow-gallery-v2');
		$vssg2_error_found = TRUE;
	}
	
	$form['vssg_target'] = isset($_POST['vssg_target']) ? sanitize_text_field($_POST['vssg_target']) : '';
	if($form['vssg_target']!= "_blank" && $form['vssg_target'] != "_parent" && $form['vssg_target'] != "_self" && $form['vssg_target'] != "_new")
	{
		$form['vssg_target'] = "_blank";
	}
		
	$form['vssg_title'] = isset($_POST['vssg_title']) ? sanitize_text_field($_POST['vssg_title']) : '';
	
	$form['vssg_order'] = isset($_POST['vssg_order']) ? intval($_POST['vssg_order']) : '';
	
	$form['vssg_status'] = isset($_POST['vssg_status']) ? sanitize_text_field($_POST['vssg_status']) : '';
	if($form['vssg_status'] != "YES" && $form['vssg_status'] != "NO")
	{
		$form['vssg_status'] = "YES";
	}
		
	$form['vssg_type'] = isset($_POST['vssg_type']) ? sanitize_text_field($_POST['vssg_type']) : '';

	//	No errors found, we can add this Group to the table
	if ($vssg2_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_VSSGV2_TABLE."`
				SET `vssg_path` = %s,
				`vssg_link` = %s,
				`vssg_target` = %s,
				`vssg_title` = %s,
				`vssg_order` = %d,
				`vssg_status` = %s,
				`vssg_type` = %s
				WHERE vssg_id = %d
				LIMIT 1",
				array($form['vssg_path'], $form['vssg_link'], $form['vssg_target'], $form['vssg_title'], $form['vssg_order'], $form['vssg_status'], $form['vssg_type'], $did)
			);
		$wpdb->query($sSql);
		
		$vssg2_success = __('Image details was successfully updated.', 'vertical-scroll-slideshow-gallery-v2');
	}
}

if ($vssg2_error_found == TRUE && isset($vssg2_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $vssg2_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($vssg2_error_found == FALSE && strlen($vssg2_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $vssg2_success; ?> 
		<a href="<?php echo WP_VSSGV2_ADMIN_URL; ?>"><?php _e('Click here', 'vertical-scroll-slideshow-gallery-v2'); ?></a> <?php _e('to view the details', 'vertical-scroll-slideshow-gallery-v2'); ?></strong></p>
	</div>
	<?php
}
?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var img_imageurl = uploaded_image.toJSON().url;
			var img_imagetitle = uploaded_image.toJSON().title;
            // Let's assign the url value to the input field
            $('#vssg_path').val(img_imageurl);
			$('#vssg_title').val(img_imagetitle);
        });
    });
});
</script>
<?php
wp_enqueue_script('jquery'); // jQuery
wp_enqueue_media(); // This will enqueue the Media Uploader script
?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Vertical scroll slideshow', 'vertical-scroll-slideshow-gallery-v2'); ?></h2>
	<form name="vssg2_form" method="post" action="#" onsubmit="return vssg2_submit()"  >
      <h3><?php _e('Update image details', 'vertical-scroll-slideshow-gallery-v2'); ?></h3>
      <label for="tag-image"><?php _e('Enter image path', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
      <input name="vssg_path" type="text" id="vssg_path" value="<?php echo $form['vssg_path']; ?>" size="90" />
	  <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
      <p><?php _e('Select and upload your image.', 'vertical-scroll-slideshow-gallery-v2'); ?></p>
      <label for="tag-link"><?php _e('Enter target link', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
      <input name="vssg_link" type="text" id="vssg_link" value="<?php echo $form['vssg_link']; ?>" size="90" />
      <p><?php _e('When someone clicks on the picture, where do you want to send them', 'vertical-scroll-slideshow-gallery-v2'); ?></p>
      <label for="tag-target"><?php _e('Select target option', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
      <select name="vssg_target" id="vssg_target">
        <option value='_blank' <?php if($form['vssg_target']=='_blank') { echo 'selected' ; } ?>>_blank</option>
        <option value='_parent' <?php if($form['vssg_target']=='_parent') { echo 'selected' ; } ?>>_parent</option>
        <option value='_self' <?php if($form['vssg_target']=='_self') { echo 'selected' ; } ?>>_self</option>
        <option value='_new' <?php if($form['vssg_target']=='_new') { echo 'selected' ; } ?>>_new</option>
      </select>
      <p><?php _e('Do you want to open link in new window?', 'vertical-scroll-slideshow-gallery-v2'); ?></p>
      <label for="tag-title"><?php _e('Enter image title', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
      <input name="vssg_title" type="text" id="vssg_title" value="<?php echo $form['vssg_title']; ?>" size="90" />
      <p><?php _e('Enter image title. This will be the description of your image.', 'vertical-scroll-slideshow-gallery-v2'); ?></p>
      <label for="tag-select-gallery-group"><?php _e('Select gallery group', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
	  <select name="vssg_type" id="vssg_type">
			<option value='Group1' <?php if($form['vssg_type']=='Group1') { echo 'selected' ; } ?>>Group1</option>
			<option value='Group2' <?php if($form['vssg_type']=='Group2') { echo 'selected' ; } ?>>Group2</option>
			<option value='Group3' <?php if($form['vssg_type']=='Group3') { echo 'selected' ; } ?>>Group3</option>
			<option value='Group4' <?php if($form['vssg_type']=='Group4') { echo 'selected' ; } ?>>Group4</option>
			<option value='Group5' <?php if($form['vssg_type']=='Group5') { echo 'selected' ; } ?>>Group5</option>
			<option value='Group6' <?php if($form['vssg_type']=='Group6') { echo 'selected' ; } ?>>Group6</option>
			<option value='Group7' <?php if($form['vssg_type']=='Group7') { echo 'selected' ; } ?>>Group7</option>
			<option value='Group8' <?php if($form['vssg_type']=='Group8') { echo 'selected' ; } ?>>Group8</option>
			<option value='Group9' <?php if($form['vssg_type']=='Group9') { echo 'selected' ; } ?>>Group9</option>
		</select>
      <p><?php _e('This is to group the images. Select your slideshow group.', 'vertical-scroll-slideshow-gallery-v2'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
      <select name="vssg_status" id="vssg_status">
        <option value='YES' <?php if($form['vssg_status']=='YES') { echo 'selected' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['vssg_status']=='NO') { echo 'selected' ; } ?>>No</option>
      </select>
      <p><?php _e('Do you want the picture to show in your galler?', 'vertical-scroll-slideshow-gallery-v2'); ?></p>
      <label for="tag-display-order"><?php _e('Display order', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
      <input name="vssg_order" type="text" id="vssg_order" size="10" value="<?php echo $form['vssg_order']; ?>" maxlength="3" />
      <p><?php _e('What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.', 'vertical-scroll-slideshow-gallery-v2'); ?></p>
      <input name="vssg_id" id="vssg_id" type="hidden" value="">
      <input type="hidden" name="vssg2_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button action" value="<?php _e('Submit', 'vertical-scroll-slideshow-gallery-v2'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button action" onclick="vssg2_redirect()" value="<?php _e('Cancel', 'vertical-scroll-slideshow-gallery-v2'); ?>" type="button" />
        <input name="Help" lang="publish" class="button action" onclick="vssg2_help()" value="<?php _e('Help', 'vertical-scroll-slideshow-gallery-v2'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('vssg2_form_edit'); ?>
    </form>
</div>
</div>