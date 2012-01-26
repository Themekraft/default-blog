<?php
/*
Plugin Name: Default Blog
Plugin URI: http://wordpress.org/extend/plugins/default-blog-options/
Description: Create new blogs with values like Posts, Pages, Theme settings, Blog options ... from a default blog made by you.
Author: Sven Lehnert, Sven Wagener
Author URI: http://www.rheinschmiede.de
Version: 0.4.1
License: (GNU General Public License 3.0 (GPL) http://www.gnu.org/licenses/gpl.html)
Copyright: Sven Wagener
*/

/**********************************************************************
This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
***********************************************************************/

$defblog_name=__('Default Blog','default-blog-options');

$defblog_plugin_path=dirname(__FILE__);
include($defblog_plugin_path."/functions.inc.php");
include($defblog_plugin_path."/ui/functions_layout.inc.php");
include($defblog_plugin_path."/lib/io.inc.php");
include($defblog_plugin_path."/admin/admin.php");
if(file_exists($defblog_plugin_path."/extended.inc.php")){include($defblog_plugin_path."/extended.inc.php");}else{include($defblog_plugin_path."/standard.inc.php");}

global $defblog_name;
global $defblog_plugin_path;
global $defblog_settings;
global $defblog_templates;

global $cat_changes;

// Getting settings for plugin
defblog_vars();

// Installing plugin
register_activation_hook(__FILE__,'initialize_plugin');

// Adding menue if WP main blog is active
add_action('admin_head','defblog_css');
add_action('admin_head','ajaxui_css');
add_action('admin_menu', 'add_blog_menue_page');
add_action('wpmu_new_blog', 'initialise_blog');
add_action('init','ajaxui_js');

$plugin_dir = basename(dirname(__FILE__))."/lang/";
load_plugin_textdomain( 'default-blog-options', 'wp-content/plugins/' . $plugin_dir, $plugin_dir );

// Add a new top-level menu 
function add_blog_menue_page() {
	add_submenu_page('wpmu-admin.php', 'Default Blog', 'Default Blog', 10, 'defaultblog', 'default_blog');
}

// Saving settings from admin form
function default_blog(){
	global $defblog_settings, $defblog_templates, $extended;
	
	// Save Default Blog ID
	if (isset($_POST['defblog_submit'])) {
		$defblog_id=$_POST['act_defblog_id'];
		
		setup_template_id($defblog_id);
		
		$template_id=get_template_id();
		
		if($defblog_templates[$template_id]['id']!=$_POST['act_defblog_id']){
		
			$defblog_settings['act_template_id']=$template_id;
			update_defblog_settings($defblog_settings);
					
			// if($defblog_templates[$template_id]['id']==""){
			if($extended!=true){
				$defblog_templates[$template_id]="";
			}
			$defblog_templates[$template_id]['id']=$defblog_id;
			update_defblog_templates($defblog_templates);
			//}
			
			defblog_vars();
	
			alert(__('Default Blog ID Saved!','default-blog-options'));
		}
	}
	// Save Default Blog Options
	if (isset($_POST['submit'])){
		
		$template_id=get_template_id();
		$defblog_templates[$template_id]['id']=$_POST['act_defblog_id'];
				
		$defblog_templates[$template_id]['posts']=$_POST['posts'];
		$defblog_templates[$template_id]['del_posts']=$_POST['delete_existing_posts'];
		
		$defblog_templates[$template_id]['pages']=$_POST['pages'];
		$defblog_templates[$template_id]['del_pages']=$_POST['delete_existing_pages'];
		
		$defblog_templates[$template_id]['links']=$_POST['links'];
		$defblog_templates[$template_id]['del_links']=$_POST['delete_existing_links'];		
		
		$defblog_templates[$template_id]['cats']=$_POST['cats'];
		$defblog_templates[$template_id]['del_cats']=$_POST['delete_existing_cats'];
		
		$defblog_templates[$template_id]['tags']=$_POST['tags'];
		$defblog_templates[$template_id]['del_tags']=$_POST['delete_existing_tags'];
	
		$defblog_templates[$template_id]['appearance']=$_POST['appearance'];
		$defblog_templates[$template_id]['settings']=$_POST['settings'];
		$defblog_templates[$template_id]['plugins']=$_POST['plugins'];
		$defblog_templates[$template_id]['options']=$_POST['options'];
		
		do_action_ref_array('defblog-submit', array(&$defblog_templates));
				
		update_defblog_templates($defblog_templates);
		
	    alert(__('Settings updatet!','default-blog-options'));
	}
	// Load the options page
	default_blog_options_page($options,$links);
}

// Initializing new blog 
function initialise_blog($blog_id){
	global $defblog_settings, $defblog_templates;
	$defblog_id=$defblog_templates[get_template_id()]['id'];
	
	if($defblog_settings['init']==true && $defblog_id!=""){
		copy_tags($defblog_id,$blog_id);
		copy_cats($defblog_id,$blog_id);
		copy_posts($defblog_id,$blog_id);
		copy_pages($defblog_id,$blog_id);
		copy_links($defblog_id,$blog_id);
		copy_appearance($defblog_id,$blog_id);
		copy_plugins($defblog_id,$blog_id);
		copy_settings($defblog_id,$blog_id);
		copy_options($defblog_id,$blog_id);
		
		do_action_ref_array( 'defblog-init-new-blog', array($defblog_id, $blog_id) );

	}
}
// This script will run the first time, the plugin was started
function initialize_plugin(){
	global $defblog_settings;
		
	if($defblog_settings['init']==""){
		$defblog_settings['init']=true;
	    update_site_option('defblog_settings',$defblog_settings);
	}
}

?>