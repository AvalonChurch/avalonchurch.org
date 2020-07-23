<?php 
/*
	Plugin Name: USP Helper
	Plugin URI: https://plugin-planet.com/usp-pro-custom-field-helper-plugin/
	Description: Displays USP Custom Fields on the front-end of your site.
	Tags: usp, usp pro
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://m0n.co/donate
	Contributors: specialk
	Requires at least: 4.3
	Tested up to: 5.4
	Stable tag: 2.5
	Version: 2.5
	Text Domain: usp-helper
	Domain Path: /languages
	
	The USP Helper license is comprised of two parts:
	
	* Part 1: Its PHP code is licensed under the GPL (v2 or later), like WordPress. More info @ http://www.gnu.org/licenses/
	
	* Part 2: Everything else (e.g., CSS, HTML, JavaScript, images, design) is licensed as follows:
	
	Without prior written consent from Monzilla Media, you must NOT directly or indirectly: license, sub-license, sell, resell, share, or provide for free any aspect or component of Part 2.
	
	Further license information is available online @ https://plugin-planet.com/wp/files/usp-helper/license.txt
	
	Upgrades: Your purchase of USP Pro includes the USP Helper plugin, which includes free lifetime upgrades, which may include new features, bug fixes, and other improvements. 
	
	Copyright 2020 Monzilla Media
	
*/

if (!defined('ABSPATH')) die();

define('USP_HELPER_VERSION', '2.5');
define('USP_HELPER_REQUIRE', '4.3');
define('USP_HELPER_NAME', 'USP Helper');
define('USP_HELPER_HOME', 'https://plugin-planet.com/usp-pro-custom-field-helper-plugin/');

$usp_helper_settings = get_option('usp_helper_settings', usp_helper_settings_default());

function usp_helper_i18n_init() {
	
	load_plugin_textdomain('usp-helper', false, basename(dirname( __FILE__ )) .'/languages');
	
}
add_action('plugins_loaded', 'usp_helper_i18n_init');

function usp_helper_version_require() {
	
	global $wp_version;
	
	$plugin = plugin_basename(__FILE__); 
	
	if (version_compare($wp_version, USP_HELPER_REQUIRE, '<')) {
		
		if (is_plugin_active($plugin)) {
			
			deactivate_plugins($plugin);
			
			$msg  = '<strong>'. USP_HELPER_NAME .'</strong> ';
			$msg .= esc_html__('requires WordPress ', 'usp-helper') . USP_HELPER_REQUIRE;
			$msg .= esc_html__(' or higher, and has been deactivated! Please return to the', 'usp-helper');
			$msg .= ' <a href="'. admin_url() .'">'. esc_html__('WP Admin Area', 'usp-helper') .'</a> ';
			$msg .= esc_html__('to upgrade WordPress and try again.', 'usp-helper');
			
			wp_die($msg);
			
		}
		
	}
	
}
if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
	
	add_action('admin_init', 'usp_helper_version_require');
	
}

function usp_helper_settings_page() {
	
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page(USP_HELPER_NAME, USP_HELPER_NAME, 'manage_options', 'usp_helper_settings', 'usp_helper_settings');
	
}
add_action ('admin_menu', 'usp_helper_settings_page');

function usp_helper_settings_register() {
	
	// register_setting( $option_group, $option_name, $sanitize_callback );
	register_setting('usp_helper_settings', 'usp_helper_settings', 'usp_helper_validate_settings');
	
}
add_action ('admin_init', 'usp_helper_settings_register');

function usp_helper_settings_delete() {
	
	if (!current_user_can('activate_plugins')) return;
	
	include_once('uninstall.php');
	
}
register_uninstall_hook(__FILE__, 'usp_helper_settings_delete');

function usp_helper_action_links($links) {
	
	$settings = '<a href="'. esc_url(admin_url('options-general.php?page=usp_helper_settings')) .'">'. esc_html__('Settings', 'usp-helper') .'</a>';
	
	array_unshift($links, $settings);
	
	return $links;
	
}
add_filter('plugin_action_links_'. plugin_basename(__FILE__), 'usp_helper_action_links');

