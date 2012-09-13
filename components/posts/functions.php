<?php

add_dfb_plugin( 'posts', __( 'Post Types', 'default-blog-options' ), 'default_blog_posts_admin', 'default_blog_posts_copy', 'default_blog_posts_save', 5  );

function default_blog_posts_admin(){ 
	global $wpdb, $default_blog_template;
	
	switch_to_blog( DFB_TEMPLATE_EDIT_BLOG_ID );
	
	$post_types = apply_filters( 'default_blog_post_types', get_post_types() );
	
	$elements = array();
	
	// echo '<pre>';
	// print_r( $default_blog_template );
	// echo '</pre>';
	
	foreach( $post_types AS $post_type ):
		
		// Getting all posts of post type
		$args = array(
			'post_type' => $post_type,
			'posts_per_page ' => -1 // Show all posts
		);
		
		$the_query = new WP_Query( $args );
		
		// Table head		
		$content = '<table class="widefat">';
		
		$content.= '<thead>';
			$content.= '<tr>';
				$content.= '<th>' . __( 'Title', 'default-blog-options' ) . '</th>';	
				$content.= '<th>' . __( 'Date', 'default-blog-options') .'</th>';
				$content.= '<th>' . __( 'Status', 'default-blog-options') .'</th>';
				$content.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
				$content.= '<th>' . __( 'Attachments', 'default-blog-options' ) . '</th>';
				$content.= '<th>' . __( 'Meta', 'default-blog-options' ) . '</th>';
				
				if( post_type_supports( $post_type, 'comments' ) )
					$content.= '<th>' . __( 'Comments', 'default-blog-options' ) . '</th>';	       
	        
	        $content.= '</tr>';
		$content.= '</thead>';
		
		$checked_posts = $default_blog_template[ $post_type ];
		$checked_posts_attachments = $default_blog_template[ $post_type . '_attachments' ];
		$checked_posts_meta = $default_blog_template[ $post_type . '_meta' ];
		
		if( post_type_supports( $post_type, 'comments' ) )
			$checked_posts_comments = $default_blog_template[ $post_type . '_comments' ];
		
		// Getting older version values 
		if( '' == $checked_posts ):
			if( 'post' == $post_type )
				if( is_array( $default_blog_template[ 'posts' ] ) ) $checked_posts = $default_blog_template[ 'posts' ];
			if( 'page' == $post_type )
				if( is_array( $default_blog_template[ 'pages' ] ) ) $checked_posts = $default_blog_template[ 'pages' ];
		endif;
			
		$content.= '<tbody>';
		
		while ( $the_query->have_posts() ) : $the_query->the_post();
			global $post;
				$status = '';
				
				$post_checked = '';
				$post_meta_checked = '';
				$post_attachments_checked = '';
				$post_comments_checked = '';
				
				// Is Post checked for a copy?
				if( is_array( $checked_posts ) )
					if( in_array( get_the_ID(), $checked_posts ) ) 
						$post_checked = ' checked';
						
				// Is Post attachment checked for copy?
				if( is_array( $checked_posts_attachments ) )
					if( in_array( get_the_ID(), $checked_posts_attachments ) ) 
						$post_attachments_checked = ' checked';
					
				// Is Post meta checked for copy?
				if( is_array( $checked_posts_meta ) )
					if( in_array( get_the_ID(), $checked_posts_meta ) ) 
						$post_meta_checked = ' checked';
					
				// Is Post comments checked for a copy?
				if( is_array( $checked_posts_comments ) && post_type_supports( $post_type, 'comments' ) )
					if( in_array( get_the_ID(), $checked_posts_comments ) ) 
						$post_comments_checked = ' checked';
				
				switch( $post->post_status ){
					case 'new':
						$status = _x( 'New', 'Post status', 'default-blog-options' );
						break;
					case 'publish':
						$status = _x( 'Published', 'Post status', 'default-blog-options' );
						break;
					case 'pending':
						$status = _x( 'Pending', 'Post status', 'default-blog-options' );
						break;
					case 'draft':
						$status = _x( 'Draft', 'Post status', 'default-blog-options' );
						break;
					case 'auto-draft':
						$status = _x( 'Auto Draft', 'Post status', 'default-blog-options' );
						break;
					case 'future':
						$status = _x( 'Future', 'Post status', 'default-blog-options' );
						break;
					case 'private':
						$status = _x( 'Private', 'Post status', 'default-blog-options' );
						break;
					case 'inherit':
						$status = _x( 'Inherit', 'Post status', 'default-blog-options' );
						break;
					case 'trash':
						$status = _x( 'Trash', 'Post status', 'default-blog-options' );
						break;
				}
				
				$content.= '<td>';
				$content.= get_the_title();
				$content.= '</td>';
				
				$content.= '<td>';
				$content.= get_the_date();
				$content.= '</td>';
				
				$content.= '<td>';
				$content.= $status;
				$content.= '</td>';
				
				$content.= '<td>';
				$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type . '][]" value="' . get_the_ID() . '"' . $post_checked . ' />';
				$content.= '</td>';
				
				$content.= '<td>';
				$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type . '_attachments][]" value="' . get_the_ID() . '"' . $post_attachments_checked . ' />';
				$content.= '</td>';
				
				$content.= '<td>';
				$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type . '_meta][]" value="' . get_the_ID() . '"' . $post_meta_checked . ' />';
				$content.= '</td>';
				
				if( post_type_supports( $post_type, 'comments' ) ):
					$content.= '<td>';
					$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type . '_comments][]" value="' . get_the_ID() . '"' . $post_comments_checked . ' /> <span class="comment-count">' . get_comments_number() . '</span>';
					$content.= '</td>';
				endif;
				
			$content.= '</tr>';
		endwhile;
		
		$content.= '</tbody>';
		$content.= '</table>';
		
		// Delete automatic entries
		if( $post_type == 'post' || $post_type == 'page' ):
			if( $default_blog_template[ $post_type . '_delete_existing' ] )
				$post_type_delete_existing_checked = ' checked="checked"';
			
			$content.= '<p><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type . '_delete_existing]" value="true"' . $post_type_delete_existing_checked . ' /> ';
			$content.=  __( 'Delete automatic generated WordPress entries.', 'default_blog_options' ) . '</p>';
			
		endif;
		
		$content = apply_filters( 'default-blog-posts-'. $post_type, $content );
		
		$elements[] = array(
			'id' => sanitize_title( $post_type ),
			'title' => $post_type,
			'content' => $content
		);		
	endforeach;
		
	$content = tk_tabs( 'default_blog_tabs', $elements, 'html' );
	
	$content = apply_filters( 'default-blog-posts-admin', $content );
	
	echo $content;
	
	do_action( 'default-blog-posts-admin-bottom' );
	
	restore_current_blog();
	
}

