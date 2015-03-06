<?php

defined('ABSPATH') or die;

add_action('admin_menu', 'js_social_bar_settings');
add_action('admin_enqueue_scripts', 'js_social_bar_admin_enqueue_script');

if (!empty($GLOBALS['pagenow']) && ('options-general.php' === $GLOBALS['pagenow'] || 'options.php' === $GLOBALS['pagenow'])) {
    add_action('admin_init', 'js_social_bar_register_settings');
}

add_action('admin_post_js_social_bar_save_settings', 'js_social_bar_save_settings');

function js_social_bar_save_settings() {
	$return_page = $_POST['_wp_http_referer'];
	update_option('js_social_bar_options', $_POST['js_social_bar_options']);
	wp_redirect($return_page);
	exit;
}

function js_social_bar_register_settings() {	
	$default_options = array(
		"selected_tab" => 0,
		"animation_speed" => 750,
		"icons_size" => "normal",
		"icons_order" => "facebook,pinterest,tumblr,twitter,google_plus,linkedin,youtube,instagram,soundcloud,stumbleupon,vkontakte",
		"facebook"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"pinterest"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"tumblr"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"twitter"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"google_plus"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"linkedin"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"youtube"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"instagram"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"soundcloud"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"stumbleupon"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		),
		"vkontakte"	=> array(
			"enabled"	=> false,
			"url"		=> ""
		)
	);
	if(!get_option('js_social_bar_options')) {
		add_option('js_social_bar_options', $default_options);
	}
}

function js_social_bar_settings() {
    add_menu_page('JS Social Bar Settings', 'JS Social Bar', 'administrator', 'js_social_bar_settings', 'js_social_bar_settings_form', plugins_url('img/menu_icon.png', __FILE__));
}

/*
 * This function will enqueue all scripts needed in the admin panel
 */
function js_social_bar_admin_enqueue_script() {
	// JQuery
    wp_enqueue_script('jquery');
	// JQuery UI
	js_social_bar_register_ui_scripts();
	// Bootstrap
	wp_register_script('bootstrap', plugins_url('js/bootstrap.min.js', __FILE__), array("jquery"));
    wp_enqueue_script('bootstrap');
	// Bootstrap Switch (transforms checkboxes in ON/OFF switches
	wp_register_script('bootstrap-switch', plugins_url('js/bootstrap-switch.js', __FILE__), array("bootstrap"));
    wp_enqueue_script('bootstrap-switch');
	// The admin style
	wp_register_style('js-social-bar-admin', plugins_url('css/admin.css', __FILE__));
    wp_enqueue_style('js-social-bar-admin');
	// The bootstrap switch style
	wp_register_style('bootstrap-switch', plugins_url('css/bootstrap-switch.min.css', __FILE__));
    wp_enqueue_style('bootstrap-switch');
}

/*
 * Puts the selected scripts in the head
 */
function js_social_bar_register_ui_scripts() {
	// Add your script names to this array.
	$scripts = array('jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-slider');
	// Register the original scripts.
	foreach($scripts as $script) {
		wp_enqueue_script($script);
	}
	// This global contains all of wordpress' registered scripts.
	global $wp_scripts;
	$sources = array();
	// Loop through the registered scripts, copy the data we need.
	foreach($wp_scripts->registered as $name => $data) {
		if(in_array($name, $scripts)) {
			$sources[$name]['src'] = $data->src;
			$sources[$name]['ver'] = $data->ver;
			$sources[$name]['deps'] = $data->deps;
		}
	}
	// Loop through our selected scripts, deregister them, then register
	// them again, only this time using 'false' to put them in the head.
	foreach($scripts as $script) {
		wp_deregister_script($script);
		wp_enqueue_script(
			$script,
			site_url($sources[$script]['src']),
			$sources[$script]['deps'],
			$sources[$script]['ver'],
			false
		);
	}
}

function js_social_bar_get_url_inputs_html() {	
	$js_social_bar_options = get_option('js_social_bar_options');
	
	//Let's define a format for the output HTML
	$html_to_format = '
	<tr>
		<td class="little-column">
			<div class="toggle-button">
				<input type="checkbox" class="input-checkbox" %s/>
			</div>
			<input type="hidden" name="js_social_bar_options[socials][%s][enabled]" value="%s" class="input-checkbox-hidden" />
		</td>
		<td class="little-column">
			<span class="output-text">
				%s
			</span>
		</td>
		<td class="little-column base-url">
			<span class="output-text">
				%s
			</span>
		</td>
		<td>
			%s
		</td>
	</tr>
	';
	
	// Get the code for all known socials (Inside the includes/social_html_code.php file)
	$social_html_codes = js_social_bar_get_social_html_codes();
	$input_text_url = '<input type="text" name="js_social_bar_options[socials][%s][url]" value="%s" class="input-text" />';
	
	// This variable will hold the full output html
	$full_html = '<table>';
	
	// Loop over the array of known codes
	foreach($social_html_codes as $code=>$value_array) {
		$active_flag = $js_social_bar_options['socials'][$code]['enabled'] == 'true';
		
		// Format the single input HTML code
		$input_text = sprintf(
			$input_text_url,
			$code, 
			$js_social_bar_options['socials'][$code]['url']
		);
		
		// Format the previous HTML string with all the parameters
		$full_html .= sprintf(
			$html_to_format,
			$active_flag ? 'checked ' : '',
			$code,
			$active_flag ? 'true' : 'false',
			$value_array['admin_label'],
			$value_array['base_url'],
			$input_text
		);
	}
	$full_html .= '</table>';
	// Ok, it's done, return the full html
	return $full_html;
}

