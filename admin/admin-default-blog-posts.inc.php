<div class="tab-head">   
    <h2><?php _e('Blog posts','default-blog-options'); ?></h2>
    <p><?php _e('Select the posts which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>

<div class="tab-content">   
      <table class="widefat post fixed">
      <?php
	  
		$selected_blog_posts=$defblog_templates[$template_id]['posts'];
		$delete_existing_posts=$defblog_templates[$template_id]['del_posts'];
	  
  		if($_POST['posts']!="" && !isset($_POST['defblog_submit'])){$selected_blog_posts = $_POST['posts'];}

  		$sql="SELECT * FROM ".$wpdb->base_prefix.$defblog_id."_posts WHERE post_type='post' ORDER BY post_title, post_status";
  		
      	$posts = $wpdb->get_results($sql);
      	
		$class = 'all-options disabled';
		
		echo "<tr style='background-color:#CCC;'>";
		echo "<th>".__('Post','default-blog-options')."</th>";	
		echo "<th>".__('Status','default-blog-options')."</th>";
		echo "<th>".__('Slug','default-blog-options')."</th>";
		echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	       
        echo "</tr>";
		
		echo "<tr style='background-color:#FFFBCC;'>";
        echo "<td scope='row' colspan='3'><label for='delete_existing_posts'>".__('Delete existing posts','default-blog-options')."</label></td>";
		 if($delete_existing_posts==true){
        	echo "<td><INPUT NAME='delete_existing_posts' TYPE=\"CHECKBOX\" value=\"true\" checked></td>";
        }else{
        	echo "<td><INPUT NAME='delete_existing_posts' TYPE=\"CHECKBOX\" value=\"true\"></td>";
        }
		echo "</tr>";
		      	     	
        foreach((array) $posts as $post){
			if($post->post_status=="trash"){
	        	echo "<tr style='background-color:#F1F1F1; color:#999;'>";
			}else{
				echo "<tr>";
			}
        	echo "<td scope='row'><label for='".$post->post_title."'>".$post->post_title."</label></td>";     	 	
        	echo "<td scope='row'>".$post->post_status."</td>";
        	echo "<td><input class='regular-text $class' type='text' name='$post->post_name' id='$post->post_name' value='" .$post->post_name. "' disabled='disabled' /></td>";			
        	echo "<td scope='row'>";
        	if(is_array($selected_blog_posts)){
		      	if(in_array($post->ID,$selected_blog_posts)){
		      		echo '<INPUT NAME="posts[]" TYPE="CHECKBOX" VALUE="'.$post->ID.'" checked>';
		      	}else{
		      		echo '<INPUT NAME="posts[]" TYPE="CHECKBOX" VALUE="'.$post->ID.'">';
		    	}
        	}else{
        		echo '<INPUT NAME="posts[]" TYPE="CHECKBOX" VALUE="'.$post->ID.'">';
        	}
        	echo "</td>";
        	echo "</tr>";
        }
      
      ?>
      </table>
      <p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>"  /></p>
</div> 