<?php
/*
Plugin Name: Vertical scroll slideshow gallery v2
Plugin URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-slideshow-gallery-v2/
Description:  Vertical scroll slideshow gallery plugin will create the vertical scrolling image slideshow gallery on the wordpress widget.
Author: Gopi Ramasamy
Version: 9.1
Author URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-slideshow-gallery-v2/
Donate link: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-slideshow-gallery-v2/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vertical-scroll-slideshow-gallery-v2
Domain Path: /languages
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("WP_VSSGV2_TABLE", $wpdb->prefix . "vssgv_gallery");

if ( ! defined( 'WP_VSSGV2_ADMIN_URL' ) )
	define( 'WP_VSSGV2_ADMIN_URL', admin_url() . 'options-general.php?page=vertical-scroll-slideshow-gallery-v2' );


add_shortcode( 'vertical-scroll-slideshow-gallery', 'vssg2_shortcode' );

function vssg2_shortcode($atts) 
{
	$vs2 = "";
	
	//[vertical-scroll-slideshow-gallery group="Group1" width="250" height="165" time="3000" random="YES"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	
	$group 	= isset($atts['group']) ? $atts['group'] : 'group1';
	$width 	= isset($atts['width']) ? $atts['width'] : '500';
	$height = isset($atts['height']) ? $atts['height'] : '300';
	$time 	= isset($atts['width']) ? $atts['time'] : '3000';
	$random = isset($atts['random']) ? $atts['random'] : 'YES';
	
	if(!is_numeric($width)){
		$width = 500;
	} 
	
	if(!is_numeric($height)){
		$height = 300;
	} 
	
	if(!is_numeric($time)){
		$time = 3000;
	} 
	
	$sSql = "select vssg_path, vssg_link, vssg_target, vssg_title from ".WP_VSSGV2_TABLE." where vssg_status='YES' ";
	if($group <> ""){ 
		$sSql = $sSql . " and vssg_type='".$group."'";
	}
	
	if($random == "YES"){ 
		$sSql = $sSql . " ORDER BY RAND()"; 
	}
	else { 
		$sSql = $sSql . " ORDER BY vssg_order"; 
	}
	
	global $wpdb;
	$data = $wpdb->get_results($sSql);

	if ( ! empty($data) ) 
	{
		$vs2_str = "";
		$vs2_count = 0;
		$plugin_script_js = plugins_url( 'script.js', __FILE__ );
		
		foreach ( $data as $data ) 
		{
			$vs2_str = $vs2_str . "vs2_slideimages[$vs2_count]='<a href=\'$data->vssg_link\' target=\'$data->vssg_target\'><img src=\'$data->vssg_path\' title=\'$data->vssg_title\' alt=\'$data->vssg_title\' border=\'0\'></a>'; ";
	  		$vs2_count++;
		}

		$vs2 = $vs2 . '<script language="JavaScript1.2">';
		$vs2 = $vs2 . "var vs2_scrollerwidth='".$width."px';";
		$vs2 = $vs2 . "var vs2_scrollerheight='".$height."px';";
		$vs2 = $vs2 . 'var vs2_pausebetweenimages='.$time.';';
		$vs2 = $vs2 . 'var vs2_slideimages=new Array();';
		$vs2 = $vs2 . $vs2_str;
		$vs2 = $vs2 . '</script>';
		$vs2 = $vs2 . '<script src="'.$plugin_script_js.'"></script>';
		
		$vs2 = $vs2 . '<ilayer id="vs2_main" width=&{vs2_scrollerwidth}; height=&{vs2_scrollerheight}; visibility=hide>';
		$vs2 = $vs2 . '<layer id="vs2_first" width=&{vs2_scrollerwidth};>';
		$vs2 = $vs2 . '<script language="JavaScript1.2">';
		$vs2 = $vs2 . 'if (document.layers)';
		$vs2 = $vs2 . 'document.write(vs2_slideimages[0]);';
		$vs2 = $vs2 . '</script>';
		$vs2 = $vs2 . '</layer>';
		$vs2 = $vs2 . '<layer id="vs2_second" width=&{vs2_scrollerwidth}; visibility=hide>';
		$vs2 = $vs2 . '<script language="JavaScript1.2">';
		$vs2 = $vs2 . 'if (document.layers)';
		$vs2 = $vs2 . 'document.write(vs2_slideimages[dyndetermine=(vs2_slideimages.length==1)? 0 : 1]);';
		$vs2 = $vs2 . '</script>';
		$vs2 = $vs2 . '</layer>';
		$vs2 = $vs2 . '</ilayer>';

		$vs2 = $vs2 . ' <script language="JavaScript1.2"> ';
		$vs2 = $vs2 . ' if (ie||dom) ';
		$vs2 = $vs2 . ' { ';
			$vs2 = $vs2 . ' document.writeln(\'<div style="padding:8px 0px 8px 0px;">\'); ';
			$vs2 = $vs2 . ' document.writeln(\'<div id="vs2_main2" style="position:relative;width:\'+vs2_scrollerwidth+\';height:\'+vs2_scrollerheight+\';overflow:hidden;">\'); ';
			$vs2 = $vs2 . ' document.writeln(\'<div style="position:absolute;width:\'+vs2_scrollerwidth+\';height:\'+vs2_scrollerheight+\';clip:rect(0 \'+vs2_scrollerwidth+\' \'+vs2_scrollerheight+\' 0);">\'); ';
			$vs2 = $vs2 . ' document.writeln(\'<div id="vs2_first2" style="position:absolute;width:\'+vs2_scrollerwidth+\';left:0px;top:1px;">\'); ';
			$vs2 = $vs2 . ' document.write(vs2_slideimages[0]); ';
			$vs2 = $vs2 . " document.writeln('</div>'); ";
			$vs2 = $vs2 . ' document.writeln(\'<div id="vs2_second2" style="position:absolute;width:\'+vs2_scrollerwidth+\';visibility:hidden">\'); ';
			$vs2 = $vs2 . ' document.write(vs2_slideimages[dyndetermine=(vs2_slideimages.length==1)? 0 : 1]); ';
			$vs2 = $vs2 . " document.writeln('</div>'); ";
			$vs2 = $vs2 . " document.writeln('</div>'); ";
			$vs2 = $vs2 . " document.writeln('</div>'); ";
			$vs2 = $vs2 . " document.writeln('</div>'); ";
		$vs2 = $vs2 . ' } ';
		$vs2 = $vs2 . ' </script> ';
	}
	else
	{
		$vs2 = __( 'No records found, please check your short code' , 'vertical-scroll-slideshow-gallery-v2');
	}
		
	return $vs2;
}

function vssg2_option() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/image-management-edit.php');
			break;
		case 'add':
			include('pages/image-management-add.php');
			break;
		case 'set':
			include('pages/image-setting.php');
			break;
		default:
			include('pages/image-management-show.php');
			break;
	}
}

function vssg2_add_to_menu() 
{
	add_options_page( __('Vertical Scroll Slideshow Gallery V2', 'vertical-scroll-slideshow-gallery-v2'), 
			__('Vertical Scroll Slideshow Gallery V2', 'vertical-scroll-slideshow-gallery-v2'), 'manage_options', 'vertical-scroll-slideshow-gallery-v2', 'vssg2_option' );
}

class vssg2_cls_widget {
	public static function vssg2_load($atts) {
		
		if ( ! is_array( $atts ) )
		{
			return '';
		}
	
		$width 	= isset($atts['width']) ? $atts['width'] : '';
		$height = isset($atts['height']) ? $atts['height'] : '';
		$time 	= isset($atts['time']) ? $atts['time'] : '';
		$group 	= isset($atts['group']) ? $atts['group'] : '';
		
		if(!is_numeric($width)) {
			$width = 250;
		}
		
		if(!is_numeric($height)) {
			$height = 167;
		}
		
		if(!is_numeric($time)) {
			$time = 3000;
		}
		
		echo vssg2_shortcode($atts);
		
	}
}

class vssg2_widget_register extends WP_Widget 
{
	function __construct() 
	{
		$widget_ops = array('classname' => 'widget_text hsas-widget', 'description' => __('Vertical scroll slideshow gallery v2', 'vertical-scroll-slideshow-gallery-v2'), 'vertical-scroll-slideshow-gallery-v2');
		parent::__construct('vertical-scroll-slideshow-gallery-v2', __('Vertical scroll slideshow gallery v2', 'vertical-scroll-slideshow-gallery-v2'), $widget_ops);
	}
	
	function widget( $args, $instance ) 
	{
		extract( $args, EXTR_SKIP );
		
		$vssg2_title 	= apply_filters( 'widget_title', empty( $instance['vssg2_title'] ) ? '' : $instance['vssg2_title'], $instance, $this->id_base );
		$vssg2_width	= $instance['vssg2_width'];
		$vssg2_height	= $instance['vssg2_height'];
		$vssg2_time		= $instance['vssg2_time'];
		$vssg2_group	= $instance['vssg2_group'];
		
		echo $args['before_widget'];
		
		if ( ! empty( $vssg2_title ) )
		{
			echo $args['before_title'] . $vssg2_title . $args['after_title'];
		}
		
		// Call widget method
		$arr = array();
		$arr["width"] 		= $vssg2_width;
		$arr["height"] 		= $vssg2_height;
		$arr["time"] 		= $vssg2_time;
		$arr["group"] 		= $vssg2_group;
		echo vssg2_cls_widget::vssg2_load($arr);
		
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) 
	{
		$instance 					= $old_instance;
		$instance['vssg2_title'] 	= ( ! empty( $new_instance['vssg2_title'] ) ) ? strip_tags( $new_instance['vssg2_title'] ) : '';
		$instance['vssg2_width'] 	= ( ! empty( $new_instance['vssg2_width'] ) ) ? strip_tags( $new_instance['vssg2_width'] ) : '';
		$instance['vssg2_height'] 	= ( ! empty( $new_instance['vssg2_height'] ) ) ? strip_tags( $new_instance['vssg2_height'] ) : '';
		$instance['vssg2_time'] 	= ( ! empty( $new_instance['vssg2_time'] ) ) ? strip_tags( $new_instance['vssg2_time'] ) : '';
		$instance['vssg2_group'] 	= ( ! empty( $new_instance['vssg2_group'] ) ) ? strip_tags( $new_instance['vssg2_group'] ) : '';
		return $instance;
	}
	
	function form( $instance ) 
	{
		$defaults = array(
			'vssg2_title' 	=> '',
            'vssg2_width' 	=> '',
            'vssg2_height' 	=> '',
			'vssg2_time'	=> '',
			'vssg2_group'  => ''
        );
		$instance 			= wp_parse_args( (array) $instance, $defaults);
		$vssg2_title 		= $instance['vssg2_title'];
        $vssg2_width 		= $instance['vssg2_width'];
        $vssg2_height 		= $instance['vssg2_height'];
		$vssg2_time 		= $instance['vssg2_time'];
		$vssg2_group 		= $instance['vssg2_group'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('vssg2_title'); ?>"><?php _e('Widget Title', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('vssg2_title'); ?>" name="<?php echo $this->get_field_name('vssg2_title'); ?>" type="text" value="<?php echo $vssg2_title; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('vssg2_width'); ?>"><?php _e('Width in px (Enter only number)', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('vssg2_width'); ?>" name="<?php echo $this->get_field_name('vssg2_width'); ?>" type="text" maxlength="20" value="<?php echo $vssg2_width; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('vssg2_height'); ?>"><?php _e('Height in px (Enter only number)', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('vssg2_height'); ?>" name="<?php echo $this->get_field_name('vssg2_height'); ?>" type="text" maxlength="20" value="<?php echo $vssg2_height; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('vssg2_time'); ?>"><?php _e('Time (Enter only number)', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('vssg2_time'); ?>" name="<?php echo $this->get_field_name('vssg2_time'); ?>" type="text" maxlength="20" value="<?php echo $vssg2_time; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('vssg2_group'); ?>"><?php _e('Group ', 'vertical-scroll-slideshow-gallery-v2'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('vssg2_group'); ?>" name="<?php echo $this->get_field_name('vssg2_group'); ?>">
				<option value="Group1" <?php $this->hsas_selected($vssg2_group == 'Group1'); ?>>Group1</option>
				<option value="Group2" <?php $this->hsas_selected($vssg2_group == 'Group2'); ?>>Group2</option>
				<option value="Group3" <?php $this->hsas_selected($vssg2_group == 'Group3'); ?>>Group3</option>
				<option value="Group4" <?php $this->hsas_selected($vssg2_group == 'Group4'); ?>>Group4</option>
				<option value="Group5" <?php $this->hsas_selected($vssg2_group == 'Group5'); ?>>Group5</option>
				<option value="Group6" <?php $this->hsas_selected($vssg2_group == 'Group6'); ?>>Group6</option>
				<option value="Group7" <?php $this->hsas_selected($vssg2_group == 'Group7'); ?>>Group7</option>
				<option value="Group8" <?php $this->hsas_selected($vssg2_group == 'Group8'); ?>>Group8</option>
				<option value="Group9" <?php $this->hsas_selected($vssg2_group == 'Group9'); ?>>Group9</option>
			</select>
        </p>
		<?php
	}
	
	function hsas_selected($var) 
	{
		if ($var==1 || $var==true) 
		{
			echo 'selected="selected"';
		}
	}
}

class vssg2_cls_registerhook {
	public static function vssg2_activation() {
		$pluginsurl = plugins_url( 'images', __FILE__ );
		global $wpdb;
		if($wpdb->get_var("show tables like '". WP_VSSGV2_TABLE . "'") != WP_VSSGV2_TABLE) 
		{
			$sSql = "CREATE TABLE IF NOT EXISTS `". WP_VSSGV2_TABLE . "` (";
			$sSql = $sSql . "`vssg_id` INT NOT NULL AUTO_INCREMENT ,";
			$sSql = $sSql . "`vssg_path` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
			$sSql = $sSql . "`vssg_link` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
			$sSql = $sSql . "`vssg_target` VARCHAR( 50 ) NOT NULL ,";
			$sSql = $sSql . "`vssg_title` VARCHAR( 1024 ) NOT NULL ,";
			$sSql = $sSql . "`vssg_order` INT NOT NULL ,";
			$sSql = $sSql . "`vssg_status` VARCHAR( 10 ) NOT NULL ,";
			$sSql = $sSql . "`vssg_type` VARCHAR( 100 ) NOT NULL ,";
			$sSql = $sSql . "`vssg_date` INT NOT NULL ,";
			$sSql = $sSql . "PRIMARY KEY ( `vssg_id` )";
			$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			$wpdb->query($sSql);
			$sSql = "INSERT INTO `". WP_VSSGV2_TABLE . "` (vssg_path, vssg_link, vssg_target, vssg_title, vssg_order, vssg_status, vssg_type, vssg_date)"; 
			$sSql = $sSql . "VALUES ('".$pluginsurl."/250x167_1.jpg','#','_parent','Image 1', '1', 'YES', 'Group1', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
			$sSql = "INSERT INTO `". WP_VSSGV2_TABLE . "` (vssg_path, vssg_link, vssg_target, vssg_title, vssg_order, vssg_status, vssg_type, vssg_date)"; 
			$sSql = $sSql . "VALUES ('".$pluginsurl."/250x167_2.jpg','#','_parent','Image 2', '2', 'YES', 'Group1', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
			$sSql = "INSERT INTO `". WP_VSSGV2_TABLE . "` (vssg_path, vssg_link, vssg_target, vssg_title, vssg_order, vssg_status, vssg_type, vssg_date)"; 
			$sSql = $sSql . "VALUES ('".$pluginsurl."/500x300_1.jpg','#','_parent','Image 3', '3', 'YES', 'Group2', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
			$sSql = "INSERT INTO `". WP_VSSGV2_TABLE . "` (vssg_path, vssg_link, vssg_target, vssg_title, vssg_order, vssg_status, vssg_type, vssg_date)"; 
			$sSql = $sSql . "VALUES ('".$pluginsurl."/500x300_2.jpg','#','_parent','Image 4', '4', 'YES', 'Group2', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
		}
	}	
	
	public static function vssg2_deactivation() {
		//No action
	}
	
	public static function vssg2_widget_loading() {
		register_widget( 'vssg2_widget_register' );
	}
}

function vssg2_textdomain() 
{
	  load_plugin_textdomain( 'vertical-scroll-slideshow-gallery-v2', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function vssg2_adminscripts() 
{
	if( !empty( $_GET['page'] ) ) 
	{
		switch ( $_GET['page'] ) 
		{
			case 'vertical-scroll-slideshow-gallery-v2':
				wp_register_script( 'vssg2-adminscripts', plugins_url( 'pages/setting.js', __FILE__ ), '', '', true );
				wp_enqueue_script( 'vssg2-adminscripts' );
				$vssg2_select_params = array(
					'vssg_path'   	=> __( 'Please select and upload your image.', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
					'vssg_link'   	=> __( 'Please enter the target link.', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
					'vssg_target' 	=> __( 'Please select the target option.', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
					'vssg_title' 	=> __( 'Please enter the image title.', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
					'vssg_order'  	=> __( 'Please enter the display order, only number.', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
					'vssg_status' 	=> __( 'Please select the display status.', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
					'vssg_type'  	=> __( 'Please select the gallery type.', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
					'vssg_delete'	=> __( 'Do you want to delete this record?', 'vssg-select', 'vertical-scroll-slideshow-gallery-v2' ),
				);
				wp_localize_script( 'vssg2-adminscripts', 'vssg2_adminscripts', $vssg2_select_params );
				break;
		}
	}
}

add_action('admin_menu', 'vssg2_add_to_menu');
add_action('plugins_loaded', 'vssg2_textdomain');
register_activation_hook( __FILE__, array( 'vssg2_cls_registerhook', 'vssg2_activation' ) );
register_deactivation_hook( __FILE__, array( 'vssg2_cls_registerhook', 'vssg2_deactivation' ) );
add_action( 'widgets_init', array( 'vssg2_cls_registerhook', 'vssg2_widget_loading' ));
add_action( 'admin_enqueue_scripts', 'vssg2_adminscripts' );
?>