/*
 * Builds the sortable list
 */
function js_social_bar_get_list_html() {
	$js_social_bar_options = get_option('js_social_bar_options');
	$options = js_social_bar_get_ordered_options();
	$social_html_codes = js_social_bar_get_social_html_codes();
	
	$html_to_format = '
	<li class="ui-state-default admin-social-bar-icon" id="%s" style="background-color: %s; color:%s;%s">%s</li>
	';
	
	$full_html = '';
	foreach($options as $option) {
		$full_html .= sprintf(
			$html_to_format,
			$option,
			$social_html_codes[$option]['background_color'],
			$social_html_codes[$option]['text_color'],
			$js_social_bar_options['socials'][$option]['enabled'] == 'true' && !empty($js_social_bar_options['socials'][$option]['url']) ? '' : 'display: none',
			$social_html_codes[$option]['admin_text']
		);
	}
	return $full_html;
}

/*
 * Builds the sortable list
 */
function js_social_bar_settings_form() {
	$js_social_bar_options = get_option('js_social_bar_options');
	
	$animation_speed = empty($js_social_bar_options['animation_speed']) ? '750' : $js_social_bar_options['animation_speed'];
	$selected_tab = empty($js_social_bar_options['selected_tab']) ? '0' : $js_social_bar_options['selected_tab'];
	
	$options = array_keys(js_social_bar_get_social_html_codes());
	$ordered_options = js_social_bar_get_ordered_options();
	$mini_icons = $js_social_bar_options['icons_size'] == 'mini';
	$medium_icons = $js_social_bar_options['icons_size'] == 'medium';
    $html = '</pre>
	<div class="social-bar-admin">
		<form action="' . admin_url('admin-post.php') . '" method="post" name="options">
			<h2>JS Social Bar Settings</h2>
			' . wp_nonce_field('update-options') . '
			<div id="js_tabs">
				<ul class="js-tabs">
					<li><a href="#js_tab_1" onclick="jQuery(\'#js_social_bar_selected_tab_input\').val(0)">Define buttons and URLs</a></li>
					<li><a href="#js_tab_2" onclick="jQuery(\'#js_social_bar_selected_tab_input\').val(1)">Choose the button order</a></li>
					<li><a href="#js_tab_3" onclick="jQuery(\'#js_social_bar_selected_tab_input\').val(2)">Other options</a></li>
				</ul>
				<div id="js_tab_1">
					<br />
					Activate buttons by using the ON/OFF switch.
					<br />
					<b>Note:</b> Even if switched on, the button won\'t show until its url is set.
					<br />
					<br />
					' . js_social_bar_get_url_inputs_html() . '
				</div>
				<div id="js_tab_2">
						';
		if(js_social_bar_at_least_one_url_enabled()):
			$html.= '
					<br />
					Use the mouse to drag items to different positions
					<br />
					<ul id="icons-order-list">
						' . js_social_bar_get_list_html() . '
					</ul>';
		else:
			$html.= '
					Enable at least one button to see the button ordering panel.';
		endif;
		$html.='
				</div>
				<div id="js_tab_3">
					<br />
					<table style="width: 100%">
						<tr>
							<td class="little-column">
								<span class="output-text">Icon set:</span>
							</td>
							<td>
								<select name="js_social_bar_options[icons_size]">
									<option value="normal"' . (!$mini_icons && !$medium_icons ? ' selected="selected"' : '') . '>
										Default Icon set (you should use it if you have maximum 5 active buttons)
									</option>
									<option value="medium"' . ($medium_icons ? ' selected="selected"' : '') . '>
										Medium Icon set (you should use it if you have more than 5 active buttons)
									</option>
									<option value="mini"' . ($mini_icons ? ' selected="selected"' : '') . '>
										Mini Icon set (you should use it if you have more than 10 active buttons)
									</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="little-column">
								<span class="output-text">Social Bar speed:</span>
							</td>
							<td>
								<div id="js_slider"></div>
								<input name="js_social_bar_options[animation_speed]" value="' . $animation_speed . '" type="hidden" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<br />
			<input type="hidden" name="action" value="js_social_bar_save_settings">
			<input type="hidden" name="js_social_bar_options[selected_tab]" id="js_social_bar_selected_tab_input" value="' . $js_social_bar_options['selected_tab'] . '"/>
			<input type="hidden" name="js_social_bar_options[icons_order]" id="social-bar-icon-order-input" value="' .implode($ordered_options, ',') . '"/>
			<input type="submit" name="Submit" value="Update Settings" class="button button-primary"/>
		</form>
	</div>
<pre>
';
 
    echo $html;
	
	//Print the needed js
	include "includes/admin_js.php";
 
}
?>