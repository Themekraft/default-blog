<?php

add_dfb_plugin( 'settings', __( 'Settings', 'default-blog-options' ), 'default_blog_settings_admin', 'default_blog_settings_copy', 'default_blog_settings_save', 25 );

function default_blog_settings_admin(){ 
	global $wpdb, $default_blog_template;
	
	switch_to_blog( DFB_TEMPLATE_EDIT_BLOG_ID );
	
	$content = '<table class="widefat">';
	
	$content.= '<thead>';
		$content.= '<tr>';
			$content.= '<th>' . __( 'Setting', 'default-blog-options' ) . '</th>';	
			$content.= '<th>' . __( 'Action', 'default-blog-options') .'</th>';
			$content.= '<th>' . __( 'Do this', 'default-blog-options' ) . '</th>';
	    $content.= '</tr>';
	$content.= '</thead>';
	
	$content.= '<tbody>';
		
		// Theme
		
		if( TRUE == $default_blog_template[ 'appearance' ][ 'theme' ] )
			$checked_appearance_theme = ' checked';
		
		$content.= '<tr>';
			$content.= '<td><label for="appearance[theme]">' . __( 'Theme', 'default-blog-options' ) . '</label></td>';
			$content.= '<td>' . sprintf( __('Set up Theme and Theme settings of "%s" theme.' ), get_blog_option( DFB_TEMPLATE_EDIT_BLOG_ID ,'current_theme' ) ) . '</td>';
			$content.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][appearance][theme]" value="true"' . $checked_appearance_theme . ' /></td>';
		$content.= '<tr>';
		
		// Plugins
		
		if( TRUE == $default_blog_template[ 'plugins' ][ 'active' ] )
			$checked_plugins_active = ' checked';
		
		$content.= '<tr>';
			$content.= '<td><label for="">' . __( 'Plugins', 'default-blog-options' ) . '</label></td>';
			$content.= '<td>' . __('Activate all Plugins like in Blog Template.' ) . '</td>';
			$content.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][plugins][active]" value="true"' . $checked_plugins_active . ' /></td>';
		$content.= '<tr>';
		
	$content.= '</tbody>';
	
	$content.= '</table>';
	
	$content = apply_filters( 'dfb-settings-admin', $content );
	
	echo $content;
	
	do_action( 'dfb-settings-admin-bottom' );
	
	restore_current_blog();
}

function default_blog_settings_copy( $from_blog_id, $to_blog_id ){
	global $default_blog_template;
	
	if( TRUE == $default_blog_template[ 'appearance' ][ 'theme' ] )
		default_blog_appearance_copy( $from_blog_id, $to_blog_id );
		
	if( TRUE == $default_blog_template[ 'plugins' ][ 'active' ] )
		default_blog_plugins_copy( $from_blog_id, $to_blog_id );
}

function default_blog_appearance_copy( $from_blog_id, $to_blog_id ){
		
	switch_to_blog( $from_blog_id );
		$current_template = get_option( 'current_theme' );
		$template = get_option( 'template' );
		$current_stylesheet = get_option( 'stylesheet' );
		$theme_mods = get_option( 'theme_mods_' . $current_stylesheet );
	restore_current_blog();
	
	switch_to_blog( $to_blog_id );
		update_option( 'current_theme', $current_template );
		update_option( 'template', $template );
		update_option( 'stylesheet', $current_stylesheet );
		update_option( 'theme_mods_' . $current_stylesheet, $theme_mods );
	restore_current_blog();
}
function default_blog_plugins_copy( $from_blog_id, $to_blog_id ){
	switch_to_blog( $from_blog_id );
		$active_plugins = get_option( 'active_plugins' );
	restore_current_blog();
	
	switch_to_blog( $to_blog_id );
		update_option( 'active_plugins', $active_plugins );
	restore_current_blog();
}
function default_blog_settings_save( $input ){
	return $input;
}
