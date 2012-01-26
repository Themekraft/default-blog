<?php
// Page in admin area
function default_blog_options_page($options,$links){
	global $wpdb, $blog_id;
	global $extended;
	global $defblog_name;
	global $defblog_plugin_path;
	global $defblog_settings, $defblog_templates;
	
	$template_id=get_template_id();
	$defblog_id=$defblog_templates[$template_id]['id'];
	
	/*
	echo "TID: ".$template_id."<br>";
	echo "DID: ".$defblog_id."<br>";	
	*/
	
	// defblog_css();
	
	?>
<div class="wrap">
    <h2><?php echo $defblog_name; ?></h2>

    <div id="config-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<?php if($defblog_id!=""){ ?>
            <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#cap_select_blog">Blog</a></li>
			<li class="ui-state-default ui-corner-top" ><a href="#cap_posts" class="selected"><?php _e('Posts', 'default-blog-options') ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="#cap_categories"><?php _e('Categories', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#cap_tags"><?php _e('Tags', 'default-blog-options') ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="#cap_links"><?php _e('Links', 'default-blog-options') ?></a></li>		
  			<li class="ui-state-default ui-corner-top"><a href="#cap_pages"><?php _e('Pages', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#cap_appearance"><?php _e('Appearance', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#cap_plugins"><?php _e('Plugins', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#cap_settings"><?php _e('Settings', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#cap_options"><?php _e('Options table (Expert modus)', 'default-blog-options') ?></a></li>
			<?php do_action('defblog-settings-tabs-active');?>
            
            <?php }else{ ?>
            <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#cap_select_blog" class="selected">Blog</a></li>
            <li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Posts', 'default-blog-options') ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Categories', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Tags', 'default-blog-options') ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Links', 'default-blog-options') ?></a></li>		
  			<li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Pages', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Appearance', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Plugins', 'default-blog-options') ?></a></li>
  			<li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Settings', 'default-blog-options') ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#"><?php _e('Options table (Expert modus)', 'default-blog-options') ?></a></li>
  			<?php do_action('defblog-settings-tabs-unactive');?>
			
            <?php } ?>
  		</ul>
	
    	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">    

		<!-- Blog select //-->
        <div id="cap_select_blog" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
        	
			<?php include($defblog_plugin_path."/admin/admin-default-blog-defblog.php"); ?>
        </div>
        
        <!-- Posts //-->
        <div id="cap_posts" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-posts.inc.php"); ?>
        </div>
        
        <!-- Pages //-->
        <div id="cap_pages" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-pages.inc.php"); ?>
        </div>
        
        <!-- Links //-->
        <div id="cap_links" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-links.inc.php"); ?>
        </div>
        
        <!-- Categories //-->
        <div id="cap_categories" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-cats.inc.php"); ?>
        </div>
        
        <!-- Tags //-->
        <div id="cap_tags" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-tags.inc.php"); ?>
        </div>
        
        <!-- Appearance //-->
        <div id="cap_appearance" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-appearance.inc.php"); ?>
        </div>
        
        <!-- Plugins //-->
        <div id="cap_plugins" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-plugins.inc.php"); ?>
        </div>
        
        <!-- Settings //-->
        <div id="cap_settings" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php include($defblog_plugin_path."/admin/admin-default-blog-settings.inc.php"); ?>
        </div>
        
        <!-- Options //-->
        <div id="cap_options" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide">
        	<?php if($extended){include($defblog_plugin_path."/extended/admin-default-blog-options.inc.php");}else{include($defblog_plugin_path."/admin/admin-default-blog-options.inc.php");}?>
        </div>
		
		<?php do_action('defblog-settings-tabs-content');?>
        
        </form>
    </div>
    <script type="text/javascript">
    	jQuery(document).ready(function($){
        	$("#config-tabs").tabs();
         });
	</script>
</div>
  	
<?php } ?>