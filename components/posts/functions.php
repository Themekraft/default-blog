<?php

add_dfb_plugin( 'posts', __( 'Post Types', 'default-blog-options' ), 'default_blog_posts_admin', 'default_blog_posts_copy', 'default_blog_posts_save', 5  );

function default_blog_posts_admin(){ 
	global $wpdb, $default_blog_template;
	
	switch_to_blog( DFB_TEMPLATE_EDIT_BLOG_ID );
	
	$post_types = apply_filters( 'default_blog_post_types', get_post_types( '', 'object' ) );
	
	$elements = array();
	
	foreach( $post_types AS $post_type ):
		
		// Getting all posts of post type
		$args = array(
			'post_type' => $post_type->name,
			'posts_per_page ' => -1 // Show all posts
		);
		
		$the_query = new WP_Query( $args );
		
		// Table head
		$content = '<h3>' . $post_type->labels->all_items . '</h3>';
		
		$content.= '<table class="widefat">';
		
		$content.= '<thead>';
			$content.= '<tr>';
				$content.= '<th>' . __( 'Title', 'default-blog-options' ) . '</th>';	
				$content.= '<th>' . __( 'Date', 'default-blog-options') .'</th>';
				$content.= '<th>' . __( 'Status', 'default-blog-options') .'</th>';
				$content.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
				$content.= '<th>' . __( 'Attachments', 'default-blog-options' ) . '</th>';
				$content.= '<th>' . __( 'Meta', 'default-blog-options' ) . 	'</th>';
				
				if( post_type_supports( $post_type->name, 'comments' ) )
					$content.= '<th>' . __( 'Comments', 'default-blog-options' ) . '</th>';	       
	        
	        $content.= '</tr>';
		$content.= '</thead>';
		
		$checked_posts = $default_blog_template[ $post_type->name ];
		$checked_posts_attachments = $default_blog_template[ $post_type->name . '_attachments' ];
		$checked_posts_meta = $default_blog_template[ $post_type->name . '_meta' ];
		
		if( post_type_supports( $post_type->name, 'comments' ) )
			$checked_posts_comments = $default_blog_template[ $post_type->name . '_comments' ];
		
		// Getting older version values 
		if( '' == $checked_posts ):
			if( 'post' == $post_type->name )
				if( is_array( $default_blog_template[ 'posts' ] ) ) $checked_posts = $default_blog_template[ 'posts' ];
			if( 'page' == $post_type->name )
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
				if( is_array( $checked_posts_comments ) && post_type_supports( $post_type->name, 'comments' ) )
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
				
				$content.= '<tr>';
				
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
				$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type->name . '][]" value="' . get_the_ID() . '"' . $post_checked . ' />';
				$content.= '</td>';
				
				$content.= '<td>';
				$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type->name . '_attachments][]" value="' . get_the_ID() . '"' . $post_attachments_checked . ' />';
				$content.= '</td>';
				
				$content.= '<td>';
				$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type->name . '_meta][]" value="' . get_the_ID() . '"' . $post_meta_checked . ' />';
				$content.= '</td>';
				
				if( post_type_supports( $post_type->name, 'comments' ) ):
					$content.= '<td>';
					$content.= '<input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type->name . '_comments][]" value="' . get_the_ID() . '"' . $post_comments_checked . ' /> <span class="comment-count">' . get_comments_number() . '</span>';
					$content.= '</td>';
				endif;
				
			$content.= '</tr>';
		endwhile;
		
		$content.= '</tbody>';
		$content.= '</table>';
		
		// Delete automatic entries
		if( $post_type->name == 'post' || $post_type->name == 'page' ):
			if( $default_blog_template[ $post_type->name . '_delete_existing' ] )
				$post_type_delete_existing_checked = ' checked="checked"';
			
			$content.= '<p><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type->name . '_delete_existing]" value="true"' . $post_type_delete_existing_checked . ' /> ';
			$content.=  __( 'Delete automatic generated WordPress entries.', 'default_blog_options' ) . '</p>';
			
		endif;
		
		$taxonomies = get_taxonomies( array( 'object_type' => array( $post_type->name ) ), 'objects' );
		
		// Taxonomies
		foreach( $taxonomies AS $taxonomy ):
			
			$terms = get_terms( $taxonomy->name, array( 'hide_empty' => FALSE ) );
			
			$content.= '<h3>' . $taxonomy->labels->name . '</h3>';
			
			if( count( $terms ) > 0 ):
			
				$content.= '<table class="widefat">';
				
				$content.= '<thead>';
					$content.= '<tr>';
						$content.= '<th>' . __( 'Title', 'default-blog-options' ) . '</th>';	
						$content.= '<th>' . __( 'Copy Term', 'default-blog-options' ) . '</th>';
			        $content.= '</tr>';
				$content.= '</thead>';
				
				$content.= '<tbody>';
				
				foreach( $terms AS $term ):
					$term_checked = '';
					if( is_array( $default_blog_template[ $post_type->name . '_taxonomies' ][ $taxonomy->name ] ) )
						if( in_array( $term->term_id, $default_blog_template[ $post_type->name . '_taxonomies' ][ $taxonomy->name ] ) )
							$term_checked = ' checked="checked"';
					
					$content.= '<tr>';
						$content.= '<td>' . $term->name . '</td>';	
						$content.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][' . $post_type->name. '_taxonomies][' . $taxonomy->name  . '][]" value="' . $term->term_id . '"' . $term_checked . ' />';
			        $content.= '</tr>';
				endforeach;
				
				$content.= '</tbody>';
	
				$content.= '</table>';
			
			else:
				$content.= '<p>' . __( 'No entry found.	', 'default-blog-options' ) . '</p>';
			endif;
			
		endforeach;
		
		$content = apply_filters( 'default-blog-posts-'. $post_type->name, $content );
		
		$elements[] = array(
			'id' => sanitize_title( $post_type->name ),
			'title' => $post_type->label,
			'content' => $content
		);		
	endforeach;
		
	$content = tk_tabs( 'default_blog_post_tabs', $elements, 'html' );
	
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
		
		// Copy Taxonomies
		$taxonomies = get_taxonomies( array( 'object_type' => array( $post_type ) ), 'objects' );
		$taxonomy_termlist = default_blog_copy_taxonomies( $post_type, $from_blog_id, $to_blog_id );
		
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
			default_blog_copy_post( $post->ID, $from_blog_id, $to_blog_id, array( 'copy_attachments' => $copy_attachments, 'copy_comments' => $copy_comments, 'copy_meta' => $copy_meta, 'taxonomy_termlist' => $taxonomy_termlist ) );
		endwhile;
		
	endforeach;
}

