<?php
/*
Plugin Name: 5280 Bootstrap Modal Contact Form
Plugin URI: https://github.com/5280studios/5280-bootstrap-modal-contact-form
Description: Easily add a pop-up contact form to your website. 
Version: 1.0
Author: 5280Studios
Author URI: http://5280studios.com
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl.html
*/

defined( 'ABSPATH' ) or die( 'Sorry. No unauthorized access!' );

if (!class_exists('SimpleBmcf_Wp')) {	

// main class

	class SimpleBmcf_Wp {

function __construct() {
require_once(plugin_dir_path( __FILE__ ) . 'inc/class-wp-list-tablesbmm.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/class-sbmm-list-table.php');
register_activation_hook(__FILE__, array($this,'sbmcf_activate'));
add_action('wp_ajax_sbmcf_submit', array($this,'sbmcf_submitted'));
add_action('wp_ajax_nopriv_sbmcf_submit', array($this,'sbmcf_submitted'));
add_action('wp_enqueue_scripts', array($this,'load_scripts_sbmcf'));
add_action('admin_menu', array($this,'sbmcf_admin_menu'));
add_shortcode('sbmcf', array($this,'sbmcf_add_shortcode'));
add_action( 'admin_init', array($this,'sbmcf_setup_sections'));
add_action('admin_enqueue_scripts', array($this,'load_scripts_admin_sbmcf'));
}

function load_scripts_sbmcf() {
wp_enqueue_script('sbmcf-javascript', plugins_url('js/javascript.js', __FILE__ ), array('jquery'));
wp_localize_script('sbmcf-javascript','ajax_object',array('ajax_url' => admin_url('admin-ajax.php'), 'sbmm_value' => 1234));
}

function load_scripts_admin_sbmcf() {
wp_enqueue_script('sbmcf-javascript-admin', plugins_url('js/admin.js', __FILE__ ), array('jquery'));
wp_register_style( 'sbmcfadmincss', plugins_url('css/modal.css', __FILE__ ));
wp_enqueue_style('sbmcfadmincss');
}

function sbmcf_submitted() {

	if (isset($_POST['psbmcf_n'])) {
		$sbmm_name_restrict = strip_tags(trim($_POST['psbmcf_n']));
        $sbmm_name = substr($sbmm_name_restrict,0,50);

		if ($sbmm_name == '') {
         $sbmm_name = 'left blank';
		}
	}
	else {$sbmm_name = 'left blank';}

	if (isset($_POST['psbmcf_e'])) {
		$sbmm_email_restrict = strip_tags(trim($_POST['psbmcf_e']));
		$sbmm_email = substr($sbmm_email_restrict,0,50);
		if ($sbmm_email == '') {
         $sbmm_email = 'left blank';
		}
	}
	else {$sbmm_email = 'left blank';}

		if (isset($_POST['psbmcf_s'])) {
		$sbmm_subject_restrict = strip_tags(trim($_POST['psbmcf_s']));
		$sbmm_subject = substr($sbmm_subject_restrict,0,100);
		if ($sbmm_subject == '') {
         $sbmm_subject = 'left blank';
		}
	}
	else {$sbmm_subject = 'left blank';}

     if (isset($_POST['psbmcf_m'])) {
		$sbmm_message_restrict = strip_tags(trim($_POST['psbmcf_m']));
		$sbmm_message = substr($sbmm_message_restrict,0,750);
		if ($sbmm_message == '') {
         $sbmm_message = 'left blank';
		}
	}
	else {$sbmm_message = 'left blank';}

	$sbmm_modal_id = 0;

    // version [free]
	$sbmm_version = 1;

	global $wpdb;
	
	$table_name = $wpdb->prefix . 'sbmcf_messages';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'sbmcftime' => current_time('mysql'), 
			'sbmcfname' => $sbmm_name, 
			'sbmcfemail' => $sbmm_email, 
			'sbmcfsubject' => $sbmm_subject,
			'sbmcfmessage' => $sbmm_message,
			'sbmcfmodalid' => $sbmm_modal_id,
			'sbmcfversion' => $sbmm_version
		) 
	);

wp_die(); // terminate required for AJAX

}


function sbmcf_admin_menu() {
add_menu_page( 'Bootstrap Modal Contact Form', 'Bootstrap Modal Contact Form',
 'manage_options', 'sbmcf', array($this,'sbmcf_admin_page'), 'dashicons-testimonial', 6);}

function sbmcf_admin_page(){
	  // Create an instance of our package class...
    $sbmcf_list_table = new Sbmcf_List_Table();
     // Fetch, prepare, sort, and filter our data...
    $sbmcf_list_table->prepare_items();
	?>
	<div class="wrap">
        <h2>Bootstrap Modal Contact Form</h2>
        <form method="post" action="options.php" id="sbmcf_form1">
    <?php
    settings_fields('sbmcf_modal_group');
    do_settings_sections('sbmcf_modal_group');
    submit_button();
    ?>
        </form>
    </div>
    <div class="wrap">
   <h2>Messages</h2>
    <form method="get" id="sbmcf_form2">
      <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
      <?php $sbmcf_list_table->display() ?>
    </form>
    </div>
	<?php
}

