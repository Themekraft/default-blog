<?php

// Setting up data
function defblog_vars(){
	global $defblog_templates,$defblog_settings;
	
	$defblog_templates=get_site_option('defblog_templates');
	$defblog_settings=get_site_option('defblog_settings');
	
	// If older version was installed 
	if($defblog_settings==""){

		$defblog_id=get_site_option('defblog_id');
		$template_id=get_template_id();
		$initialized_blog_plugin=get_site_option('default_blog_plugin_initialized');

		$selected_blog_posts = get_site_option('default_blog_posts');
		$delete_existing_posts = get_site_option('default_blog_posts_delete');
		
	 	$selected_blog_pages = get_site_option('default_blog_pages');
		$delete_existing_pages = get_site_option('default_blog_pages_delete');
		
		$selected_blog_cats = get_site_option('default_blog_cats');
		$delete_existing_cats = get_site_option('default_blog_cats_delete');
		
		$selected_blog_tags = get_site_option('default_blog_tags');
		$delete_existing_tags = get_site_option('default_blog_tags_delete');
		
		$selected_blog_links = get_site_option('default_blog_links');
	 	$delete_existing_links = get_site_option('default_blog_link_delete');
		
		$blog_appearance = get_site_option('default_blog_design');	
		$blog_settings = get_site_option('default_blog_settings');		
		$blog_plugins = get_site_option('default_blog_plugins');		
	 	$blog_options = get_site_option('global_blog_options');
		
		delete_site_option('defblog_id');
		delete_site_option('default_blog_plugin_initialized');
		delete_site_option('default_blog_posts');
		delete_site_option('default_blog_posts_delete');
		delete_site_option('default_blog_pages');
		delete_site_option('default_blog_pages_delete');
		delete_site_option('default_blog_cats');
		delete_site_option('default_blog_cats_delete');
		delete_site_option('default_blog_tags');
		delete_site_option('default_blog_tags_delete');
		delete_site_option('default_blog_links');
		delete_site_option('default_blog_link_delete');
		delete_site_option('default_blog_design');
		delete_site_option('default_blog_settings');
		delete_site_option('default_blog_plugins');
		delete_site_option('global_blog_options');
		
		// Writing data in new array
		$defblog_settings['init']=true;
		$defblog_settings['act_template_id']=$template_id;
		
		$defblog_templates[$template_id]['id']=$defblog_id;
				
		$defblog_templates[$template_id]['posts']=$selected_blog_posts;
		$defblog_templates[$template_id]['del_posts']=$delete_existing_posts;
		
		$defblog_templates[$template_id]['pages']=$selected_blog_pages;
		$defblog_templates[$template_id]['del_pages']=$delete_existing_pages;
		
		$defblog_templates[$template_id]['cats']=$selected_blog_cats;
		$defblog_templates[$template_id]['del_cats']=$delete_existing_cats;
		
		$defblog_templates[$template_id]['tags']=$selected_blog_tags;
		$defblog_templates[$template_id]['del_tags']=$delete_existing_tags;
		
		$defblog_templates[$template_id]['links']=$selected_blog_links;
		$defblog_templates[$template_id]['del_links']=$delete_existing_links;
		
		$defblog_templates[$template_id]['appearance']=$blog_appearance;
		$defblog_templates[$template_id]['settings']=$blog_settings;
		$defblog_templates[$template_id]['plugins']=$blog_plugins;
		$defblog_templates[$template_id]['options']=$blog_options;
		
		// Saving data			
		update_site_option("defblog_templates",$defblog_templates);
		update_site_option("defblog_settings",$defblog_settings);
	}
}
// Updating defblog settings
function update_defblog_settings($settings){
	global $defblog_settings;
	$defblog_settings=$settings;
	update_site_option("defblog_settings",$defblog_settings);	
}
// Updating defblog templates
function update_defblog_templates($templates){
	global $defblog_templates;
	$defblog_templates=$templates;
	update_site_option("defblog_templates",$defblog_templates);
}
// Updating links of new blog
function copy_links($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates, $wpdb;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	$link_fields=array('link_url','link_name','link_image','link_target','link_description','link_visible','link_updated','link_rel','link_notes','link_rss');

	// Getting default blog settings
	$links=$defblog_templates[$template_id]['links'];
	$default_blog_link_delete=$defblog_templates[$template_id]['del_links'];
	
	if($default_blog_link_delete==true){
		$sql="TRUNCATE TABLE ".$wpdb->base_prefix.$to_blog_id."_links";
		$wpdb->query($sql);
	}
	
	// Adding links
	if(is_array($links)){
		foreach($links AS $link){
				$sql="SELECT * FROM ".$wpdb->base_prefix.$from_blog_id."_links WHERE link_id='".$link."'";
				$default_link = $wpdb->get_row($sql, ARRAY_A);
				$sql_old=$sql;
				
				$sql="INSERT INTO ".$wpdb->base_prefix.$to_blog_id."_links ";
				$i=0;
	
				foreach($link_fields AS $link_field){
					if($i==0){
						$sql_fields=$link_field;
						$sql_values="'".$default_link[$link_field]."'";
					}else{
						$sql_fields.=",".$link_field;
						$sql_values.=",'".$default_link[$link_field]."'";
					}
					$i++;
				}
				$sql.="(".$sql_fields.") VALUES (".$sql_values.")";
				
				$wpdb->query($sql);
		}
	}
}

