<?php

global $dfb_templates;

$plugins = get_dfb_plugins();

$elements = array();

tk_form_start( 'default-blog-config' );

if( is_array( $plugins ) ):
	foreach( $plugins AS $plugin ):
			
		// If Templates are added and Template ID is set OR tab is blog template settings
		if( DFB_TEMPLATE_EDIT_ID > 0  || 'blog-template' == $plugin['slug'] ):
		
			$element[ 'id' ] = $plugin['slug'];
			$element[ 'title' ] = $plugin['title'];
			
			ob_start();
			if( function_exists( $plugin[ 'function_admin' ] ) ) call_user_func( $plugin[ 'function_admin' ] );
			$element[ 'content' ] = ob_get_clean();
			
			$elements[] = $element;
		endif;
			
	endforeach;
endif;

echo tk_tabs( 'default_blog_tabs', $elements, 'html' );

echo tk_form_button( __( 'Save Settings', 'default-blog-options' ) );

$content = tk_form_end( FALSE );

?>
<div class="wrap">
    <h2><?php _e( 'Default Blog', 'default-blog-options' ); ?></h2>
    <p><?php _e( 'Create your Blog Template and setup which things you want to copy to new created blogs.', 'default-blog-options' ); ?></p>
    <?php echo $content; ?>
    
</div>