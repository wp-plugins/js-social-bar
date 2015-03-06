<?php

defined('ABSPATH') or die;

add_action('wp_footer', 'js_social_bar');
add_action('wp_enqueue_scripts', 'js_social_bar_enqueue_script');

/*
 * This function enqueues all the scripts the plugin needs to work properly
 */
function js_social_bar_enqueue_script() {
	// JQuery needs to be loaded
    wp_enqueue_script('jquery');
	
	/* 
	 * JQuery debounce(http://benalman.com/projects/jquery-throttle-debounce-plugin/)
	 * Used to fire an event when the user stops scrolling the page.
	 */
	wp_register_script('js-social-bar-jquery-debounce', plugins_url('js/jquery.debounce.min.js', __FILE__), array("jquery"));
    wp_enqueue_script('js-social-bar-jquery-debounce');
	
	/* 
	 * JQuery transit(https://github.com/rstacruz/jquery.transit)
	 * CSS3 animations for javascript
	 */
	wp_register_script('js-social-bar-jquery-transit', plugins_url('js/jquery.transit.min.js', __FILE__), array("jquery"));
    wp_enqueue_script('js-social-bar-jquery-transit');
	
	/* 
	 * Supports - used to check for CSS3 support (because JQuery Transit doesn't automatically
	 * replace CSS3 animations with js animations if CSS3 is not supported)
	 * CSS3 animations for javascript
	 */
	wp_register_script('js-social-bar-supports', plugins_url('js/supports.min.js', __FILE__));
    wp_enqueue_script('js-social-bar-supports');
	
	/* 
	 * The social bar CSS style
	 */
	wp_register_style('js-social-bar-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('js-social-bar-style');
}



function js_social_bar() {
	$js_social_bar_options = get_option('js_social_bar_options');
	$mini_icons = $js_social_bar_options['icons_size'] == 'mini';
	$medium_icons = $js_social_bar_options['icons_size'] == 'medium';
	
	$animation_speed = empty($js_social_bar_options['animation_speed']) ? '750' : $js_social_bar_options['animation_speed'];
	
	$full_html_to_format = '
		<div class="social-bar">
			%s
		</div>
	';
	if($mini_icons) {
		$single_html_to_format .= '
			<a href="%s" class="social-bar-link-mini" target="_blank">
				<img src="%s" />
			</a>
		';
	} else if ($medium_icons) {
		$single_html_to_format .= '
			<a href="%s" class="social-bar-link-medium" target="_blank">
				<img src="%s" />
			</a>
		';
	} else {
		$single_html_to_format .= '
			<a href="%s" class="social-bar-link" target="_blank">
				<img src="%s" />
			</a>
		';
	}
	
	// Get the sorted options
	$ordered_options = js_social_bar_get_ordered_options();
	// Get HTML codes for all the known socials
	$social_html_codes = js_social_bar_get_social_html_codes();
	
	//This will be the full html
	$full_links_html = "";
	
	//Loop over the ordered options
	foreach($ordered_options as $id) {
		//Let's store the current value array into $values
		$values = $social_html_codes[$id];
		
		//In order to be displayed, not only the option must be enabled, but it needs to have a non-empty url
		$social_url = trim($js_social_bar_options['socials'][$id]['url']);
		$button_enabled = trim($js_social_bar_options['socials'][$id]['enabled']) == 'true';
		if($button_enabled && $social_url) {
			$image_url = plugins_url($values['image_path'], __FILE__);
			if($mini_icons) {
				$image_url = plugins_url($values['mini_image_path'], __FILE__);
			} else if($medium_icons) {
				$image_url = plugins_url($values['medium_image_path'], __FILE__);
			}
			//Format the link HTML with the social URL and the image URL
			$link_html = sprintf(
				$single_html_to_format,
				$values['base_url'].$social_url,
				$image_url
			);
			$full_links_html .= $link_html;
		}
	}
	
	//Create the full html
	$full_html = sprintf($full_html_to_format, $full_links_html);
	//Print it
	echo $full_html;
	
	//Print the needed js
	include "includes/site_js.php";
}


?>