function sbmcf_setup_sections() {

register_setting( 'sbmcf_modal_group', 'sbmcf_modal_group_name' );

add_settings_section( 'sbmcf_modal_type_section', '', array($this,'sbmcf_modal_default'), 'sbmcf_modal_group' );
   
add_settings_field(
	'sbmcf_modal_choice_1',
	'Choose one:',
	array($this,'sbmcf_modal_choice_1r'),
	'sbmcf_modal_group',
	'sbmcf_modal_type_section'
	);

add_settings_field(
	'sbmcf_modal_choice_custom',
	'Custom:',
	array($this,'sbmcf_modal_custom_r'),
	'sbmcf_modal_group',
	'sbmcf_modal_type_section'
	);

add_settings_field(
	'sbmcf_modal_choice_title',
	'Form title:',
	array($this,'sbmcf_modal_title_r'),
	'sbmcf_modal_group',
	'sbmcf_modal_type_section'
	);

add_settings_field(
	'sbmcf_modal_choice_btncolor',
	'Button color:',
	array($this,'sbmcf_modal_btncolor_r'),
	'sbmcf_modal_group',
	'sbmcf_modal_type_section'
	);

add_settings_field(
	'sbmcf_modal_choice_btntxclr',
	'Button text color:',
	array($this,'sbmcf_modal_btntxclr_r'),
	'sbmcf_modal_group',
	'sbmcf_modal_type_section'
	);

}

function sbmcf_modal_default() {
echo '<p>Paste the following shortcode <span class="sbmm_orange"><strong>[sbmcf]</strong></span> where you wish to display the button that triggers the pop-up contact form.</p>';
}

function sbmcf_modal_choice_1r() {
$my_sbmm_option = get_option('sbmcf_modal_group_name'); 
$sbmcf_d = 1;
$sbmcf_c = 2;
?>
<td><div class="sbmcf_radio"><input type="radio" id="sbmcf_radio1" name="sbmcf_modal_group_name[sbmcf_modal_choice_1]" value="1" <?php checked($sbmcf_d, $my_sbmm_option['sbmcf_modal_choice_1'], true); ?>>Default modal</div>
<div class="sbmcf_radio"><input type="radio" id="sbmcf_radio2" name="sbmcf_modal_group_name[sbmcf_modal_choice_1]" value="2" <?php checked($sbmcf_c, $my_sbmm_option['sbmcf_modal_choice_1'], true); ?>>Custom modal</div></td>
<?php
}

function sbmcf_modal_custom_r() {
$my_sbmm_option = get_option('sbmcf_modal_group_name'); 
?>
<td><textarea id="sbmcf_textarea_cust" cols="50" rows="5" name="sbmcf_modal_group_name[sbmcf_modal_choice_custom]"><?php echo esc_textarea($my_sbmm_option['sbmcf_modal_choice_custom']); ?></textarea></td>
<?php	
}

function sbmcf_modal_title_r() {
$my_sbmm_option = get_option('sbmcf_modal_group_name'); 
?>
<td><input id="sbmcf_input_ft" type="text" name="sbmcf_modal_group_name[sbmcf_modal_choice_title]" value="<?php echo esc_attr($my_sbmm_option['sbmcf_modal_choice_title']); ?>" maxlength="50" /></td>
<?php
}

function sbmcf_modal_btncolor_r() {
$my_sbmm_option = get_option('sbmcf_modal_group_name'); 
?>
<td><input id="sbmcf_input_btnclr" type="text" name="sbmcf_modal_group_name[sbmcf_modal_choice_btncolor]" value="<?php echo esc_attr($my_sbmm_option['sbmcf_modal_choice_btncolor']); ?>" maxlength="7" /></td>
<?php
}

function sbmcf_modal_btntxclr_r() {
$my_sbmm_option = get_option('sbmcf_modal_group_name'); 
?>
<td><input id="sbmcf_input_txclr" type="text" name="sbmcf_modal_group_name[sbmcf_modal_choice_btntxclr]" value="<?php echo esc_attr($my_sbmm_option['sbmcf_modal_choice_btntxclr']); ?>" maxlength="7" /></td>
<?php
}

function sbmcf_activate() {
// set default options
$sbmcfdefaults = array (
                'sbmcf_modal_choice_1' => '1',
                'sbmcf_modal_choice_custom' => '',
                'sbmcf_modal_choice_title' => '',
                'sbmcf_modal_choice_btncolor' => '#007bff',
                'sbmcf_modal_choice_btntxclr' => '#ffffff'
            );

$mytheme_settings = get_option('sbmcf_modal_group_name');

if($mytheme_settings === false) {
   update_option('sbmcf_modal_group_name', $sbmcfdefaults);
}


    global $wpdb;	

	$table_name = $wpdb->prefix . 'sbmcf_messages';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		ID mediumint(9) NOT NULL AUTO_INCREMENT,
		sbmcftime datetime NULL,
	    sbmcfname varchar(50) NULL,
		sbmcfemail varchar(50) NULL,
		sbmcfsubject varchar(100) NULL,
		sbmcfmessage longtext NULL,
		sbmcfversion mediumint(9) NULL,
		sbmcfmodalid mediumint(9) NULL,
		PRIMARY KEY  (ID)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta( $sql );
	
}



function sbmcf_add_shortcode($atts) {
	
$my_sbmcf_options = get_option('sbmcf_modal_group_name');

if ($my_sbmcf_options['sbmcf_modal_choice_1'] == 2) {
	ob_start();
echo $my_sbmcf_options['sbmcf_modal_choice_custom'];
$output = ob_get_clean();
return $output;
}
else {
$my_title = $my_sbmcf_options['sbmcf_modal_choice_title'];
if ($my_title == '') {$my_title = 'Contact Us';}
$my_color = $my_sbmcf_options['sbmcf_modal_choice_btncolor'];
$my_t_color = $my_sbmcf_options['sbmcf_modal_choice_btntxclr'];
ob_start();
include(plugin_dir_path( __FILE__ ) . 'inc/modal.php');
$output = ob_get_clean();
return $output;
}

}

// end class
}

new SimpleBmcf_Wp();

// end if
}