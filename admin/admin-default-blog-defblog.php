<div class="tab-head">
    <h2><?php _e('Select blog template','default-blog-options'); ?></h2>
</div>

<div class="tab-content">
    <p><label for="prefix"><?php _e('Please select a blog where data have to be copied from.','default-blog-options'); ?></label></p>
    <?php
    
    global $wpdb;
    $blogs = $wpdb->get_results("SELECT blog_id FROM " . $wpdb->blogs. " WHERE spam = '0' AND deleted ='0'", ARRAY_A );
    
    ?>
    <p><select id="act_defblog_id" name="act_defblog_id">
    <?php 
    if($defblog_id==""){
		echo "<option selected>".__('Please select a blog', 'default-blog-options')."</option>\n";
	}
    foreach($blogs AS $blog){
		
        if($defblog_id==$blog['blog_id']){
            echo "<option value=\"".$blog['blog_id']."\" selected>".get_blog_option($blog['blog_id'],'blogname')."</option>\n";
        }else{
            echo "<option value=\"".$blog['blog_id']."\">".get_blog_option($blog['blog_id'],'blogname')."</option>\n";
        }
    }

    ?>
    </select></p>
    
    <p><input class="button-secondary action" type="submit" name="defblog_submit" value="<?php _e('Update', 'default-blog-options') ?>"  /></p>
</div>