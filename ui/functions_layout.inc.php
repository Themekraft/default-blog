<?php 
// Templates
if(!function_exists('alert')){
	function alert($msg){
		echo "<div class=\"updated\"><p>".$msg."</p></div>";
	}
}
function defblog_css(){
	global $defblog_plugin_path;
	echo "<link rel=\"stylesheet\" href=\"".get_option('siteurl')."/wp-content/plugins/".basename($defblog_plugin_path)."/ui/styles.css\" type=\"text/css\" media=\"screen\" />";
}
if(!function_exists('ajaxui_js')){
	function ajaxui_js(){
		
		if( ! isset( $_GET['page'] ) ) 
			return;

		if( $_GET['page'] == 'defaultblog' ) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-tabs');
		}
	}
}
if(!function_exists('ajaxui_css')){
	function ajaxui_css()
	{
		 if( $_GET['page'] == 'defaultblog' ) {
			 echo '<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css" rel="stylesheet" />';
		 }	
	}
}


?>