<?php

add_dfb_plugin( 'sidebars', __( 'Sidebars', 'default-blog-options' ), 'default_blog_sidebars_admin', 'default_blog_sidebars_copy', 'default_blog_sidebars_save', 15 );

function default_blog_sidebars_admin(){ 
	global $default_blog_template;
	
	switch_to_blog( DFB_TEMPLATE_EDIT_BLOG_ID );
	
	global $wp_registered_sidebars, $wp_registered_widgets;
	
	$sidebars_widgets = wp_get_sidebars_widgets();
	
	$checked_sidebars = $default_blog_template[ 'sidebars' ];
	
	$content = '<h3>' . __( 'Sidebars', 'default-blog-options' )  . '</h3>';
	$content.= '<p>' . __( 'Select the Sidebars you want to copy.', 'default-blog-options' )  . '<p>';
	
	$content.= '<table class="widefat">';
		
	$content.= '<thead>';
	$content.= '<tr>';
		$content.= '<th>' . __( 'Sidebar', 'default-blog-options' ) . '</th>';	
		$content.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
    $content.= '</tr>';
	$content.= '</thead>';
	
	$content.= '<tbody>';
	
	foreach( $sidebars_widgets AS $sidebar_id => $sidebar ):
		$sidebar_checked = '';
		
		// Is Nav Menu checked for a copy?
		if( is_array( $checked_sidebars ) )
			if( in_array( $sidebar_id, $checked_sidebars ) ) 
				$sidebar_checked = ' checked';
			
		if( preg_match( '/sidebar-(\d)+/', $sidebar_id ) ):
			$content.= '<tr>';
				$content.= '<td>' . $wp_registered_sidebars[ $sidebar_id ][ 'name' ] . '</td>';	
				$content.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][sidebars][]" value="' . $sidebar_id . '"' . $sidebar_checked . ' /></td>';
        	$content.= '</tr>';
		endif;
	endforeach;
	
	$content.= '</tbody>';
	$content.= '</table><br />';
	
	foreach( $sidebars_widgets AS $sidebar_id => $sidebar ):
		// If its a sidebar
		if( preg_match( '/sidebar-(\d)+/', $sidebar_id ) ):
			if( is_array( $checked_sidebars ) ):
				if( in_array( $sidebar_id, $checked_sidebars ) ):
			
					$content_tab = '<h3>' . __( 'Sidebar Widgets', 'default-blog-options' )  . '</h3>';
					$content_tab.= '<p>' . __( 'Select the Widgets you want to copy.', 'default-blog-options' )  . '<p>';
					
					$content_tab.= '<table class="widefat">';
					
					$content_tab.= '<thead>';
					$content_tab.= '<tr>';
						$content_tab.= '<th>' . __( 'Widget', 'default-blog-options' ) . '</th>';	
						$content_tab.= '<th>' . __( 'ID', 'default-blog-options' ) . '</th>';	
						$content_tab.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
			        $content_tab.= '</tr>';
					$content_tab.= '</thead>';
					
					$content_tab.= '<tbody>';
					
					$sidebar_widgets = $wp_registered_widgets[ $sidebar_id ];
					
					$checked_sidebar_widgets = $default_blog_template[ 'sidebar_widgets' ][ $sidebar_id ];
					
					if( is_array( $sidebar ) ):
						foreach( $sidebar AS $sidebar_widget ):
							$sidebar_widget_checked = '';
		
							// Is Nav Menu checked for a copy?
							if( is_array( $checked_sidebar_widgets ) )
								if( in_array( $sidebar_widget, $checked_sidebar_widgets ) ) 
									$sidebar_widget_checked = ' checked';
								
							$content_tab.= '<tr>';
								$content_tab.= '<td>' . $wp_registered_widgets[ $sidebar_widget ][ 'name' ] . '</td>';	
								$content_tab.= '<td>' . $wp_registered_widgets[ $sidebar_widget ][ 'id' ] . '</td>';	
								$content_tab.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][sidebar_widgets][' . $sidebar_id . '][]" value="' . $sidebar_widget . '"' . $sidebar_widget_checked . ' /></td>';
					        $content_tab.= '</tr>';
							
						endforeach;
					endif;
					
					$content_tab.= '</tbody>';
					
					$content_tab.= '</table>';
					
					if ( TRUE == $default_blog_template[ 'sidebar_widgets' ][ $sidebar_id . '_delete_existing'] )
						$sidebar_delete_existing_checked = ' checked';
					
					$content_tab.= '<p><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][sidebar_widgets][' . $sidebar_id . '_delete_existing]" value="true"' . $sidebar_delete_existing_checked . ' /> ';
					$content_tab.=  __( 'Delete automatic generated WordPress entries.', 'default_blog_options' ) . '</p>';
					
					$elements[] = array(
						'id' => 'dfb_',
						'title' => $wp_registered_sidebars[ $sidebar_id ][ 'name' ],
						'content' => $content_tab
					);
				endif;
			endif;
		endif;
	endforeach;
	
	if( is_array( $elements ) )
		$content.= tk_tabs( 'default_blog_sidebars_tabs', $elements, 'html' );
	
	$content = apply_filters( 'default_blog_sidebars_admin', $content );
	
	echo $content;
	
	echo '<pre>';
	// print_r( $sidebars_widgets );
	echo '</pre>';
	
	echo '<pre>';
	// print_r( $wp_registered_sidebars );
	echo '</pre>';
	
	echo '<pre>';
	// print_r( $wp_registered_widgets );
	echo '</pre>';
	
	do_action( 'default_blog_links_admin_bottom' );
	
	restore_current_blog();
}
function default_blog_sidebars_copy( $from_blog_id, $to_blog_id ){
	global $default_blog_template, $default_blog_menu_references, $wp_registered_sidebars, $wp_registered_widgets;
	
	switch_to_blog( $from_blog_id );
	$sidebars_from = get_option( 'sidebars_widgets' );
	restore_current_blog();
	
	switch_to_blog( $to_blog_id );
	update_option( 'sidebars_widgets', $sidebars_from );
	restore_current_blog();
	
	$sidebars = $default_blog_template[ 'sidebars' ];
	if( is_array( $sidebars ) ):
		foreach( $sidebars AS $sidebar ):
			
			$widgets = $default_blog_template[ 'sidebar_widgets' ][ $sidebar ];
			
			if( is_array( $widgets ) ):
				foreach( $widgets AS $widget ):
					
					preg_match( '/([a-zA-Z_\-]+)-(\d+)?/', $widget, $widget_info );
					$widget_name = $widget_info[1];
					$widget_number = $widget_info[2];
		
					switch_to_blog( $from_blog_id );
					$widget_option = get_option( 'widget_' . $widget_name );
					restore_current_blog();
					
					if( is_array( $widget_option ) ):
						foreach( $widget_option AS $key => $option ):
							if( is_int( $key ) ):
								// Rewrite settings for Nav Menu
								if( 'nav_menu' == $widget_name )
									$widget_option[ $key ][ 'nav_menu' ] = $default_blog_menu_references[ $widget_option[ $key ][ 'nav_menu' ] ];
							endif;
						endforeach;
					endif;
					
					switch_to_blog( $to_blog_id );
					update_option( 'widget_' . $widget_name, $widget_option );
					restore_current_blog();	
				endforeach;
			endif;
			
		endforeach;
	endif;
}
function default_blog_sidebars_save( $input ){
	return $input;
}
