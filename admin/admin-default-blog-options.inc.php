<div class="tab-head">    
    <h2><?php _e('Blog options','default-blog-options'); ?></h2>
    <p><?php _e('Select the blog options which have to be copied from default blog into a new blog.','default-blog-options'); ?></p>
</div>
      
<div class="tab-content">          
    <table class="widefat post fixed">
    <?php
	
	$blog_options=$defblog_templates[$template_id]['options'];

    if($_POST['options']!="" && !isset($_POST['defblog_submit'])){$blog_options=$_POST['options'];}
	
	echo "<tr style='background-color:#CCC;'>";
	echo "<th>".__('Option','default-blog-options')."</th";	
	echo "<th>".__('Value','default-blog-options')."</th>";		   
	echo "<th>".__('Take it (checked)','default-blog-options')."</th>";	    
	echo "</tr>";
	
    $options = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix.$defblog_id."_options ORDER BY option_name");
    foreach((array) $options as $option) :
        $disabled = '';
        $option->option_name = esc_attr($option->option_name);
        if($option->option_name=='')
            continue;
        if(is_serialized($option->option_value)){
            if(is_serialized_string($option->option_value)){
                // this is a serialized string, so we should display it
                $value = maybe_unserialize($option->option_value);
                $class = 'all-options disabled';
                } else {
                $value = 'SERIALIZED DATA';
                $disabled = ' disabled="disabled"';
                $class = 'all-options disabled';
            }
        }else{
            $value = $option->option_value;
            $class = 'all-options disabled';
        }
        echo "
    <tr>
        <td scope='row'><label for='$option->option_name'>$option->option_name</label></td>
    <td>";
        if (strpos($value, "\n") !== false) echo "<textarea class='$class' name='$option->option_name' id='$option->option_name' cols='30' rows='5'>" . esc_html($value) . "</textarea>";
        else echo "<input class='regular-text $class' type='text' name='$option->option_name' id='$option->option_name' value='" . esc_attr($value) . "'$disabled />";
        echo "</td><td>";
        if(isset($blog_options)){
        if(in_array($option->option_name,$blog_options)){
            echo '<INPUT NAME="options[]" TYPE="CHECKBOX" VALUE="'.$option->option_name.'" checked>';
        } else {
            echo '<INPUT NAME="options[]" TYPE="CHECKBOX" VALUE="'.$option->option_name.'">';
        }
        } else {
            echo '<INPUT NAME="options[]" TYPE="CHECKBOX" VALUE="'.$option->option_name.'">';
      }
        echo "</td>
        </tr>";
    endforeach;
    ?>
    </table>
    <p><input class="button-secondary action" type="submit" name="submit" value="<?php _e('Update', 'default-blog-options') ?>" /></p>	
</div>
