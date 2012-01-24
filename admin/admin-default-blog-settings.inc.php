<div class="tab-head">   
    <h2><?php _e('Blog settings','default-blog-options'); ?></h2>
    <p><?php _e('Select the settings which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>

<div class="tab-content">       
    <table class="widefat post fixed">
    <?php
	
    $blog_settings=$defblog_templates[$template_id]['settings'];

    if($_POST['settings']!="" && !isset($_POST['defblog_submit'])){$blog_settings=$_POST['settings'];}
        
    // Welcome page
    switch_to_blog($defblog_id);
    $page_title=get_the_title(get_blog_option($defblog_id,'page_on_front'));
    restore_current_blog();
	
	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Setting','default-blog-options')."</th>";	
	echo "<th>".__('Value','default-blog-options')."</th>";	   
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	    
	echo "</tr>";	
    
    echo "<tr><td scope='row'><label for='theme'>".__('Welcome page','default-blog-options')."</label></td>";
    
    if($blog_settings["welcome_page"]==true){
		echo "<td>".$page_title."</td>";
        echo '<td><INPUT NAME="settings[welcome_page]" TYPE="CHECKBOX" VALUE="true" checked></td>';
    } else {
//		echo "???";
		if($page_title!=""){
			echo "<td>".$page_title."</td>";
	        echo '<td><INPUT NAME="settings[welcome_page]" TYPE="CHECKBOX" VALUE="false"></td>';
		}else{
			echo "<td>".__('N/A','default-blog-options')."</td>";
			echo '<td>'.__('No start page set.','default-blog-options').'</td>';			
		}
    }
    echo "</tr>";
    
    ?>
    </table>
    <p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>"  /></p>	
</div>