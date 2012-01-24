<div class="tab-head">   
    <h2><?php _e('Blog pages','default-blog-options'); ?></h2>
    <p><?php _e('Select the pages which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div> 

<div class="tab-content">       
    <table class="widefat post fixed">
    <?php
	
	$selected_blog_pages=$defblog_templates[$template_id]['pages'];
	$delete_existing_pages=$defblog_templates[$template_id]['del_pages'];
    
    if($_POST['pages']!="" && !isset($_POST['defblog_submit'])){$selected_blog_pages=$_POST['pages'];}
    
    $sql="SELECT * FROM ".$wpdb->base_prefix.$defblog_id."_posts WHERE post_type='page' ORDER BY post_title";
    
    $pages = $wpdb->get_results($sql);
    
    $class = 'all-options disabled';
	
	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Page','default-blog-options')."</th>";	
	echo "<th>".__('Status','default-blog-options')."</th>";		
	echo "<th>".__('Slug','default-blog-options')."</th>";	
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	       
	echo "</tr>";	
    
    echo "<tr style='background-color:#FFFBCC;'>";
    echo "<td scope='row'><label for='delete_existing_pages'>".__('Delete existing pages','default-blog-options')."</label></td>";
	echo "<td>&nbsp;</td>";	
    echo "<td>&nbsp;</td>";	
    if($delete_existing_pages==true){
        echo "<td><INPUT NAME='delete_existing_pages' TYPE=\"CHECKBOX\" value=\"true\" checked></td>";
    }else{
        echo "<td><INPUT NAME='delete_existing_pages' TYPE=\"CHECKBOX\" value=\"true\"></td>";
    }
    echo "</tr>";
                    
    foreach((array) $pages as $page){
		if($page->post_status=="trash"){
			echo "<tr style='background-color:#F1F1F1; color:#999;'>";
		}else{
			echo "<tr>";
		}
        echo "<td scope='row'><label for='".$page->post_title."'>".$page->post_title."</label></td>";   
		echo "<td scope='row'>".$page->post_status."</td>";    	 	
        echo "<td><input class='regular-text $class' type='text' name='$page->post_name' id='$page->post_name' value='" .$page->post_name. "' disabled='disabled' /></td>";
        echo "<td>";
        if(isset($selected_blog_pages)){
            if(in_array($page->ID,$selected_blog_pages)){
                echo '<INPUT NAME="pages[]" TYPE="CHECKBOX" VALUE="'.$page->ID.'" checked>';
            }else{
                echo '<INPUT NAME="pages[]" TYPE="CHECKBOX" VALUE="'.$page->ID.'">';
            }
        }else{
            echo '<INPUT NAME="pages[]" TYPE="CHECKBOX" VALUE="'.$page->ID.'">';
        }
        echo "</td>";
        echo "</tr>";
    }
    
    ?>
    </table>
    <p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>"  /></p>
</div>
