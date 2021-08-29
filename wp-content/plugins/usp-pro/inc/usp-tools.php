<?php // USP Pro - Tools (Settings Tab)

if (!defined('ABSPATH')) die();

/*
	Tools - Reset Default Settings
*/
if (!function_exists('usp_tools_reset')) : 
function usp_tools_reset() {
	
	$tools_reset  = '<p>'. esc_html__('To restore USP Pro default settings:', 'usp-pro') .'</p>'; 
	$tools_reset .= '<ol>';
	$tools_reset .= '<li>'. esc_html__('Check the box below and click &ldquo;Save Changes&rdquo;.', 'usp-pro') .'</li>';
	$tools_reset .= '<li><a href="'. get_admin_url() .'plugins.php?page=usp-pro-license">'. esc_html__('Deactivate your USP License', 'usp-pro') .'</a> '. esc_html__('(make a note of your License Key before deactivation; you will need it to reactivate the plugin).', 'usp-pro') .'</li>';
	$tools_reset .= '<li>'. esc_html__('Deactivate and then reactivate the plugin to make it so.', 'usp-pro') .'</li>';
	$tools_reset .= '</ol>';
	$tools_reset .= '<p><strong>'. esc_html__('Note: ', 'usp-pro') .'</strong> '. esc_html__('restoring default settings does not affect any submitted post data or existing USP Forms.', 'usp-pro') .'</p>';
	
	return $tools_reset;
}
endif;

/*
	Tools - Display Resources
*/
if (!function_exists('usp_tools_display')) : 
function usp_tools_display() {
	
	$tools_display  = '<div class="usp-pro-tools-display">';
	
	$tools_display .= '<h3><a id="usp-toggle-s1" class="usp-toggle-s1" href="#usp-toggle-s1" title="'. esc_attr__('Show/Hide Backup &amp; Restore', 'usp-pro') .'">'. esc_html__('Backup &amp; Restore', 'usp-pro') .'</a></h3>';
	$tools_display .= '<div class="usp-s1 usp-toggle default-hidden">'. usp_display_import_export() .'</div>';
	
	$tools_display .= '<h3><a id="usp-toggle-s2" class="usp-toggle-s2" href="#usp-toggle-s2" title="'. esc_attr__('Show/Hide USP Shortcodes', 'usp-pro') .'">'. esc_html__('USP Shortcodes', 'usp-pro') .'</a></h3>';
	$tools_display .= '<div class="usp-s2 usp-toggle default-hidden">'. usp_tools_shortcodes() .'</div>';
	
	$tools_display .= '<h3><a id="usp-toggle-s3" class="usp-toggle-s3" href="#usp-toggle-s3" title="'. esc_attr__('Show/Hide USP Template Tags', 'usp-pro') .'">'. esc_html__('USP Template Tags', 'usp-pro') .'</a></h3>';
	$tools_display .= '<div class="usp-s3 usp-toggle default-hidden">'. usp_tools_tags() .'</div>';
	
	$tools_display .= '<h3><a id="usp-toggle-s4" class="usp-toggle-s4" href="#usp-toggle-s4" title="'. esc_attr__('Show/Hide Helpful Resources', 'usp-pro') .'">'. esc_html__('Helpful Resources', 'usp-pro') .'</a></h3>';
	$tools_display .= '<div class="usp-s4 usp-toggle default-hidden">'. usp_tools_resources() .'</div>';
	
	$tools_display .= '</div>';
	
	return $tools_display;
}
endif;

/*
	Tools - Shortcodes
*/
if (!function_exists('usp_tools_shortcodes')) : 
function usp_tools_shortcodes() {
	
	$tools_shortcodes = '<p class="toggle-intro">' . esc_html__('USP Pro provides shortcodes that make it easy to display forms and submitted content virtually anywhere. ', 'usp-pro');
	$tools_shortcodes .= esc_html__('To get started,', 'usp-pro') . ' <a href="https://codex.wordpress.org/Shortcode_API" target="_blank" rel="noopener noreferrer">' . esc_html__('learn how to use Shortcodes', 'usp-pro') . '</a> ';
	$tools_shortcodes .= esc_html__('and then include USP Shortcodes as needed in your Posts and Pages.', 'usp-pro') .'</p>';
	$tools_shortcodes .= '<p><a href="https://plugin-planet.com/usp-pro-shortcodes/" target="_blank" rel="noopener noreferrer">'. esc_html__('Check out the complete list of USP Shortcodes &raquo;', 'usp-pro') .'</a></p>';
	$tools_shortcodes .= '<p>' . esc_html__('In addition to those provided by USP Pro, there are numerous', 'usp-pro') . ' <a href="https://codex.wordpress.org/Shortcode" target="_blank" rel="noopener noreferrer">' . esc_html__('default WP shortcodes', 'usp-pro') . '</a>, ';
	$tools_shortcodes .= esc_html__('as well as any shortcodes that may be included with your theme and/or plugin(s). Also, FYI, more information about shortcodes may be found in the USP source code (as inline comments), ', 'usp-pro');
	$tools_shortcodes .= esc_html__('specifically see', 'usp-pro') . ' <code>/inc/usp-functions.php</code>.</p>';
	return $tools_shortcodes;	
}
endif;

