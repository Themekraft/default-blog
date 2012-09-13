<?php

add_dfb_plugin( 'links', __( 'Links', 'default-blog-options' ), 'default_blog_links_admin', 'default_blog_links_copy', 'default_blog_links_save', 10 );

function default_blog_links_admin(){ 
	global $wpdb, $default_blog_template;
	
	switch_to_blog( DFB_TEMPLATE_EDIT_BLOG_ID );
	
	$args = array(
    	'limit' => -1  
	); 
	
	$bookmarks = get_bookmarks( $args );
	
	// Table head		
	$content = '<table class="widefat">';
	
	$content.= '<thead>';
		$content.= '<tr>';
			$content.= '<th>' . __( 'Name', 'default-blog-options' ) . '</th>';	
			$content.= '<th>' . __( 'URL', 'default-blog-options') .'</th>';
			$content.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
			
        $content.= '</tr>';
	$content.= '</thead>';
	
	$content.= '<tbody>';
	
	$checked_links = $default_blog_template[ 'links' ];
	
	foreach( $bookmarks AS $bookmark ):
		$checked = '';
		
		// Is Post checked for a copy?
		if( is_array( $checked_links ) )
			if( in_array( $bookmark->link_id, $checked_links ) ) 
				$checked = ' checked';
		
		$content.= '<tr>';
			$content.= '<td>' . $bookmark->link_name . '</th>';	
			$content.= '<td>' . $bookmark->link_url . '</td>';
			$content.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][links][]" value="' . $bookmark->link_id . '"' . $checked . ' ></td>';
        $content.= '</tr>';
	endforeach;
		
	$content.= '</tbody>';
	$content.= '</table>';
	
	if( $default_blog_template[ 'links_delete_existing' ] )
		$links_delete_existing_checked = ' checked="checked"';
		
		$content.= '<p><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][links_delete_existing]" value="true"' . $links_delete_existing_checked . ' /> ';
		$content.=  __( 'Delete automatic generated WordPress entries.', 'default_blog_options' ) . '</p>';
	
	$content = apply_filters( 'default_blog_links_admin', $content );
	
	echo $content;
	
	do_action( 'default_blog_links_admin_bottom' );
	
	restore_current_blog();
}
function default_blog_links_copy( $from_blog_id, $to_blog_id ){
	global $default_blog_template;
	
	if( !is_array( $default_blog_template[ 'links' ] ) )
		return FALSE;
	
	// Deleting existing links
	if( $default_blog_template[ 'links_delete_existing' ] ):
		switch_to_blog( $to_blog_id );
			$args = array(
		    	'limit' => -1
			); 
			
			$bookmarks = get_bookmarks( $args );
			foreach( $bookmarks AS $bookmark )
				wp_delete_link( $bookmark->link_id );
				
		restore_current_blog();
	endif;
		
	
	$links = implode( ',', $default_blog_template[ 'links' ] );
	
	$args = array(
    	'limit' => -1,
    	'include' => $links,
	); 
	
	switch_to_blog( $from_blog_id );
		$bookmarks = get_bookmarks( $args );
	restore_current_blog();
	
	foreach( $bookmarks AS $bookmark ):
		$bookmark = (array) $bookmark;
		unset( $bookmark[ 'link_id' ] );
		
		switch_to_blog( $to_blog_id );
		$link_id = wp_insert_link( $bookmark, TRUE );
		restore_current_blog();
		
	endforeach;
}
function default_blog_links_save( $input ){
	return $input;
}
