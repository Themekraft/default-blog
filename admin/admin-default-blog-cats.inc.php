<div class="tab-head">
	<h2><?php _e('Blog categories','default-blog-options'); ?></h2>
  	<p><?php _e('Select the categories which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>
  
<div class="tab-content">  
  <table class="widefat post fixed">
  <?php
  
	$selected_blog_cats=$defblog_templates[$template_id]['cats'];
	$delete_existing_cats=$defblog_templates[$template_id]['del_cats'];
	
	// echo "Selected blog cats:";
	// print_r($selected_blog_cats);
	  
    if($_POST['cats']!="" && !isset($_POST['defblog_submit'])){$selected_blog_cats = $_POST['cats'];}
       
    $args = array(
    'type'                     => 'post',
    'child_of'                 => 0,
    'orderby'                  => 'name',
    'order'                    => 'ASC',
    'hide_empty'               => false,
    'pad_counts'               => false );
    
    switch_to_blog($defblog_id);
    $cats=get_categories($args);
    restore_current_blog();
    
    // print_r($cats);
    
    $class = 'all-options disabled';
	
	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Category','default-blog-options')."</th>";	
	echo "<th>".__('Slug','default-blog-options')."</th>";
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	       
	echo "</tr>";
    
	/*
    echo "<tr style='background-color:#FFFBCC;'>";
    echo "<td scope='row'><label for='delete_existing_posts'>".__('Delete existing categories','default-blog-options')."</label></td>";
    echo "<td>&nbsp;</td>";	
    if($delete_existing_cats==true){
        echo "<td><INPUT NAME='delete_existing_cats' TYPE=\"CHECKBOX\" value=\"true\" checked></td>";
    }else{
        echo "<td><INPUT NAME='delete_existing_cats' TYPE=\"CHECKBOX\" value=\"true\"></td>";
    }
    echo "</tr>";
	*/
                    
    foreach($cats as $cat){
        echo "<tr>";
        echo "<td scope='row'><label for='".$cat->cat_name."'>".$cat->cat_name."</label></td>";    	 	
        echo "<td><input class='regular-text $class' type='text' name='$cat->cat_name' id='$link->cat_name' value='" .$cat->slug. "' disabled='disabled' /></td>";
        echo "<td>";
        if(isset($selected_blog_cats)){
			if(is_array($selected_blog_cats)){
				if(in_array($cat->cat_ID,$selected_blog_cats)){
					echo '<INPUT NAME="cats[]" TYPE="CHECKBOX" VALUE="'.$cat->cat_ID.'" checked>';
				}else{
					echo '<INPUT NAME="cats[]" TYPE="CHECKBOX" VALUE="'.$cat->cat_ID.'">';
				}
			}else{
				echo '<INPUT NAME="cats[]" TYPE="CHECKBOX" VALUE="'.$cat->cat_ID.'">';
			}
        }else{
            echo '<INPUT NAME="cats[]" TYPE="CHECKBOX" VALUE="'.$cat->cat_ID.'">';
        }
        echo "</td>";
        echo "</tr>";
    }
  
  ?>
  </table>
  <p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>"  /></p>
</div> 