/*
	Tools - Template Tags
*/
if (!function_exists('usp_tools_tags')) : 
function usp_tools_tags() {
	
	$tools_tags = '<p class="toggle-intro">' . esc_html__('USP Pro provides template tags for displaying submitted post content, author information, file uploads and more. ', 'usp-pro');
	$tools_tags .= esc_html__('To get started,', 'usp-pro') . ' <a href="https://codex.wordpress.org/Template_Tags" target="_blank" rel="noopener noreferrer">' . esc_html__('learn how to use Template Tags', 'usp-pro') . '</a> ';
	$tools_tags .= esc_html__('and then include USP Template Tags as needed in your theme template.', 'usp-pro') . '</p>';
	$tools_tags .= '<p><a href="https://plugin-planet.com/usp-pro-template-tags/" target="_blank" rel="noopener noreferrer">'. esc_html__('Check out the complete list of USP Template Tags &raquo;', 'usp-pro') .'</a></p>';
	$tools_tags .= '<p>' . esc_html__('In addition to those provided by USP Pro, there are a great many template tags provided by WordPress, making it possible to display just about any information anywhere on your site. ', 'usp-pro');
	$tools_tags .= esc_html__('Also, FYI, more information about each of these template tags may be found in the USP source code (as inline comments), specifically see', 'usp-pro') . ' <code>/inc/usp-functions.php</code>.</p>';
	
	return $tools_tags;
}
endif;

/*
	Tools - Helpful Resources
*/
if (!function_exists('usp_tools_resources')) : 
function usp_tools_resources() {
	
	$tools_resources  = '<p class="toggle-intro">'. esc_html__('Here are some useful resources for working with USP Pro and WordPress.', 'usp-pro') .'</p>';
	
	$tools_resources .= '<h3>'. esc_html__('Useful resources and places to get help', 'usp-pro') .'</h3>';
	$tools_resources .= '<ul>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://m0n.co/usp-video-tuts">'.                               esc_html__('Video Tutorials', 'usp-pro')                              .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/category/docs+usp-pro/">'.            esc_html__('USP Pro Docs', 'usp-pro')                                 .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/category/tuts+usp-pro/">'.            esc_html__('USP Pro Tutorials', 'usp-pro')                            .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/category/forum+usp-pro/">'.           esc_html__('USP Pro Forum', 'usp-pro')                                .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/news/">'.                             esc_html__('Plugin Planet News', 'usp-pro')                                 .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/support/#contact">'.                  esc_html__('Bug reports, help requests, and feedback', 'usp-pro')     .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/wp/wp-login.php">'.                   esc_html__('Log in to your account for current downloads', 'usp-pro') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://digwp.com/2011/09/where-to-get-help-with-wordpress/">'. esc_html__('Where to Get Help with WordPress', 'usp-pro')             .'</a></li>';
	$tools_resources .= '</ul>';
	
	$tools_resources .= '<h3>'. esc_html__('Key resources at the WordPress Codex', 'usp-pro') .'</h3>';
	$tools_resources .= '<ul>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://codex.wordpress.org/Templates">'.                    esc_html__('WP Theme Templates', 'usp-pro')       .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/article/wordpress-widgets/">'. esc_html__('WP Widgets', 'usp-pro')               .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://codex.wordpress.org/Shortcode_API">'.                esc_html__('WP Shortcodes', 'usp-pro')            .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://codex.wordpress.org/Template_Tags">'.                esc_html__('WP Template Tags', 'usp-pro')         .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://codex.wordpress.org/Quicktags_API">'.                esc_html__('WP Quicktags', 'usp-pro')             .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/article/post-types/">'.        esc_html__('WP Custom Post Types', 'usp-pro')     .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://codex.wordpress.org/The_Loop">'.                     esc_html__('The WordPress Loop', 'usp-pro')       .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://codex.wordpress.org/Troubleshooting">'.              esc_html__('WP Troubleshooting Guide', 'usp-pro') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/">'.                           esc_html__('WP Help Forum', 'usp-pro')            .'</a></li>';
	$tools_resources .= '</ul>';
	
	$tools_resources .= '<h3>'. esc_html__('WordPress books and resources by Jeff Starr', 'usp-pro') .'</h3>';
	$tools_resources .= '<ul>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://digwp.com/">'.                        esc_html__('Digging Into WordPress, by Chris Coyier and Jeff Starr', 'usp-pro')                      .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://wp-tao.com/">'.                       esc_html__('The Tao of WordPress &ndash; Complete guide for users, admins, and everyone', 'usp-pro') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://wp-tao.com/wordpress-themes-book/">'. esc_html__('WordPress Themes In Depth &ndash; Complete guide to building awesome themes', 'usp-pro') .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://htaccessbook.com/">'.                 esc_html__('.htaccess made easy &ndash; Configure, optimize, and secure your site', 'usp-pro')       .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/">'.              esc_html__('Perishable Press &ndash; WordPress, Web Design, Code &amp; Tutorials', 'usp-pro')        .'</a></li>';
	$tools_resources .= '<li><a target="_blank" rel="noopener noreferrer" href="https://wp-mix.com/">'.                       esc_html__('WP-Mix &ndash; Useful code snippets for WordPress and more', 'usp-pro')                  .'</a></li>';
	$tools_resources .= '</ul>';
	
	return $tools_resources;	
}
endif;