// Updating posts of new blog
function copy_posts($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates,$cat_changes, $wpdb, $old_tag_ids;	
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];

	$post_fields=array('post_author','post_date','post_date_gmt','post_content','post_title','post_excerpt','post_status','comment_status','ping_status','post_password','post_name','to_ping','pinged','post_modified','post_modified_gmt','post_content_filtered','post_parent','guid','menu_order','post_type','post_mime_type','comment_count');
	
	// Getting default blog settings
	$posts=$defblog_templates[$template_id]['posts'];
	$default_blog_posts_delete=$defblog_templates[$template_id]['del_posts'];
		
	// Deleting old posts
	if($default_blog_posts_delete==true){
		switch_to_blog($to_blog_id);
		$del_posts=get_posts("numberposts=-1&post_status=''&post_type='post'");
					
		foreach($del_posts AS $del_post){
			wp_delete_post($del_post->ID,true);
		}
		restore_current_blog();
	}
	
	if($posts!=""){
		
		switch_to_blog($from_blog_id);
		$new_posts=get_posts("numberposts=-1&include=".implode(",",$posts)."&post_status=''&post_type=any");
		restore_current_blog();
		
		// Adding posts
		if(is_array($new_posts)){
			$post_changes="";

			$i=0;
			
			// Adding pages
			foreach($new_posts AS $new_post){		
				$old_post_id=$new_post->ID;
				
				// Getting additional data for post
				switch_to_blog($from_blog_id);
				$custom_keys=get_post_custom_keys($old_post_id);
				$post_cats=wp_get_post_categories($old_post_id);
                		$post_format = get_post_format($old_post_id);
                		$post_format = $post_format === false ? 'standard' : $post_format; // If no post format was set, then it should be 'standard'
				restore_current_blog();	
							
				// Changing cats
				$new_cats=array();
				foreach($post_cats AS $post_cat){
					array_push($new_cats,$cat_changes[$post_cat]);
				}
				
				// Inserting new post
				$new_post->ID="";
				$new_post->post_category=$new_cats;

				switch_to_blog($to_blog_id);
				$new_post_id=wp_insert_post($new_post);
                		set_post_format($new_post_id, $post_format);
				restore_current_blog();	
				
				$post_changes[$old_post_id]=$new_post_id;	
				
				// Adding post_metas
				foreach($custom_keys AS $custom_key){

					switch_to_blog($from_blog_id);
			  		$post_metas=get_post_meta($old_post_id, $custom_key);
			  		restore_current_blog();
			  		
			  		foreach($post_metas AS $post_meta){
						switch_to_blog($to_blog_id);
			  			update_post_meta($new_post_id, $custom_key, $post_meta);
						restore_current_blog();	
			  		} 		  		
			  	}
			  	
			  	$args = array(
			    'orderby'                  => 'name',
			    'order'                    => 'ASC',
			    'hide_empty'               => false,
			    'pad_counts'               => false );
			  	
			  	// Adding post tags
			  	switch_to_blog($from_blog_id);		  		
				$post_tags=wp_get_post_tags($old_post_id);
				restore_current_blog();
				
				$post_tag_names=array();
	
				foreach($post_tags AS $post_tag){
					if(in_array($post_tag->term_id,$old_tag_ids)){
						array_push($post_tag_names,$post_tag->name);
					}
			  	}
				switch_to_blog($to_blog_id);		  		
			  	wp_set_post_tags($new_post_id,$post_tag_names);
				restore_current_blog();	  		
			}
		}
	}
}
// Updating pages of new blog
function copy_pages($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates, $page_changes, $wpdb;	
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	// Getting default blog settings
	$pages=$defblog_templates[$template_id]['pages'];
	$default_blog_pages_delete=$defblog_templates[$template_id]['del_pages'];
	
	// Deleting old pages
	if($default_blog_pages_delete==true){
		$args = array(
		    'child_of' => 0,
		    'hierarchical' => 0,
		    'parent' => -1,
		    'offset' => 0, 
		    'post_type'=>'page'
		);
		
		switch_to_blog($to_blog_id);		  	
		$del_pages=get_pages($args);
					
		foreach($del_pages AS $del_page){
			wp_delete_post($del_page->ID,true);
		}
		restore_current_blog();
	}
	
		
	if($pages!=""){
		
		// Getting new pages from default blog
		switch_to_blog($from_blog_id);
		$new_pages=get_pages("include=".implode(",",$pages));
		restore_current_blog();
		
		// Adding pages
		if(is_array($new_pages)){
			
			// Adding pages
			foreach($new_pages AS $new_page){
				
				$old_page_id=$new_page->ID;
				$new_page->ID="";	
				
				switch_to_blog($to_blog_id);			
				$new_page_id=wp_insert_post($new_page);
				restore_current_blog();
				
				$page_changes[$old_page_id]=$new_page_id;		
				
				// Adding post_metas
				switch_to_blog($from_blog_id);
				$custom_keys=get_post_custom_keys($old_page_id);
				restore_current_blog();
				
				foreach($custom_keys AS $custom_key){
					
					switch_to_blog($from_blog_id);
			  		$post_metas=get_post_meta($old_page_id, $custom_key);
			  		restore_current_blog();
			  		
			  		// print_r($post_metas);
			  		switch_to_blog($to_blog_id);
			  		foreach($post_metas AS $post_meta){
			  			update_post_meta($new_page_id, $custom_key, $post_meta);
			  		}
					restore_current_blog();
			  	}
			}
			
			// Seting up parent pages
			$args = array(
			    'child_of' => 0,
			    'hierarchical' => 0,
			    'parent' => -1,
			    'offset' => 0 
			);
			
			switch_to_blog($to_blog_id);
			$update_pages=get_pages($args);
			
			foreach($update_pages AS $update_page){
				if($update_page->post_parent!=0){
					$update_page->post_parent=$page_changes[$update_page->post_parent];
					wp_update_post($update_page);
				}
			}
			restore_current_blog();
	
		}
	}
}
// Updating categories of new blog
function copy_cats($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates, $cat_changes, $wpdb;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	// Getting default blog settings
	$cats=$defblog_templates[$template_id]['cats'];
	$default_blog_cats_delete=$defblog_templates[$template_id]['del_cats'];
	
	if($default_blog_cats_delete==true){
		$args = array(
	    'type'                     => 'post',
	    'child_of'                 => 0,
	    'orderby'                  => 'name',
	    'order'                    => 'ASC',
	    'hide_empty'               => false,
	    'pad_counts'               => false );
		
		switch_to_blog($to_blog_id);
  		$del_cats=get_categories($args);
  		
  		foreach($del_cats as $del_cat){
  			wp_delete_category($del_cat->cat_ID);
  		}
		restore_current_blog();
	}
	
	if($cats!="" && is_array($cats)){	
		$args = array(
		    'type'                     => 'post',
			'include'				   => implode(",",$cats),
		    'child_of'                 => 0,
		    'orderby'                  => 'name',
		    'order'                    => 'ASC',
		    'hide_empty'               => false,
		    'pad_counts'               => false );
		
		switch_to_blog($from_blog_id);
		$cats_new=get_categories($args);
	  	restore_current_blog();
		
		// Adding pages
		if(is_array($cats_new)){
			foreach($cats_new AS $cat_new){
				$cat_args = array(
				  'cat_name' => $cat_new->cat_name,
				  'category_description' => $cat_new->category_description,
				  'category_nicename' => $cat_new->category_nicename,
				  'category_parent' => $cat_new->category_parent,
				  'slug' => $cat_new->slug
				);
				$old_cat_id=$cat_new->cat_ID;
				switch_to_blog($to_blog_id);
				$new_cat_id=wp_insert_category($cat_args);
				restore_current_blog();
				$cat_changes[$old_cat_id]=$new_cat_id;
			}
		}
	}
}
// Updating tags of new blog
function copy_tags($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates, $page_changes, $tag_changes, $old_tag_ids;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	$old_tag_ids=array();
	
	// Getting default blog settings
	$tags=$defblog_templates[$template_id]['tags'];
	$default_blog_tags_delete=$defblog_templates[$template_id]['del_tags'];
	
	// Deleting old tags
	if($default_blog_tags_delete==true){
		
		$args = array(
	    'type'                     => 'post',
	    'child_of'                 => 0,
	    'orderby'                  => 'name',
	    'order'                    => 'ASC',
	    'hide_empty'               => false,
	    'pad_counts'               => false );
		
		switch_to_blog($to_blog_id);
		$tags_delete=get_tags($args);

		foreach($tags_delete AS $tag_delete){
			wp_delete_term($tag_delete->term_id,'post_tag');
		}
		restore_current_blog();
	}
	
	if($tags!="" && is_array($tags)){
		$args = array(
	    'type'                     => 'post',
		'include'				   => implode(",",$tags),
	    'child_of'                 => 0,
	    'orderby'                  => 'name',
	    'order'                    => 'ASC',
	    'hide_empty'               => false,
	    'pad_counts'               => false );
		
		switch_to_blog($from_blog_id);
	  	$tags=get_tags($args);
	  	restore_current_blog();
	  	
		foreach($tags AS $tag){
			$old_term_id=$tag->term_id;
			$args=array(
				'description' => $tag->description,
				'slug'		  => $tag->slug
			);
			
			switch_to_blog($to_blog_id);
			$new_term_id=wp_insert_term($tag->name,'post_tag',$args);
			restore_current_blog();
			
			$tag_changes[$old_term_id]=$new_term_id;
			
			array_push($old_tag_ids,$old_term_id);
		}
	}	
}
// Updating appearance of blog
function copy_appearance($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	$blog_appearance=$defblog_templates[$template_id]['appearance'];
	
	// Copy theme
	if( (boolean) $blog_appearance["theme"] == TRUE ){
		
		switch_to_blog($from_blog_id);
		$current_template=get_option('current_theme');
		$template=get_option('template');
		$current_stylesheet=get_option('stylesheet');
		$theme_mods = get_option( 'theme_mods_' . $current_stylesheet );
  		restore_current_blog();
		
		switch_to_blog($to_blog_id);
  		update_option('current_theme', $current_template);
  		update_option('template', $template);
		update_option('stylesheet', $current_stylesheet);
		update_option('theme_mods_' . $current_stylesheet, $theme_mods);
		restore_current_blog();
	}
}
// Updating plugin settings of blog
function copy_plugins($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	$blog_plugins = $defblog_templates[$template_id]['plugins'];
	
	// Copy active plugins
	if($blog_plugins["active"]==true){
		switch_to_blog($from_blog_id);
		$active_plugins=get_option('active_plugins');
	  	restore_current_blog();
	  	
		switch_to_blog($to_blog_id);
	  	update_option('active_plugins', $active_plugins);
		restore_current_blog();
	}
}
// Updating settings of blog
function copy_settings($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates, $page_changes;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	$blog_settings=$defblog_templates[$template_id]['settings'];
	
	if($blog_settings["welcome_page"]==true){
		$page_on_front=get_blog_option($from_blog_id,'page_on_front');
		$show_on_front=get_blog_option($from_blog_id,'show_on_front');
	  	
		switch_to_blog($to_blog_id);
		update_option('page_on_front',$page_changes[$page_on_front]);
		update_option('show_on_front',$show_on_front);
		restore_current_blog();
	}
	
}

// Updating options of new blog
function copy_options($from_blog_id,$to_blog_id){
	global $defblog_settings, $defblog_templates, $wp_rewrite;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
		
	$options=$defblog_templates[$template_id]['options'];
	
	if(is_array($options)){
		foreach($options AS $option){
			switch_to_blog( $to_blog_id );
			if( $option == 'permalink_structure' ):
				$wp_rewrite->set_permalink_structure( get_blog_option( $from_blog_id, $option ) );
			elseif( $option == 'category_base' ):
				$wp_rewrite->set_category_base( get_blog_option( $from_blog_id, $option ) );
			elseif( $option == 'tag_base' ):
				$wp_rewrite->set_tag_base( get_blog_option( $from_blog_id, $option ) );
			else:
				update_option( $option, get_blog_option( $from_blog_id, $option ) );
			endif;
			
			create_initial_taxonomies();
			
			$wp_rewrite->flush_rules();
			
			restore_current_blog();
		}
	}
}
?>