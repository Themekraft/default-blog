<div class="tab-head">   
    <h2><?php _e('Plugins','default-blog-options'); ?></h2>
    <p><?php _e('Select the plugin settings which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>    	

<div class="tab-content">   
    <table class="widefat post fixed">
	<?php
	
	$blog_plugins=$defblog_templates[$template_id]['plugins'];

  	if($_POST['plugins']!="" && !isset($_POST['defblog_submit'])){$blog_plugins=$_POST['plugins'];}
  	
  	// Plugins
	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Setting','default-blog-options')."</th>";	
	echo "<th>&nbsp;</th>";		   
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	    
	echo "</tr>";
		
    echo "<tr><td scope='row'><label for='theme'>".__('Activate Plugins','default-blog-options')."</label></td>";
    echo "<td>&nbsp;</td>";	
   	if($blog_plugins["active"]==true){
   		echo '<td><INPUT NAME="plugins[active]" TYPE="CHECKBOX" VALUE="true" checked></td>';
   	} else {
		echo '<td><INPUT NAME="plugins[active]" TYPE="CHECKBOX" VALUE="false"></td>';
   	}
   	echo "</tr>";
   	
    ?>
    </table>
    <p><input  class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>" /></p>	
</div>