function default_blog_copy_post( $post_id, $from_blog_id, $to_blog_id, $args = array() ){
	global $default_blog_post_relations;
	
	// Setting Arguments
	$defaults = array(
		'copy_attachments' => TRUE,
		'copy_comments' => TRUE,
		'copy_meta' => TRUE,
		'taxonomy_termlist' => ''
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
	
	$default_blog_post_relations[ $post_id ] = $new_post_id;
	
	if( $new_post_id == 0 )
		return FALSE;
	
	restore_current_blog();
	
	// Setting taxonomies for post
	if( is_array( $taxonomy_termlist ) && count( $taxonomy_termlist ) > 0 ):
		
		foreach ( $taxonomy_termlist AS $taxonomy_name => $taxonomy ):
			
			// Getting Terms of Post
			switch_to_blog( $from_blog_id );
			$post_terms = get_the_terms( $post_id, $taxonomy_name );
			restore_current_blog();
			
			if( is_array( $post_terms ) ):
				foreach( $post_terms AS $post_term ):
					// If its no error entry
					if( is_array( $taxonomy[ $post_term->term_id ] ) ):
						switch_to_blog( $to_blog_id );
						wp_set_object_terms( (int) $new_post_id, (int) $taxonomy[ $post_term->term_id ][ 'term_id' ], $taxonomy_name );
						restore_current_blog();
					endif;
				endforeach;
			endif;
		endforeach;
	endif;
	
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
	global $default_blog_post_attachment_relations;
	
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
			
			$default_blog_post_attachment_relations[ $attachment_id ] = $new_attachment_id;
			
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
	global $default_blog_post_attachment_relations;
	
	switch_to_blog( $from_blog_id );
	$custom_fields = get_post_custom( $post_id );
	restore_current_blog();
	
	switch_to_blog( $to_blog_id );
	foreach ( $custom_fields AS $custom_field_key => $custom_field ):
		foreach ( $custom_field AS $meta_value ):
			
			// Rewriting Thumbnail ID
			if( '_thumbnail_id' ==  $custom_field_key )
				$meta_value = $default_blog_post_attachment_relations[ $meta_value ];
			
			add_post_meta( $new_post_id, $custom_field_key, $meta_value );
		endforeach;
	endforeach;
	restore_current_blog();
}

function default_blog_copy_taxonomies( $post_type, $from_blog_id, $to_blog_id ){
	switch_to_blog( $from_blog_id );
	$taxonomies = get_taxonomies( array( 'object_type' => array( $post_type ) ), 'objects' );
	restore_current_blog();
	
	// Taxonomies
	foreach( $taxonomies AS $taxonomy ):
		$new_termlist[ $taxonomy->name ] = default_blog_copy_taxonomy( $taxonomy->name, $post_type, $from_blog_id, $to_blog_id );
	endforeach;
	
	return $new_termlist;
}

function default_blog_copy_taxonomy( $taxonomy_name, $post_type, $from_blog_id, $to_blog_id, $args = array() ){
	global $default_blog_template;
	
	// Setting Arguments
	$defaults = array(
		'term_ids' => $default_blog_template[ $post_type . '_taxonomies' ][ $taxonomy_name ]
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	switch_to_blog( $from_blog_id );
	$terms = get_terms( $taxonomy_name, array( 'hide_empty' => FALSE ) );
	restore_current_blog();
	
	if( count( $terms ) > 0 && is_array( $terms ) ):
		foreach( $terms AS $term ):
			if( is_array( $term_ids ) )
				if( in_array( $term->term_id, $term_ids ) ):
					$new_terms[ $term->term_id ] = default_blog_copy_term( $term->term_id, $taxonomy_name, $from_blog_id, $to_blog_id );
				endif;
		endforeach;
	endif;
	
	return $new_terms; 
}

function default_blog_copy_term( $term_id, $taxonomy_name, $from_blog_id, $to_blog_id ){
	global $default_blog_term_relations;
	
	switch_to_blog( $from_blog_id );
	$term = (array) get_term_by( 'id', $term_id, $taxonomy_name );
	restore_current_blog();
	
	switch_to_blog( $to_blog_id );
	unset( $term[ 'term_id' ] );
	$new_term = wp_insert_term( $term[ 'name' ], $taxonomy_name, $term );
	restore_current_blog();
	
	// Return have to be Array, else it's an error
	if( is_object( $new_term ) )
		return FALSE;
	
	$default_blog_term_relations[ $term_id ] = $new_term[ 'term_id' ];
	
	return $new_term;
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

