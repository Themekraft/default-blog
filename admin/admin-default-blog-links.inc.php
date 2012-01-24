<div class="tab-head">    
    <h2><?php _e('Blog links','default-blog-options'); ?></h2>
    <p><?php _e('Select the links which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>    

<div class="tab-content">      
    <table class="widefat post fixed">
    <?php 
	
	$selected_blog_links=$defblog_templates[$template_id]['links'];
	$delete_existing_links=$defblog_templates[$template_id]['del_links'];

    if($_POST['links'] != "" && !isset($_POST['defblog_submit'])){$selected_blog_links = $_POST['links'];}
    
    $sql="SELECT * FROM ".$wpdb->base_prefix.$defblog_id."_links ORDER BY link_name";
    
    $links = $wpdb->get_results($sql);
    
    $class = 'all-options disabled';
	
	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Link','default-blog-options')."</th>";	
	echo "<th>".__('URL','default-blog-options')."</th>";	
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	       
	echo "</tr>";	
    
    echo "<tr style='background-color:#FFFBCC;'>";
    echo "<td scope='row'><label for='delete_existing_links'>".__('Delete existing links','default-blog-options')."</label></td>";
    echo "<td>&nbsp;</td>";	
    if($delete_existing_links==true){
        echo "<td><INPUT NAME='delete_existing_links' TYPE=\"CHECKBOX\" value=\"true\" checked></td>";
    }else{
        echo "<td><INPUT NAME='delete_existing_links' TYPE=\"CHECKBOX\" value=\"true\"></td>";
    }
    echo "</tr>";
                    
    foreach((array) $links as $link){
        echo "<tr>";
        echo "<td scope='row'><label for='$link->link_name'>$link->link_name</label></td>";    	 	
        echo "<td><input class='regular-text $class' type='text' name='$link->link_url' id='$link->link_url' value='" .$link->link_url. "' disabled='disabled' /></td>";
        echo "<td>";
        if(isset($selected_blog_links)){
            if(in_array($link->link_id,$selected_blog_links)){
                echo '<INPUT NAME="links[]" TYPE="CHECKBOX" VALUE="'.$link->link_id.'" checked>';
            }else{
                echo '<INPUT NAME="links[]" TYPE="CHECKBOX" VALUE="'.$link->link_id.'">';
            }
        }else{
            echo '<INPUT NAME="links[]" TYPE="CHECKBOX" VALUE="'.$link->link_id.'">';
        }
        echo "</td>";
        echo "</tr>";
    }
    
    ?>
    </table>
    <p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>"  /></p>
</div>