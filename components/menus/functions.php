<?php

add_dfb_plugin( 'menus', __( 'Menus', 'default-blog-options' ), 'default_blog_menus_admin', 'default_blog_menus_copy', 'default_blog_menus_save', 10 );

function default_blog_menus_admin(){ 
	global $wpdb, $default_blog_template;
	
	switch_to_blog( DFB_TEMPLATE_EDIT_BLOG_ID );
	
	$nav_menus = wp_get_nav_menus( array('orderby' => 'name') );
	
	$checked_nav_menus = $default_blog_template[ 'nav_menu' ];
	
	$elements = array();
	
	$content = '<h3>' . __( 'Menus', 'default-blog-options' )  . '</h3>';
	$content.= '<p>' . __( 'Select the menus you want to copy.', 'default-blog-options' )  . '<p>';
	
	$content.= '<table class="widefat">';
		
	$content.= '<thead>';
	$content.= '<tr>';
		$content.= '<th>' . __( 'Menu', 'default-blog-options' ) . '</th>';	
		$content.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
    $content.= '</tr>';
	$content.= '</thead>';
	
	$content.= '<tbody>';
	foreach( $nav_menus AS $nav_menu ):
		$nav_menu_checked = '';
		
		// Is Nav Menu checked for a copy?
		if( is_array( $checked_nav_menus ) )
			if( in_array(  $nav_menu->term_id, $checked_nav_menus ) ) 
				$nav_menu_checked = ' checked';
		
		$content.= '<tr>';
			$content.= '<td>' . $nav_menu->name . '</td>';	
			$content.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][nav_menu][]" value="' . $nav_menu->term_id . '"' . $nav_menu_checked . ' /></td>';
        $content.= '</tr>';
	endforeach;
	
	$content.= '</tbody>';
	$content.= '</table><br />';
	
	foreach( $default_blog_template[ 'nav_menu' ] AS $nav_menu_id ):
		
		$nav_menu = wp_get_nav_menu_object( $nav_menu_id );
		$nav_menu_items = wp_get_nav_menu_items( $nav_menu_id );
		$checked_nav_menu_items = $default_blog_template[ 'nav_menu_items' ][ $nav_menu_id ];
		
		$content_tab = '<h3>' . __( 'Menu Items', 'default-blog-options' )  . '</h3>';
		$content_tab.= '<p>' . __( 'Select the menu items you want to copy.', 'default-blog-options' )  . '<p>';
		
		$content_tab.= '<table class="widefat">';
		
		$content_tab.= '<thead>';
		$content_tab.= '<tr>';
			$content_tab.= '<th>' . __( 'Title', 'default-blog-options' ) . '</th>';	
			$content_tab.= '<th>' . __( 'Copy', 'default-blog-options' ) . '</th>';
        $content_tab.= '</tr>';
		$content_tab.= '</thead>';
		
		$content_tab.= '<tbody>';
		
		foreach( $nav_menu_items AS $nav_menu_item ):
		
			$nav_menu_item_checked = '';
			
			// Is Nav Menu Item checked for a copy?
			if( is_array( $checked_nav_menu_items ) )
				if( in_array( $nav_menu_item->ID, $checked_nav_menu_items ) ) 
					$nav_menu_item_checked = ' checked';
			
			$content_tab.= '<tr>';
				$content_tab.= '<td>' .$nav_menu_item->title . '</td>';	
				$content_tab.= '<td><input type="checkbox" name="' . DFB_OPTION_GROUP . '[' . DFB_TEMPLATE_EDIT_ID . '][nav_menu_items][' . $nav_menu->term_id . '][]" value="' . $nav_menu_item->ID . '"' . $nav_menu_item_checked . ' /></td>';
	        $content_tab.= '</tr>';
		endforeach;
		
		$content_tab.= '</table>';
		
		$elements[] = array(
			'id' => 'dfb_' . $nav_menu->slug,
			'title' => $nav_menu->name,
			'content' => $content_tab
		);	
	endforeach;
	
	$content.= tk_tabs( 'default_blog_menu_tabs', $elements, 'html' );
		
	$content = apply_filters( 'default_blog_menus_admin', $content );
	
	echo $content;
	
	do_action( 'default_blog_links_admin_bottom' );
	
	restore_current_blog();
}

