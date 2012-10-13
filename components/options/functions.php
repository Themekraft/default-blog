<?php

add_dfb_plugin( 'blog_options', __( 'Blog Options', 'default-blog-options' ), 'default_blog_options_admin', 'default_blog_options_copy', 'default_blog_options_save', 35 );

function default_blog_options_admin(){ 
	global $wpdb, $default_blog_template;
	
	switch_to_blog( DFB_TEMPLATE_EDIT_BLOG_ID );
	
	$options_table = $wpdb->base_prefix . DFB_TEMPLATE_EDIT_BLOG_ID . '_options';
	
	$options = $wpdb->get_results( "SELECT * FROM " . $options_table . " ORDER BY option_name");
	
	// If there is no result
	if( count( $options ) == 0 )
		$options_table = $wpdb->base_prefix . '_options';
	
	$options = $wpdb->get_results( "SELECT * FROM " . $options_table . " ORDER BY option_name");
	
	$content = '<table class="widefat">';
	
	$content.= '<thead>';
		$content.= '<tr>';
			$content.= '<th>' . __( 'Name', 'default-blog-options' ) . '</th>';	
			$content.= '<th>' . __( 'Value', 'default-blog-options') .'</th>';
			$content.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
	    $content.= '</tr>';
	$content.= '</thead>';
	
	$content.= '<tbody>';
	
		$options_selected = $default_blog_template['options'];
		
		foreach( (array) $options as $option) :
			$option->option_name = esc_attr( $option->option_name );
			
			$checked = '';
			
			if( is_array( $options_selected ) )
				if( in_array( $option->option_name, $options_selected ) )
					$checked = ' checked';
			
			$content.= '<tr>';
				$content.= '<td><label for="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][options][]">' . $option->option_name . '</label></td>';
				$content.= '<td><textarea disabled="disabled">' . $option->option_value . '</textarea></td>';
				$content.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][options][]" value="' . $option->option_name . '" ' . $checked . ' /></td>';
			$content.= '<tr>';
			
			$content = apply_filters( 'default-blog-options-row', $content, $option->option_name );
		
		endforeach;
		
	$content.= '</tbody>';
	
	$content.= '</table>';
	
	$content = apply_filters( 'default-blog-options-admin', $content );
	
	echo $content;
	
	do_action( 'default-blog-options-admin-bottom' );
	
	restore_current_blog();
}

function default_blog_options_copy( $from_blog_id, $to_blog_id ){
	global $wp_rewrite, $default_blog_template;
	
	$options = $default_blog_template[ 'options' ];
	
	if( is_array( $options ) ):
		foreach( $options AS $option ):
			switch_to_blog( $to_blog_id );
				
				if( 'permalink_structure' == $option ):
					$wp_rewrite->set_permalink_structure( get_blog_option( $from_blog_id, $option ) );
				
				elseif( 'category_base' == $option ):
					$wp_rewrite->set_category_base( get_blog_option( $from_blog_id, $option ) );
					
				elseif( 'tag_base' == $option  ):
					$wp_rewrite->set_tag_base( get_blog_option( $from_blog_id, $option ) );
					
				else:
					update_option( $option, get_blog_option( $from_blog_id, $option ) );
					
				endif;
				
				create_initial_taxonomies();
				
				$wp_rewrite->flush_rules();
				
			restore_current_blog();
		endforeach;
	endif;
}

function default_blog_options_save( $input ){
	return $input;
}
