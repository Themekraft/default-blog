<?php

add_dfb_plugin( 'blog-template', __( 'Template Settings', 'default-blog-options' ), 'default_blog_template_admin', '', 'default_blog_template_save', 0 );

function default_blog_template_admin(){ 
	global $wpdb;
	
    $blogs = $wpdb->get_results( "SELECT blog_id FROM " . $wpdb->blogs. " WHERE spam = '0' AND deleted ='0'", ARRAY_A );
    
	$dfb_template_options = get_option( DFB_TEMPLATE_OPTIONS );
	
	if( !is_array( $dfb_template_options ) )
		$content.= '<p>' . __( 'No Template have been created yet. Please create one to go further.', 'default-blog-options' ) . '</p>';
	
	if( DEFAULT_EDIT_TEMPLATE_ID == 0  )
		$content.= '<p>' . __( 'Please select an Template which you want to edit to go further.', 'default-blog-options' ) . '</p>';
	
	if( is_array( $dfb_template_options ) ):
		$content.= '<h3>' . __( 'Templates', 'default-blog-options' ) . '</h3>';
		$content.= '<p><table class="widefat">';
		
		$content.= '<thead>';
			$content.= '<tr>';
				$content.= '<th>' . __( 'Template', 'default-blog-options' ) . '</th>';	
				$content.= '<th>' . __( 'Blog', 'default-blog-options' ) .'</th>';
				$content.= '<th>' . __( 'Edit Template', 'default-blog-options' ) .'</th>';
				$content.= '<th>' . __( 'Select as Default Template for new created blogs', 'default-blog-options' ) .'</th>';
	        $content.= '</tr>';
		$content.= '</thead>';
		
		$content.= '<tbody>';
		
		foreach( $dfb_template_options AS $key => $template_options ):
			
			if( is_int( $key ) ):
				$checked_template = '';
				$checked_default_blog = '';
				
				if( DFB_TEMPLATE_EDIT_ID == $template_options[ 'template_id' ] && '' != DFB_TEMPLATE_EDIT_ID )
					$checked_template = ' checked="checked"';
					
				if( DFB_TEMPLATE_ID == $template_options[ 'template_id' ] && '' != DFB_TEMPLATE_ID  )
					$checked_default_blog = ' checked="checked"';
				
				$content.= '<tr>';
					$content.= '<td>' . $template_options[ 'template_name' ] . '</td>';	
					$content.= '<td>' . get_blog_option( $template_options['blog_id'], 'blogname' ) .'</td>';
					$content.= '<td><input type="radio" name="' . DFB_OPTION_GROUP . '[dfb_template_edit_id]" value="' . $template_options[ 'template_id' ] . '"' . $checked_template . ' /></td>';
					$content.= '<td><input type="radio" name="' . DFB_OPTION_GROUP . '[dfb_template_id]" value="' . $template_options[ 'template_id' ] . '"' . $checked_default_blog . ' /></td>';
					$content.= '';
		        $content.= '</tr>';
			endif;
			
		endforeach;
		
		$content.= '</tbody>';
		
		$content.= '</table></p>';
	endif;
	
	////////////
	
	$content.= '<h3>' . __( 'Create Blog Template', 'default-blog-options' ) . '</h3>';
	
	$content.= '<label for="create_blog_template_name">' . __( 'Template name', 'default-blog-options' ) . '</label>';
	
	$content.= '<p><input type="text" id="create_blog_template_name" name="' . DFB_OPTION_GROUP . '[create_blog_template_name]" /></p>';
	
	$content.= '<label for="create_blog_template_name">' . __( 'Select a Blog', 'default-blog-options' ) . '</label>';
	
	$content.= '<p><select id="create_blog_id" name="' . DFB_OPTION_GROUP . '[create_blog_id]">';
	
	$content.= '<option selected value="none">' . __( 'Please select a blog', 'default-blog-options' ) . '</option>\n';
 
    foreach( $blogs AS $blog ):
		$content.= '<option value="' . $blog['blog_id'] . '">' . get_blog_option( $blog['blog_id'], 'blogname' ) . '</option>\n';
    endforeach;

    $content.= '</select></p>';
	
	$content.= tk_form_button( __( 'Add new Template', 'default-blog-options' ), array( 'name' => 'new_template' ), 'html' );
	
	$content = apply_filters( 'default-blog-template-admin', $content );
	
	echo $content;
	
	
	echo '<br /><br /><br /><br />Options: <pre>';
	print_r( get_option( DFB_OPTION_GROUP ) );
	echo '</pre>';
	
	echo 'Templates: <pre>';
	print_r( get_option( DFB_TEMPLATE_OPTIONS ) );
	echo '</pre>';
	
	echo '<pre>';
	print_r( get_dfb_plugins() );
	echo '</pre>';
	
	/*
	echo 'Old Templates: <pre>';
	print_r( get_site_option( 'defblog_templates' ) );
	echo '</pre>';
	
	echo 'Old Settings: <pre>';
	print_r( get_site_option( 'defblog_settings' ) );
	echo '</pre>';
	 */
	
	do_action( 'default-blog-template-admin-bottom' );
}

function default_blog_template_save( $input ){
	
	if( isset( $_REQUEST[ 'new_template' ] ) && 'none' != $input[ 'create_blog_id' ] && '' != $input[ 'create_blog_template_name' ] ):
		
		$dfb_template_options = get_option( DFB_TEMPLATE_OPTIONS );
		
		if( is_array( $dfb_template_options ) ):
			$last_inserted_id = $dfb_template_options[ 'last_inserted_id' ];
			$template_id = $last_inserted_id + 1;
		else:
			$dfb_template_options = array();
			$template_id = 1;
			$last_inserted_id = 1;
		endif;
		
		$dfb_template_options[ $template_id ] = array(
			'blog_id' => $input[ 'create_blog_id' ],
			'template_id' => $template_id,
			'template_name' => $input[ 'create_blog_template_name' ],
		);
		
		$dfb_template_options[ 'last_inserted_id' ] = $template_id;
		
		update_option( DFB_TEMPLATE_OPTIONS, $dfb_template_options );
	endif;
	
	unset( $input[ 'create_blog_id' ] );
	unset( $input[ 'create_blog_template_name' ] );
	
	return $input;
}