function default_blog_menus_copy( $from_blog_id, $to_blog_id ){
	global $default_blog_template, $default_blog_menu_references;
	
	$checked_nav_menus = $default_blog_template[ 'nav_menu' ];
	
	// Copy all Nav Menus
	foreach( $checked_nav_menus AS $nav_menu_id ):
		
		// Getting actual Nav Menu
		switch_to_blog( $from_blog_id );
		$nav_menu = wp_get_nav_menu_object( $nav_menu_id );
		restore_current_blog();
		
		// Creating Nav Menu
		switch_to_blog( $to_blog_id );
		$new_nav_menu_id = wp_create_nav_menu( $nav_menu->name );
		restore_current_blog();
		
		$default_blog_menu_references[ $nav_menu->term_id ] = $new_nav_menu_id;
		
		// Copy all Items of Menu
		default_blog_copy_menu_items( $from_blog_id, $to_blog_id, $nav_menu->term_id, $new_nav_menu_id );
	endforeach;
}
function default_blog_copy_menu_items( $from_blog_id, $to_blog_id, $from_nav_menu_id, $to_nav_menu_id, $args = array() ){
	global $default_blog_template;
	
	$defaults = array(
		'menu_item_ids' => $default_blog_template[ 'nav_menu_items' ][ $from_nav_menu_id ]
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	// Creating Nav Menu items
	if( is_array( $menu_item_ids ) ):
		foreach( $menu_item_ids AS $menu_item_id ):
			default_blog_copy_menu_item( $from_blog_id, $to_blog_id, $from_nav_menu_id, $to_nav_menu_id, $menu_item_id );
		endforeach;
	endif;
}
function default_blog_copy_menu_item( $from_blog_id, $to_blog_id, $from_nav_menu_id, $to_nav_menu_id, $menu_item_id ){
	global $default_blog_post_relations, $default_blog_term_relations;
	
	// Getting all posts of post type
	$args = array(
		'post_type' => 'nav_menu_item',
		'post__in' => array( $menu_item_id ) // Only taking selected posts
	);
	
	// Getting Posts from Soiurce Blog
	switch_to_blog( $from_blog_id );
	$the_query = new WP_Query( $args );
	restore_current_blog();
	
	// Running Posts of Post Type
	while ( $the_query->have_posts() ) : $the_query->the_post();
		global $post;
	
		switch_to_blog( $from_blog_id );
		$nav_menu_item_post = wp_setup_nav_menu_item( $post );
		restore_current_blog();
		
		if( 'post_type' == $nav_menu_item_post->type )
			$object_id = $default_blog_post_relations[ $nav_menu_item_post->object_id ];
			
		if( 'taxonomy' == $nav_menu_item_post->type )
			$object_id = $default_blog_term_relations[ $nav_menu_item_post->object_id ];
			
		if( 'custom' == $nav_menu_item_post->type )
			$object_id = 0;
	
		$menu_item_data = array(
			'menu-item-object-id' => $object_id,
			'menu-item-object' => $nav_menu_item_post->object,
			'menu-item-parent-id' => $nav_menu_item_post->menu_item_parent,
			'menu-item-position' => $nav_menu_item_post->menu_order,
			'menu-item-type' => $nav_menu_item_post->type,
			'menu-item-title' => $nav_menu_item_post->title,
			'menu-item-url' => $nav_menu_item_post->url,
			'menu-item-description' =>  $nav_menu_item_post->description,
			'menu-item-attr-title' => $nav_menu_item_post->attr_title,
			'menu-item-target' =>  $nav_menu_item_post->target,
			'menu-item-classes' => implode( ' ', $nav_menu_item_post->classes ),
			'menu-item-xfn' => $nav_menu_item_post->xfn,
			'menu-item-status' => $nav_menu_item_post->post_status,
		);
		
		switch_to_blog( $to_blog_id );
		wp_update_nav_menu_item( $to_nav_menu_id, 0, $menu_item_data );
		restore_current_blog();
	endwhile;
}
function default_blog_get_menu_item( $blog_id, $nav_menu_id, $menu_item_id ){
	
	// Getting Nav Menu Object
	switch_to_blog( $blog_id );
		$nav_menu_items = wp_get_nav_menu_items( $nav_menu_id );
		
		if( is_array( $nav_menu_items ) ):
			foreach( $nav_menu_items AS $nav_menu_item ):
				if( $nav_menu_item->term_id == $menu_item_id )
					break;
			endforeach;
		endif;
	restore_current_blog();
	
	return $nav_menu_item;
}
// REMEMBER to copy locations! get_nav_menu_locations

function default_blog_get_nav_menu_item_from_post( $post ){
	
}
function default_blog_menus_save( $input ){
	return $input;
}