function usp_helper_add_links($links, $file) {
	
	if ($file == plugin_basename(__FILE__)) {
		
		$links[] = '<a href="https://wordpress.org/plugins/user-submitted-posts/">'. esc_html__('USP Free', 'usp-helper') .'</a>';
		$links[] = '<a href="https://plugin-planet.com/usp-pro/">'. esc_html__('USP Pro', 'usp-helper') .'</a>';
		
	}
	
	return $links;
	
}
add_filter('plugin_row_meta', 'usp_helper_add_links', 10, 2);

function usp_helper_settings_default() {
	
	$settings = array(
		
		'custom_field_names'  => array('example-custom-field-name-1', 'example-custom-field-name-2', 'example-custom-field-name-3'),
		'custom_field_before' => array('<p>', '<p>', '<p>'),
		'custom_field_after'  => array('</p>', '</p>', '</p>'),
		'display_frontend'    => 1,
		'display_backend'     => 0,
		'display_after_post'  => 0,
		'display_posts_only'  => 0,
		'display_submit_only' => 0,
		'video_load_scripts'  => 0,
		'all_fields_before'   => '',
		'all_fields_after'    => '',
	);
	
	return apply_filters('usp_helper_settings_default', $settings);
	
}

function usp_helper_validate_settings($input) {
	
	if (isset($input['custom_field_names'])) {
		$input['custom_field_names'] = usp_helper_check_fields($input['custom_field_names']);
	}
	
	if (isset($input['custom_field_before'])) {
		$input['custom_field_before'] = usp_helper_check_fields($input['custom_field_before']);
	}
	
	if (isset($input['custom_field_after'])) {
		$input['custom_field_after'] = usp_helper_check_fields($input['custom_field_after']);
	}
	
	if (!isset($input['display_frontend'])) $input['display_frontend'] = null;
	$input['display_frontend'] = ($input['display_frontend'] == 1 ? 1 : 0);

	if (!isset($input['display_backend'])) $input['display_backend'] = null;
	$input['display_backend'] = ($input['display_backend'] == 1 ? 1 : 0);
	
	if (!isset($input['display_after_post'])) $input['display_after_post'] = null;
	$input['display_after_post'] = ($input['display_after_post'] == 1 ? 1 : 0);
	
	if (!isset($input['display_posts_only'])) $input['display_posts_only'] = null;
	$input['display_posts_only'] = ($input['display_posts_only'] == 1 ? 1 : 0);
	
	if (!isset($input['display_submit_only'])) $input['display_submit_only'] = null;
	$input['display_submit_only'] = ($input['display_submit_only'] == 1 ? 1 : 0);
	
	if (!isset($input['video_load_scripts'])) $input['video_load_scripts'] = null;
	$input['video_load_scripts'] = ($input['video_load_scripts'] == 1 ? 1 : 0);

	$input['all_fields_before'] = wp_kses_post($input['all_fields_before']);
	
	$input['all_fields_after'] = wp_kses_post($input['all_fields_after']);
	
	return $input;
	
}

function usp_helper_check_fields($input) {
	
	if (is_array($input)) {
		
		foreach ($input as $key => $value) {
			
			$input[$key] = $value;
			
		}
		
	} else {
		
		$input = array();
		
	}
	
	return $input;
	
}

function usp_helper_get_fields() {
	
	global $usp_helper_settings; 
	
	$fields = array();
	
	if (isset($usp_helper_settings['custom_field_names'])) {
		
		if (is_array($usp_helper_settings['custom_field_names'])) {
			
			$fields = $usp_helper_settings['custom_field_names'];
		
		}
	} 
	
	return $fields;
	
}

function usp_helper_get_before() {
	
	global $usp_helper_settings; 
	
	$before = array();
	
	if (isset($usp_helper_settings['custom_field_before'])) {
		
		if (is_array($usp_helper_settings['custom_field_before'])) {
			
			$before = $usp_helper_settings['custom_field_before'];
		
		}
	} 
	
	return $before;
	
}

function usp_helper_get_after() {
	
	global $usp_helper_settings; 
	
	$after = array();
	
	if (isset($usp_helper_settings['custom_field_after'])) {
		
		if (is_array($usp_helper_settings['custom_field_after'])) {
			
			$after = $usp_helper_settings['custom_field_after'];
		
		}
	} 
	
	return $after;
	
}

