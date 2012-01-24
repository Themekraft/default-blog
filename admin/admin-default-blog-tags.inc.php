<div class="tab-head">
    <h2><?php _e('Blog tags','default-blog-options'); ?></h2>
    <p><?php _e('Select the tags which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>

<div class="tab-content">         
    <table class="widefat post fixed">
    <?php
	
	$selected_blog_tags=$defblog_templates[$template_id]['tags'];
	$delete_existing_tags=$defblog_templates[$template_id]['del_tags'];
		
    if($_POST['tags']!="" && !isset($_POST['defblog_submit'])){$selected_blog_tags = $_POST['tags'];}
    
    $args = array(
    'type'                     => 'post',
    'child_of'                 => 0,
    'orderby'                  => 'name',
    'order'                    => 'ASC',
    'hide_empty'               => false,
    'pad_counts'               => false );
    
    switch_to_blog($defblog_id);
    $tags=get_tags($args);
    restore_current_blog();
    
    $class = 'all-options disabled';

	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Tag','default-blog-options')."</th>";	
	echo "<th>".__('Slug','default-blog-options')."</th>";
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	       
	echo "</tr>";
    
	/*
    echo "<tr style='background-color:#FFFBCC;'>";
    echo "<td scope='row'><label for='delete_existing_tags'>".__('Delete existing tags','default-blog-options')."</label></td>";
    echo "<td>&nbsp;</td>";	
    if($delete_existing_tags==true){
        echo "<td><INPUT NAME='delete_existing_tags' TYPE=\"CHECKBOX\" value=\"true\" checked></td>";
    }else{
        echo "<td><INPUT NAME='delete_existing_tags' TYPE=\"CHECKBOX\" value=\"true\"></td>";
    }
    echo "</tr>";
    */
	              
	$i=0;
    foreach($tags as $tag){
        echo "<tr>";
        echo "<td scope='row'><label for='".$tag->name."'>".$tag->name."</label></td>";    	 	
        echo "<td><input class='regular-text $class' type='text' name='$tag->name' id='$link->name' value='" .$tag->slug. "' disabled='disabled' /></td>";
        echo "<td>";
        if(isset($selected_blog_tags)){
			if(is_array($selected_blog_tags)){
				if(in_array($tag->term_id,$selected_blog_tags)){
					echo '<INPUT NAME="tags[]" TYPE="CHECKBOX" VALUE="'.$tag->term_id.'" checked>';
				}else{
					echo '<INPUT NAME="tags[]" TYPE="CHECKBOX" VALUE="'.$tag->term_id.'">';
				}
			}else{
            	echo '<INPUT NAME="tags[]" TYPE="CHECKBOX" VALUE="'.$tag->term_id.'">';
        	}
        }else{
            echo '<INPUT NAME="tags[]" TYPE="CHECKBOX" VALUE="'.$tag->term_id.'">';
        }
        echo "</td>";
        echo "</tr>";
		$i++;
    }
	if($i==0){
		echo "<tr>";
		echo "<td scope='row' colspan='3'>".__('No tags found.','default-blog-options')."</td>";	   
		echo "</tr>";	
	}
    
    ?>
    </table>
    <p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>" /></p>
</div> 