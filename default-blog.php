<?php
/*
Plugin Name: Default Blog
Plugin URI: http://wordpress.org/extend/plugins/default-blog-options/
Description: Create new blogs with values like Posts, Pages, Theme settings, Blog options ... from a default blog made by you.
Author: Sven Lehnert, Sven Wagener
Author URI: http://www.rheinschmiede.de
Version: 1.0 alpha
License: (GNU General Public License 3.0 (GPL) http://www.gnu.org/licenses/gpl.html)
Copyright: Sven Wagener
*/

/**********************************************************************
This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
***********************************************************************/

class default_blog{
	
	var $template_options;
	var $templates;
	var $components;
	
	function default_blog(){
		$this->__construct();
	}
	function __construct(){
		$this->constants(); // Setting general Constants
		$this->get_options(); // Getting options
		$this->globals(); // Setting Globals
		$this->includes(); // Getting Includes
		$this->components(); 
		
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) ); // Loading Textdomain
		add_action( 'network_admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wpmu_new_blog', array( $this, 'init_blog' ) );
		
		// Admin page
		if( is_admin() ):
			if( 'defaultblog' == $_GET['page'] || 'options.php' == basename( $_SERVER['REQUEST_URI'] ) ):
				add_action( 'after_setup_theme', array( $this, 'admin_load_framework' ), 1 ); // Loading Framework
				
				add_action( 'admin_init', array( $this, 'register_settings' ) );
				add_action( 'admin_head', array( $this, 'admin_css' ) );
				add_action( 'admin_init',  array( $this, 'admin_js' ));
				add_action( 'admin_head',  array( $this, 'admin_js_vars' ));
			endif;
		endif;
	}
	
	public function register_settings(){
		register_setting( 'default-blog-config', DFB_OPTION_GROUP, array( $this, 'save' ) );
	}
	
	public function save( $input ){
		$plugins = get_dfb_plugins();
		
		if( !is_array( $plugins ) )
			return FALSE;
		
		// Doing Saving actions of Plugins
		foreach( $plugins AS $plugin ):
			if( function_exists( $plugin[ 'function_save' ] ) ):
				$input = call_user_func( $plugin[ 'function_save' ], $input );
				// echo 'Doing: ' . $plugin[ 'function_save' ] . '<br />';
			endif;
		endforeach;
		
		// Merging old and new template values
		$old_input = get_option( DFB_OPTION_GROUP );
		
		if( !is_array( $input ) )
			return FALSE;
		
		if( !is_array( $old_input ) )
			return $input;
		
		foreach ( $old_input AS $key => $template ):
			if( !array_key_exists( $key, $input ) )
				$input[ $key ] = $template;
		endforeach;
		
		return $input;
	}

	public function init_blog( $to_blog_id ){
		$plugins = get_dfb_plugins();
		
		foreach( $plugins AS $plugin ):
			$this->copy_element( $plugin[ 'slug' ], DFB_TEMPLATE_BLOG_ID, $to_blog_id );
		endforeach;
	}
	
	public function copy_element( $plugin_slug, $from_blog_id, $to_blog_id, $args = array() ){

		if( !in_array( $plugin_slug, get_dfb_plugin_slugs() ) )
			return FALSE;
		
		$plugin = get_dfb_plugin( $plugin_slug );
		
		$function_copy = $plugin[ 'function_copy' ];
		
		if( !function_exists( $function_copy ) )
			return FALSE;
		
		if( FALSE == call_user_func( $function_copy, $from_blog_id, $to_blog_id, $args ) )
			return FALSE;
		
		return TRUE;
	}
	
	public function includes(){
		include_once( DFB_FOLDER . '/functions.php' ); // Functions
		include_once( DFB_FOLDER . '/includes/tkf/loader.php' ); // Framework
	}
	
	public function load_textdomain(){
		load_plugin_textdomain( 'default-blog-options', DFB_FOLDER . '/languages/' );
	}
	
	function constants(){
		define( 'DFB_FOLDER', 	$this->get_folder() );
		define( 'DFB_URLPATH', $this->get_url_path() );
		define( 'DFB_OPTION_GROUP', 'dfb-option-group' ); // Option group to save data
		define( 'DFB_TEMPLATE_OPTIONS', 'dfb-template-options' ); // Option to save template data
		define( 'DFB_PLUGIN_OPTIONS', 'dfb-plugin-options' ); // Option to save template data
	}
	
	function globals(){
		global $default_blog_template;
		$default_blog_template = $this->templates[ DFB_TEMPLATE_EDIT_ID ] ;
		
		//echo '<pre>';
		//print_r( $default_blog_template );
		//echo '</pre>';
	}
	
	function components(){
		$components_folder = DFB_FOLDER . '/components';
		
		$this->components = apply_filters( 'default_blog_components', array( 
			'blog-template' =>  $components_folder . '/blog-template' , 
			'posts' =>  $components_folder . '/posts' , 
			'menus' =>  $components_folder . '/menus' , 
			'sidebars' =>  $components_folder . '/sidebars' , 
			'links' =>  $components_folder . '/links' , 
			'settings' =>  $components_folder . '/settings',
			'options' =>  $components_folder . '/options',
		));
		
		foreach( $this->components AS $component_name => $component_folder ):
			$component_functions_path = $component_folder . '/functions.php';
			
			if( file_exists( $component_functions_path )  ):
				include_once( $component_functions_path );
			endif;
			
		endforeach;
	}
	
	function admin_menu(){
		add_submenu_page( 'sites.php', __( 'Default Blog', 'default-blog-options' ), __( 'Default Blog', 'default-blog-options' ), 'manage_options', 'defaultblog', array( $this, 'admin_page' ) );
	}
	
	function admin_page(){
		include_once( DFB_FOLDER . '/admin.php' );
	}
	
	function admin_css(){
		wp_register_style( 'default-blog-admin-css', DFB_URLPATH . '/admin.css' );
		wp_enqueue_style( 'default-blog-admin-css' );
	}
	
	function admin_js(){
		wp_register_script( 'default-blog-js', DFB_URLPATH . '/admin.js' ); // General Theme JS
		wp_enqueue_script( 'default-blog-js' );	
	}

	public function admin_js_vars(){
		$content = '<script type="text/javascript">' . chr(13);
		$content.= '// Text strings for default Blog' . chr(13);
		$content.= 'var dfb_txt_delete_template_question = "' . __( 'Do you really want to delete this template?', 'default-blog-options' ) . '";' . chr(13);
		$content.= '</script>' . chr(13);
		echo $content;
	}
	
	public function admin_load_framework(){
		$args['jqueryui_components'] = array( 'jquery-cookies', 'jquery-ui-tabs', 'jquery-ui-accordion' );
		tk_framework( $args );
	}
	
	function get_options(){
		$this->update();
		
		// Try to get old values
		if( '' == get_option( DFB_OPTION_GROUP ) )
			$this->update();
			
		$this->templates = get_option( DFB_OPTION_GROUP );
		$this->template_options = get_option( DFB_TEMPLATE_OPTIONS );
		/*
		echo '<br />Templates:<pre>';
		print_r( $this->templates );
		echo '</pre>';
		
		echo '<br />Template Options:<pre>';
		print_r( $this->template_options );
		echo '</pre>';
		 * */
		
		if( is_array( $this->templates ) )
			define( 'DFB_TEMPLATE_ID', $this->templates[ 'dfb_template_id' ] );
		if( is_array( $this->template_options[ DFB_TEMPLATE_ID ] ) )
			define( 'DFB_TEMPLATE_BLOG_ID', $this->template_options[ DFB_TEMPLATE_ID ][ 'blog_id' ] );
		
		if( is_array( $this->templates ) )
			define( 'DFB_TEMPLATE_EDIT_ID', $this->templates[ 'dfb_template_edit_id' ] );
		if( is_array( $this->template_options[ DFB_TEMPLATE_EDIT_ID ] ) )
			define( 'DFB_TEMPLATE_EDIT_BLOG_ID', $this->template_options[ DFB_TEMPLATE_EDIT_ID ][ 'blog_id' ]);
	}

	function update(){
		$templates_old = get_site_option( 'defblog_templates' );
		$settings_old = get_site_option( 'defblog_settings' );
		/*
		echo '<br />Templates old<pre>';
		print_r( $templates_old );
		echo '</pre>';
		
		echo '<br />Settings old<pre>';
		print_r( $settings_old );
		echo '</pre>';
		 * */
		
		$new_templates[ 0 ] = array(
			'post' => $templates_old[ 0 ][ 'posts' ],
			'post_delete_existing' => $templates_old[ 0 ][ 'del_posts' ],
			'post_taxonomies' => array(
				'category' => $templates_old[ 0 ][ 'cats' ],
				'tag' => $templates_old[ 0 ][ 'tags' ]
			),
			'page' => $templates_old[ 0 ][ 'pages' ],
			'page_delete_existing' => $templates_old[ 0 ][ 'del_pages' ],
			'links' => $templates_old[ 0 ][ 'links' ],
			'links_delete_existing' => $templates_old[ 0 ][ 'del_links' ],
			'appearance' => $templates_old[ 0 ][ 'appearance' ],
			'plugins' => $templates_old[ 0 ][ 'plugins' ],
			'options' => $templates_old[ 0 ][ 'options' ],
		);
		/*
		echo '<br />New Template:<pre>';
		print_r( $new_templates );
		echo '</pre>';
		*/
		$new_template_options[] = array(
			'blog_id' => $settings_old['init'],
			'template_id' => 0,
			'template_name' => __( 'Autogenerated from old version.', 'default-blog-options' )
		); 
	}
	
	/**
	* Getting URL Path
	*
	* @package Default Blog
	* @since 1.0
	*
	*/
	private function get_url_path(){
		$sub_path = substr( DFB_FOLDER, strlen( ABSPATH ), ( strlen( DFB_FOLDER ) ) );
		$script_url = get_bloginfo( 'wpurl' ) . '/' . $sub_path;
		return $script_url;
	}
	
	/**
	* Getting URL Path of theme
	*
	* @package Default Blog
	* @since 1.0
	*
	*/
	private function get_folder(){
		$sub_folder = substr( dirname(__FILE__), strlen( ABSPATH ), ( strlen( dirname(__FILE__) ) - strlen( ABSPATH ) ) );
		$script_folder = ABSPATH . $sub_folder;
		return $script_folder;
	}
}

$default_blog = new default_blog();