function usp_helper_get_set() {
	
	$set = array();
	
	$set['fields'] = usp_helper_get_fields();
	$set['before'] = usp_helper_get_before();
	$set['after']  = usp_helper_get_after();
	
	array_unshift($set, null);
	$set = call_user_func_array('array_map', $set);
	
	return $set;
	
}

function usp_helper_settings() {
	
	global $usp_helper_settings; 
	
	$set = usp_helper_get_set();
	
	?>
	
	<style type="text/css">
		.wrap h1 small { color: #c7c7c7; font-size: 90%; }
		
		.box { width: 100%; overflow: hidden; clear: both; }
		
		.set { position: relative; float: left; clear: both; margin: 5px 0; padding: 0 20px 0 0; }
		.set label { box-sizing: border-box; display: block; float: left; width: 250px; margin: 0 5px 0 0; padding: 0; }
		.set textarea { box-sizing: border-box; display: block; width: 100%; height: 70px; margin: 0; padding: 7px; }
		
		.set-tools { display: block; float: left; width: 40px; margin: 5px 0; border-left: 1px solid #ddd; }
		.set-tool { display: block; width: 20px; height: 20px; margin: 5px; }
		.set-tool a, .sort-handle { 
			display: block; width: 20px; height: 20px; line-height: 20px; font-size: 12px; text-align: center;
			color: #fff; background-color: #ccc; border: 0; border-radius: 20px; text-decoration: none; 
			}
		.set-tool a:hover, .sort-handle:hover { background-color: #aaa; }
		.sort-handle { width: 30px; border-radius: 3px; cursor: -webkit-grabbing; cursor: grabbing; }
		
		.before-after label { box-sizing: border-box; display: block; margin: 10px 0; padding; 0; }
		.before-after textarea { box-sizing: border-box; display: block; width: 50%; height: 70px; margin: 0; padding: 7px; }
		
		.clone-input { margin: 0 0 5px 0; }
		.general-settings p:first-child { margin-top: 5px; }
		.submit-form { margin: 10px 0; }
		
		.about { color: #bbb; font-size: 90%; }
		.about a:link, .about a:visited { color: #bbb; }
		.about a:hover, .about a:active { color: #999; }
	</style>

	<div class="wrap">
		<h1><?php echo USP_HELPER_NAME; ?> <small><?php echo 'v'. USP_HELPER_VERSION; ?></small></h1>
		<p>
			<?php esc_html_e('The USP Helper plugin makes it easy to display Custom Fields on the front-end of your site.', 'usp-helper'); ?>
		</p>
		<p>
			<?php esc_html_e('For usage instructions,', 'usp-helper') ?> 
			<a target="_blank" href="<?php echo USP_HELPER_HOME; ?>"><?php esc_html_e('visit the plugin homepage', 'usp-helper'); ?>&nbsp;&raquo;</a>
		</p>
		<form method="post" action="options.php" novalidate="novalidate">
			
			<?php settings_fields('usp_helper_settings'); ?>
			
			<div class="box">
				<h3><?php esc_html_e('Custom Fields', 'usp-helper') ?></h3>
			</div>
			
			<div id="sortable" class="box custom-fields">
				
				<?php foreach ($set as $k => $v) : 
					
					$name_field   = 'usp_helper_settings[custom_field_names]['.  $k .']';
					$name_before  = 'usp_helper_settings[custom_field_before]['. $k .']';
					$name_after   = 'usp_helper_settings[custom_field_after]['.  $k .']';
					
					$value_field  = $v[0];
					$value_before = $v[1];
					$value_after  = $v[2];
					
				?>
					
				<p class="set ui-state-default" data-set="<?php echo esc_attr($k); ?>">
					
					<label>
						<textarea class="code" rows="3" cols="30" 
								  placeholder="<?php esc_attr_e('Before Custom Field', 'usp-helper'); ?>" 
								  name="<?php echo esc_attr($name_before); ?>"><?php echo esc_textarea($value_before); ?></textarea>
					</label>
					
					<label>
						<textarea class="code" rows="3" cols="30" 
								  placeholder="<?php esc_attr_e('Custom Field Name', 'usp-helper'); ?>" 
								  name="<?php echo esc_attr($name_field); ?>"><?php echo esc_textarea($value_field); ?></textarea>
					</label>
					
					<label>
						<textarea class="code" rows="3" cols="30" 
								  placeholder="<?php esc_attr_e('After Custom Field', 'usp-helper'); ?>" 
								  name="<?php echo esc_attr($name_after); ?>"><?php echo esc_textarea($value_after); ?></textarea>
					</label>
					
					<span class="set-tools">
						<span class="set-tool sort-handle" title="<?php esc_attr_e('Drag/drop row order', 'usp-helper'); ?>">
							<?php esc_html_e('Drag/drop row order', 'usp-helper'); ?>
						</span>
						<span class="set-tool remove-set">
							<a href="#" data-set="<?php echo esc_attr($k); ?>" 
								title="<?php esc_attr_e('Remove Custom Field', 'usp-helper'); ?>"><?php esc_html_e('Remove', 'usp-helper') ?></a>
						</span>
					</span>
					
				</p>
					
				<?php endforeach; ?>
				
			</div>
			
			<div class="box clone-input">
				<p><a href="#">&plus; <?php esc_html_e('Add another row', 'usp-helper'); ?></a></p>
			</div>
			
			<div class="box">
				<h3><?php esc_html_e('General Settings', 'usp-helper'); ?></h3>
			</div>
			
			<div class="box general-settings">
				
				<p>
					<label>
						<input type="checkbox" value="1" name="usp_helper_settings[display_frontend]" 
							<?php if (isset($usp_helper_settings['display_frontend'])) checked($usp_helper_settings['display_frontend']); ?> /> 
							<?php esc_html_e('Display Custom Fields on frontend', 'usp-helper'); ?>
					</label>
				</p>
				
				<p>
					<label>
						<input type="checkbox" value="1" name="usp_helper_settings[display_backend]" 
							<?php if (isset($usp_helper_settings['display_backend'])) checked($usp_helper_settings['display_backend']); ?> /> 
							<?php esc_html_e('Display Custom Fields on backend', 'usp-helper'); ?>
					</label>
				</p>
				
				<p>
					<label>
						<input type="checkbox" value="1" name="usp_helper_settings[display_after_post]" 
							<?php if (isset($usp_helper_settings['display_after_post'])) checked($usp_helper_settings['display_after_post']); ?> /> 
							<?php esc_html_e('Display Custom Fields after post content', 'usp-helper'); ?>
					</label>
				</p>
				
				<p>
					<label>
						<input type="checkbox" value="1" name="usp_helper_settings[display_posts_only]" 
							<?php if (isset($usp_helper_settings['display_posts_only'])) checked($usp_helper_settings['display_posts_only']); ?> /> 
							<?php esc_html_e('Limit display of Custom Fields to single Posts and Pages', 'usp-helper'); ?>
					</label>
				</p>
				
				<p>
					<label>
						<input type="checkbox" value="1" name="usp_helper_settings[display_submit_only]" 
							<?php if (isset($usp_helper_settings['display_submit_only'])) checked($usp_helper_settings['display_submit_only']); ?> /> 
							<?php esc_html_e('Limit display of Custom Fields to submitted Posts and Pages', 'usp-helper'); ?>
					</label>
				</p>
				
				<p>
					<label>
						<input type="checkbox" value="1" name="usp_helper_settings[video_load_scripts]" 
							<?php if (isset($usp_helper_settings['video_load_scripts'])) checked($usp_helper_settings['video_load_scripts']); ?> /> 
							<?php esc_html_e('Load Video Scripts &amp; Styles', 'usp-helper'); ?>
					</label>
				</p>
				
			</div>
			
			<div class="box before-after">
				
				<p>
					<label><?php esc_html_e('HTML to display before all Custom Fields', 'usp-helper'); ?>
						<textarea class="code" rows="5" cols="50" name="usp_helper_settings[all_fields_before]"><?php 
							if (isset($usp_helper_settings['all_fields_before'])) echo esc_textarea($usp_helper_settings['all_fields_before']); ?></textarea>
					</label>
					
					<label><?php esc_html_e('HTML to display after all Custom Fields', 'usp-helper'); ?>
						<textarea class="code" rows="5" cols="50" name="usp_helper_settings[all_fields_after]"><?php 
							if (isset($usp_helper_settings['all_fields_after'])) echo esc_textarea($usp_helper_settings['all_fields_after']); ?></textarea>
					</label>
				</p>
				
			</div>
			
			<div class="submit-form">
				<input class="button button-primary" type="submit" name="submit" value="<?php esc_attr_e('Save Changes', 'usp-helper'); ?>">
			</div>
			
		</form>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function($){ 
			
			var id = $('.set:last').data('set');
			
			$('.wrap').on('click', '.clone-input a', function(e) {
				
				e.preventDefault();
				
				id++;
				
				var setting = 'usp_helper_settings';
				
				var name_field  = 'custom_field_names';
				var name_before = 'custom_field_before';
				var name_after  = 'custom_field_after';
				
				var place_field  = '<?php esc_attr_e('Custom Field Name',   'usp-helper'); ?>';
				var place_before = '<?php esc_attr_e('Before Custom Field', 'usp-helper'); ?>';
				var place_after  = '<?php esc_attr_e('After Custom Field',  'usp-helper'); ?>';
				
				var link_title = '<?php esc_attr_e('Remove Custom Field', 'usp-helper'); ?>';
				var link_text  = '<?php esc_html_e('Remove', 'usp-helper') ?>';
				
				var sort_title = '<?php esc_attr_e('Drag/drop row order', 'usp-helper'); ?>';
				var sort_text  = '<?php esc_html_e('Drag/drop row order', 'usp-helper'); ?>';
				
				var atts = 'class="code" rows="3" cols="30"';
				
				var html = '<p class="set ui-state-default" data-set="' + id + '">' + 
							'<label>' + 
							'<textarea ' + atts + ' placeholder="' + place_before + '" name="' + setting + '[' + name_before + '][' + id + ']"></textarea>' + 
							'</label>' + 
							'<label>' + 
							'<textarea ' + atts + ' placeholder="' + place_field + '" name="' + setting + '[' + name_field + '][' + id + ']"></textarea>' + 
							'</label>' + 
							'<label>' + 
							'<textarea ' + atts + ' placeholder="' + place_after + '" name="' + setting + '[' + name_after + '][' + id + ']"></textarea>' + 
							'</label>' + 
							'<span class="set-tools">' +
							'<span class="set-tool sort-handle" title="' + sort_title + '">' + sort_text + '</span>' +
							'<span class="set-tool remove-set"><a href="#remove-' + id + '" data-set="' + id + '" title="' + link_title + '">' + link_text + '</a></span>' +
							'</span>' +
							'</p>';
							
				$('.custom-fields').append(html);
				
				$('.remove-set a').html('&times;');
				$('.sort-handle').html('&uarr;&darr;');
				
			});
			
			$('.remove-set a').html('&times;');
			$('.sort-handle').html('&uarr;&darr;');
			
			$('.wrap').on('click', '.remove-set a', function(e) {
				
				e.preventDefault();
				
				var set = $(this).data('set');
				
				$('.set[data-set="' + set + '"]').remove();
				
			});
			
		});
	</script>
	
<?php 
}  

function usp_helper_get_custom_fields() {
	
	$fields = '';
	
	$set = usp_helper_get_set();
	
	foreach ($set as $k => $v) {
		
		$field = '';
		
		$key    = isset($v[0]) ? $v[0] : null;
		$before = isset($v[1]) ? $v[1] : '';
		$after  = isset($v[2]) ? $v[2] : '';
		
		if (empty($key)) continue;
		
		$array = true;
		
		$sep = '%%';
		
		$var = '';
		
		$pos = (strpos($key, $sep));
		
		if ($pos !== false) {
			
			$array = false;
			
			$var = explode($sep, $key);
			
			$var = intval($var[1]) - 1;
			
			$key = substr($key, 0, $pos);
			
		}
		
		$custom_field = get_post_meta(get_the_ID(), $key, $array);
		
		if (($before === '[usp_video]') && $after === '[/usp_video]') {
			
			if (is_array($custom_field)) {
				
				foreach ($custom_field as $field_key => $field_val) {
					
					if ($field_key === $var) $field .= (filter_var($field_val, FILTER_VALIDATE_URL) !== false) ? usp_helper_video_markup($field_val) : '';
					
				}
				
			} else {
				
				$field = (filter_var($custom_field, FILTER_VALIDATE_URL) !== false) ? usp_helper_video_markup($custom_field) : '';
				
			}
			
		} else {
			
			if (is_array($custom_field)) {
				
				foreach ($custom_field as $field_key => $field_val) {
					
					if ($field_key === $var) $field .= (!empty($field_val)) ? $before . $field_val . $after : '';
					
				}
				
			} else {
				
				$allow_empty = apply_filters('usp_helper_allow_empty', false);
				
				if ($allow_empty) {
					
					$field = $before . $custom_field . $after;
					
				} else {
					
					$field = (!empty($custom_field)) ? $before . $custom_field . $after : '';
					
				}
				
			}
			
		}
		
		$fields .= apply_filters('usp_helper_field', nl2br($field));
		
	}
	
	return $fields;
	
}

function usp_helper_get_output($content) {
	
	global $usp_helper_settings;
	
	$fields = usp_helper_get_custom_fields();
	
	$html_before = isset($usp_helper_settings['all_fields_before']) ? $usp_helper_settings['all_fields_before'] : '';
	$html_after  = isset($usp_helper_settings['all_fields_after'])  ? $usp_helper_settings['all_fields_after']  : '';
	
	$display_after = isset($usp_helper_settings['display_after_post']) ? $usp_helper_settings['display_after_post'] : 0;
	
	$fields = $html_before . html_entity_decode($fields, ENT_QUOTES, get_option('blog_charset', 'UTF-8')) . $html_after;
	
	$output = $display_after ? $content . "\n\n" . $fields : $fields . "\n\n" . $content;
	
	return $output;
	
}

function usp_helper_display_custom_fields_frontend($content) {
	
	global $usp_helper_settings;
	
	$display_frontend = isset($usp_helper_settings['display_frontend']) ? $usp_helper_settings['display_frontend'] : 0;
	
	if (!$display_frontend) return $content;
	
	$submitted_only = isset($usp_helper_settings['display_submit_only']) ? $usp_helper_settings['display_submit_only'] : 0;
	
	if ($submitted_only && !usp_helper_is_submitted()) return $content;
	
	$posts_only = isset($usp_helper_settings['display_posts_only']) ? $usp_helper_settings['display_posts_only'] : 0;
	
	if ($posts_only && !is_singular()) return $content;
	
	$output = usp_helper_get_output($content);
	
	return apply_filters('usp_helper_display_frontend', $output);
	
}
add_filter('the_content', 'usp_helper_display_custom_fields_frontend');

function usp_helper_display_custom_fields_backend($content, $post_id, $int = null) {
	
	global $usp_helper_settings;
	
	$display_backend = isset($usp_helper_settings['display_backend']) ? $usp_helper_settings['display_backend'] : 0;
	
	if (!$display_backend) return $content;
	
	$submitted_only = isset($usp_helper_settings['display_submit_only']) ? $usp_helper_settings['display_submit_only'] : 0;
	
	if ($submitted_only && !usp_helper_is_submitted()) return $content;
	
	$added_to_content = get_post_meta($post_id, 'usp-added-to-content', true);
	
	if ($added_to_content === 'true') return $content;
	
	if (get_post_type() === 'usp_form') return $content;
	
	$output = usp_helper_get_output($content);
	
	if (usp_helper_is_submitted()) {
		
		$args = array('ID' => $post_id, 'post_content' => $output);
		
		$updated = wp_update_post($args);
		
		if ($updated) update_post_meta($post_id, 'usp-added-to-content', 'true');
		
	}
	
	return apply_filters('usp_helper_display_backend', $output);
	
}
add_filter('edit_post_content', 'usp_helper_display_custom_fields_backend', 10, 3);

/*
	
	More infos about HTML5/video:
	
	http://websitehelpers.com/video/
	http://videojs.com/getting-started/
	https://en.wikipedia.org/wiki/HTML5_video
	http://help.encoding.com/knowledge-base/article/correct-mime-types-for-serving-video-files/
	
*/
function usp_helper_video_markup($video) {
	
	global $usp_helper_settings;
	
	$enqueue_video = isset($usp_helper_settings['video_load_scripts']) ? $usp_helper_settings['video_load_scripts'] : 0;
	
	if (!$enqueue_video) return;
	
	$no_video = get_post_meta(get_the_ID(), 'usp-no-video', true);
	
	if ($no_video === 'true') return;
	
	// optional attributes for the <video> tag (not sure? leave as-is)
	$video_attributes = ' controls preload';
	$video_attributes = apply_filters('usp_helper_video_attributes', $video_attributes);
	
	$src = parse_url($video, PHP_URL_PATH); // autoplay only works for relative src path
	
	$mime_types = array(
		"3gp"  => "video/3gpp",
		"flv"  => "video/x-flv",
		"mkv"  => "video/webm", // video/x-matroska
		"mov"  => "video/mp4",
		
		"mpeg" => "video/mpeg",
		"mpg"  => "video/mpeg",
		"mpe"  => "video/mpeg",
		
		"mp4"  => "video/mp4",
		"ogv"  => "video/ogg",
		"webm" => "video/webm",
		
		"avi"  => "video/x-msvideo",
		"m3u8" => "application/x-mpegURL",
		"ts"   => "video/MP2T",
		"wmv"  => "video/x-ms-wmv",
	);
	
	$tmp = explode('.', $video);
	$extension = strtolower(end($tmp));
	
	$mime_type = $mime_types[$extension];
	
	$video_markup = 
'<video '. $video_attributes .'>
	<source type="'. $mime_type .'" src="'. $src .'">
	<p class="vjs-hidden">Enable JavaScript to view video.</p>
</video>';
	
	return apply_filters('usp_helper_video_markup', $video_markup);
	
}

function usp_helper_enqueue_video() {
	
	// wp_enqueue_style ( $handle, $src, $deps, $ver, $media )
	// https://developer.wordpress.org/reference/functions/wp_enqueue_style/
	
	wp_enqueue_style('usp-video', plugin_dir_url(__FILE__) .'video/videojs.css', array(), USP_HELPER_VERSION);
	
	// wp_enqueue_script ( $handle, $src, $deps, $ver, $in_footer )
	// https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	
	// uncomment next line to support IE8
	// wp_enqueue_script('usp-pro-video-ie8', plugin_dir_url(__FILE__) .'video/videojs-ie8.js', array(), USP_HELPER_VERSION, false);
	
	wp_enqueue_script('usp-video',       plugin_dir_url(__FILE__) .'video/videojs.js',   array(), USP_HELPER_VERSION, true);
	wp_enqueue_script('usp-video-utils', plugin_dir_url(__FILE__) .'video/utilities.js', array('jquery'), USP_HELPER_VERSION, true);
	
}

function usp_helper_enqueue_settings() {
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'id') && $screen->id === 'settings_page_usp_helper_settings') {
		
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('usp-settings', plugin_dir_url(__FILE__) .'assets/settings.js', array('jquery-ui-sortable'), USP_HELPER_VERSION, true);
		
	}
	
}
add_action('admin_enqueue_scripts', 'usp_helper_enqueue_settings');

function usp_helper_enqueue_video_resources() {
	
	global $usp_helper_settings;
	
	$no_video = get_post_meta(get_the_ID(), 'usp-no-video', true);
	
	if ($no_video === 'true') return;
	
	$posts_only = isset($usp_helper_settings['display_posts_only']) ? $usp_helper_settings['display_posts_only'] : 0;
	
	if ($posts_only && !is_singular()) return;
	
	$enqueue_video = isset($usp_helper_settings['video_load_scripts']) ? $usp_helper_settings['video_load_scripts'] : 0;
	
	if (!$enqueue_video) return;
	
	$submitted_only = isset($usp_helper_settings['display_submit_only']) ? $usp_helper_settings['display_submit_only'] : 0;
	
	if (!$submitted_only || ($submitted_only && usp_helper_is_submitted())) {
		
		usp_helper_enqueue_video();
		
	}
	
}
add_action('wp_enqueue_scripts', 'usp_helper_enqueue_video_resources');

function usp_helper_is_submitted() {
	
	if (
		(function_exists('usp_is_submitted') && usp_is_submitted()) || 
		(function_exists('usp_is_public_submission') && usp_is_public_submission())
	) {
		
		return true;
		
	}
	
	return false;
	
}
