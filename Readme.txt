=== Default Blog ===
Contributors: svenl77, mahype 
Tags: options,wpmu,wordpress mu,blogs,create blogs,blog defaults,wp_options,automation,community,template,admin,tools,blog network,sites,copy blogs,copy,cloning,blog cloning
Requires at least: wpmu 3.0
Tested up to: wpmu 3.0.1
Requires at least: 3.0
Stable tag:  0.5

Clone your blog settings to all new blogs youre adding. Copy settings like Posts, Pages, Theme settings, Blog options ... 

== Description ==
Dublicate your Blog! Create your Blog template and use it as draft for all new blogs you create in your WordPress Network installation.

In the free version you can duplicate the following settings:

   * Posts
   * Pages
   * Keywords
   * Tags
   * Links
   * Design
   * Plugin
   * Values from Blog option table
   
In the <a href="http://themekraft.com/shop/default-blog/">pro version</a> you also can duplicate more settings:

   * Menues
   * Widgets
  
<a href="http://themekraft.com/shop/default-blog/">Get the pro version!</a>

Very nice for mass blog creation as in communities!

Bug report, please go here:<br><br>
https://github.com/Themekraft/default-blog/issues

== Installation ==
<h3>Installing Plugin</h3>
<ul>
	<li>1. Upload `Default Blog Options` to the `/wp-content/plugins/` directory<br></li>
	<li>2. Login as Superadmin</li>
	<li>3. Activate the plugin <strong>Site Wide</strong> through the 'Plugins' menu in WordPress</li>
</ul>

<h3>How it works:</h3>

Just create a blog which you want to use as default blog and set up the whole blog.

After setting up the default blog, go to the "Default Blog" plugin in the "Super Admin" panel and set up the "Default Blog". 

When done, you will be able to select the settings you'd like to have in every new blog.

Every time a blog is created, the settings will be copied from the default blog to the new blog.

Thats it! Have fun.

== Screenshots ==
1. **Setting up blog**
2. **Pages**
2. **Blog options**
== License ==

**********************************************************************
This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
**********************************************************************

==  Version history  ==

0.5 | Changed Plugin structure and added hooks

0.4.1 | Added flexible table prefixes | Fixed GUI Javascript Bugs

0.4 | Fixed category Warnings | Bettered up user guidance cause of logical issues | Prepared software for using blog templates

0.3 | Plugin is WP 3.0 ready | Implemented new GUI | New Blogs where set up at the moment of adding blog.

0.22 - beta | Plugin settings haven't been shown. Problems solved. | Problems on deleting existing pages, posts and categories  in the moment when settings where copied. Script hanged up. Problem is solved.

0.21 - beta | Deleted error message and running dead if no page or post was selected. | Added some german words to language file

0.2 - beta | Inserted design, plugins and settings tabs with settings for it.

0.16 - beta | Tags can be copied too. Default blog can be selected in a dropdown list.

0.15 - beta | Categories of posts where now copied too.

0.14 - beta | Post and page meta where now copied too. Parent pages where set correctly. 

0.13 - beta | Added categories to copy from default blog 

0.12 - beta | Default blog couldn't be set after setting main blog to defaul blog. | Tabs where shown now. CSS and JS wasn't linked correct.

0.11 - beta	| Heavy Bugfix: Content of existing blogs have been deleted, if dashboard was visited and plugin was active. Now plugin won't run anymore for blogs which have been created before plugin was enabled. | Bugfix: Warning won't be shown anymore, if blog dashboard was visited the first time | New function: Added tabs to the admin area.

0.1	Added Posts and Pages to copy from default blog (Attention: Do not use!)

0.05 Added translations to plugin and german language (Attention: Do not use!)

0.04 Added links to copy from default blog (Attention: Do not use!)

0.03.1 Set up correct version of plugin (Attention: Do not use!)

0.03-beta Renamed Plugin to "Default Blog" in consider of developements in future. Cleaned up the code. Plugin will be shown in the Siteadmin menue independent of backend.

0.02-beta Reorder and clean up the code and fix some bugs were values got lost.

0.01-beta First testing beta version.