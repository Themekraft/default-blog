<div class="tab-head">
    <h2><?php _e('Appearance','default-blog-options'); ?></h2>
    <p><?php _e('Select the appearance settings which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>

<div class="tab-content">  
    <table class="widefat post fixed">
    <?php
	
	$blog_appearance=$defblog_templates[$template_id]['appearance'];
	
    if($_POST['appearance']!="" && !isset($_POST['defblog_submit'])){$blog_appearance=$_POST['appearance'];}
    
    // print_r($blog_settings);
    
    // Theme
	
	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Setting','default-blog-options')."</th>";	
	echo "<th>".__('Value','default-blog-options')."</th>";		   
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	    
	echo "</tr>";
	
    echo "<tr><td scope='row'><label for='theme'>".__('Set up Theme','default-blog-options')."</label></td>";
    echo "<td>".get_blog_option($defblog_id,'current_theme')."</td>";
    if($blog_appearance["theme"]==true){
        echo '<td><INPUT NAME="appearance[theme]" TYPE="CHECKBOX" VALUE="true" checked></td>';
    } else {
        echo '<td><INPUT NAME="appearance[theme]" TYPE="CHECKBOX" VALUE="false"></td>';
    }
    echo "</tr>";
    
    ?>
    </table>
	<p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>"  /></p>	
</div>