function default_blog_posts_copy( $from_blog_id, $to_blog_id, $args = array() ){
	global $default_blog_template;
	
	// Setting up Post Types
	$defaults = array(
		'post_types' => apply_filters( 'default_blog_post_types', get_post_types() ),
		'template_id' => DFB_TEMPLATE_ID
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	// Running Post Types	
	foreach( $post_types AS $post_type ):
		
		if( $default_blog_template[ $post_type . '_delete_existing' ] ):
			switch_to_blog( $to_blog_id );
			
			$args = array(
				'numberposts' => -1,
				'post_type' => $post_type
			);
			$delete_posts = get_posts( $args );
			
			foreach( $delete_posts AS $delete_post ):
				wp_delete_post( $delete_post->ID, TRUE );
			endforeach;
			
			restore_current_blog();
		endif;
		
		// Getting all posts of post type
		$args = array(
			'post_type' => $post_type,
			'posts_per_page ' => -1, // Show all posts
			'post__in' => $default_blog_template[ $post_type ] // Only taking selected posts
		);
		
		// Getting Posts from Soiurce Blog
		switch_to_blog( $from_blog_id );
		$the_query = new WP_Query( $args );
		restore_current_blog();
		
		// Running Posts of Post Type
		while ( $the_query->have_posts() ) : $the_query->the_post();
			global $post;
		
			// Checking if comments have to be copied too
			$copy_attachments = FALSE;
			if ( is_array( $default_blog_template[ $post_type . '_attachments' ] ) )
				if( in_array( $post->ID, $default_blog_template[ $post_type . '_attachments' ] ) )
					$copy_attachments = TRUE;
			
			// Checking if comments have to be copied too
			$copy_meta = FALSE;
			if ( is_array( $default_blog_template[ $post_type . '_meta' ] ) )
				if( in_array( $post->ID, $default_blog_template[ $post_type . '_meta' ] ) )
					$copy_meta = TRUE;
				
			// Checking if comments have to be copied too
			$copy_comments = FALSE;
			if ( is_array( $default_blog_template[ $post_type . '_comments' ] ) )
				if( in_array( $post->ID, $default_blog_template[ $post_type . '_comments' ] ) )
					$copy_comments = TRUE;
			
			// Copy post
			default_blog_copy_post( $post->ID, $from_blog_id, $to_blog_id, array( 'copy_attachments' => $copy_attachments, 'copy_comments' => $copy_comments, 'copy_meta' => $copy_meta ) );
		endwhile;
		
	endforeach;
}

function default_blog_copy_post( $post_id, $from_blog_id, $to_blog_id, $args = array() ){
	
	// Setting Arguments
	$defaults = array(
		'copy_attachments' => TRUE,
		'copy_comments' => TRUE,
		'copy_meta' => TRUE
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	// Getting Data from source Blog
	switch_to_blog( $from_blog_id );
	$post = (array) get_post( $post_id );
	
	if ( !is_array( $post ) )
		return FALSE;
	
	restore_current_blog();
	
	// Putting Data to destination Blog
	switch_to_blog( $to_blog_id );
	unset( $post[ 'ID' ] ); // Deleting ID for adding new post
	$new_post_id = wp_insert_post( $post );
	
	if( $new_post_id == 0 )
		return FALSE;
	
	restore_current_blog();
	
	// Copy Attachments
	if( $copy_attachments )
		default_blog_copy_attachments( $post_id, $new_post_id, $from_blog_id, $to_blog_id );

	// Copy Comments
	if( $copy_comments )
		default_blog_copy_comments( $post_id, $new_post_id, $from_blog_id, $to_blog_id );
	
	// Copy Meta Data	
	if( $copy_meta )
		default_blog_copy_meta( $post_id, $new_post_id, $from_blog_id, $to_blog_id );
	
	return $new_post_id;
}

function default_blog_copy_attachments( $post_id, $new_post_id, $from_blog_id, $to_blog_id ){
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => null,
		'post_status' => null,
		'post_parent' => $post_id
	); 
	
	switch_to_blog( $from_blog_id );
	$attachments = get_posts( $args );
	restore_current_blog();
	
	if( $attachments ):
		foreach( $attachments as $attachment ):
			$attachment = (array) $attachment;
			
			// Getting Attachment data
			switch_to_blog( $from_blog_id );
			$attachment_id = $attachment[ 'ID' ];
			$attachment_meta = get_post_custom( $attachment[ 'ID' ] );
			$attachment_url = wp_get_attachment_url( $attachment_id );
			
			$filename = $attachment_meta[ '_wp_attached_file' ][ 0 ];
			$wp_upload_dir = wp_upload_dir();
			$filepath = $wp_upload_dir[ 'path' ] . '/' . _wp_relative_upload_path( $filename );
			$fileurl =  $attachment[ 'guid' ];
			restore_current_blog();
			
			// Adding Attachment
			switch_to_blog( $to_blog_id );

			unset( $attachment[ 'ID' ] ); // Not needed
			unset( $attachment[ 'post_parent' ] ); // Not needed
			
			$new_wp_upload_dir = wp_upload_dir();
			$new_filepath = $new_wp_upload_dir[ 'path' ] . '/' .  _wp_relative_upload_path( $filename );
			$new_fileurl = $new_wp_upload_dir[ 'baseurl' ] . '/' .  _wp_relative_upload_path( $filename );
			$attachment[ 'guid' ] = $new_fileurl;
			
			$new_attachment_id = wp_insert_attachment( $attachment, $filename, $new_post_id ); // Inserting Attachments
			$new_attachment_url = wp_get_attachment_url( $new_attachment_id );
			default_blog_copy_meta( $attachment_id, $new_attachment_id, $from_blog_id, $to_blog_id ); // Copy Meta data
			
			// Copy files
			if( file_exists( $filepath ) )
				if( !copy( $filepath , $new_filepath ) )
					wp_die( __( 'Could not copy files from Template. Please deselect to copy attachements or contact your Administrator and try again.', 'default-blog-options' ) );
			
			// Copy different image sizes
			$attachment_images = unserialize( $attachment_meta[ '_wp_attachment_metadata' ][ 0 ] ) ;
			$attachment_images_sizes = $attachment_images[ 'sizes' ];
			
			$replace_image_sizes = array();
			
			if( is_array( $attachment_images_sizes ) ):
				foreach( $attachment_images_sizes AS $images ):
					// Setting path and url
					$image_filepath = $wp_upload_dir[ 'path' ] . '/' .  _wp_relative_upload_path( $images[ 'file' ] );
					$image_fileurl =  $wp_upload_dir[ 'baseurl' ] . '/' .  _wp_relative_upload_path( $images[ 'file' ] );
					
					$new_image_filepath = $new_wp_upload_dir[ 'path' ] . '/' .  _wp_relative_upload_path( $images[ 'file' ] );
					$new_image_fileurl = $new_wp_upload_dir[ 'baseurl' ] . '/' .  _wp_relative_upload_path( $images[ 'file' ] );
					
					// Adding URLs for replacing
					$replace_files[] = array(
						'url' => $image_fileurl,
						'new_url' => $new_image_fileurl
					);
					
					// Copy file
					if( file_exists( $image_filepath ) ):
						if( !copy( $image_filepath , $new_image_filepath ) ):
							wp_die( __( 'Could not copy files from Template. Please deselect to copy attachements or contact your Administrator and try again.', 'default-blog-options' ) );
						endif;
					endif;
						
				endforeach;
			endif;
			
			// Getting Post and rewriting attachment URLs
			$new_attachment = (array) get_post( $new_attachment_id );
			$post = (array) get_post( $new_post_id );
			
			// Attachement URL
			$replace_files[] = array(
				'url' => $attachment_url,
				'new_url' => $new_attachment_url
			);
			
			// Attachment href
			$replace_files[] = array(
				'url' => $fileurl,
				'new_url' => $new_fileurl
			);
			
			// Replacing all URLs
			foreach( $replace_files AS $file )
				$post[ 'post_content' ] = str_replace( $file[ 'url' ], $file[ 'new_url' ], $post[ 'post_content' ] );
			
			wp_update_post( $post );
			
			restore_current_blog();
			
		endforeach;
	endif;
}

function default_blog_copy_comments(  $post_id, $new_post_id, $from_blog_id, $to_blog_id ){
	// Getting Comments
	$args = array(
		'post_id' => $post_id
	);
	
	switch_to_blog( $from_blog_id );
	$comments =  get_comments( $args );
	restore_current_blog();
	
	// Adding Comments
	switch_to_blog( $to_blog_id );
	foreach( $comments as $comment ) :
		$comment = (array) $comment; // Adding needs array
		unset( $comment[ 'comment_ID' ] ); // Dont need it
		
		$comment[ 'comment_post_ID' ] = $new_post_id;
		$comment_id = wp_insert_comment( $comment );
	endforeach;
	restore_current_blog();
}

function default_blog_copy_meta( $post_id, $new_post_id, $from_blog_id, $to_blog_id ){
	switch_to_blog( $from_blog_id );
	$custom_fields = get_post_custom( $post_id );
	restore_current_blog();
	
	switch_to_blog( $to_blog_id );
	foreach ( $custom_fields AS $custom_field_key => $custom_field ):
		foreach ( $custom_field AS $meta_value ):
			add_post_meta( $new_post_id, $custom_field_key, $meta_value );
		endforeach;
	endforeach;
	restore_current_blog();
}

function default_blog_posts_ignore( $post_types ){
	unset( $post_types[ 'attachment' ] );
	unset( $post_types[ 'revision' ] );
	unset( $post_types[ 'nav_menu_item' ] );
	
	return $post_types;
}
add_filter( 'default_blog_post_types', 'default_blog_posts_ignore' );

function default_blog_posts_save( $input ){
	return $input;
}

