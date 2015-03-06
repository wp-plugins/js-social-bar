<?php

defined('ABSPATH') or die;

$js_social_bar_social_html_codes = array (
	"facebook" => array (
		"admin_label"		=> "Facebook URL:",
		"image_path"		=> "img/facebook.png",
		"medium_image_path"	=> "img/facebook_medium.png",
		"mini_image_path"	=> "img/facebook_mini.png",
		"base_url"			=> "http://www.facebook.com/",
		"admin_text"		=> "Facebook",
		"background_color"	=> "#4d68a1",
		"text_color"		=> "#ffffff"
	),
	"pinterest" => array (
		"admin_label"		=> "Pinterest URL:",
		"image_path"		=> "img/pinterest.png",
		"medium_image_path"	=> "img/pinterest_medium.png",
		"mini_image_path"	=> "img/pinterest_mini.png",
		"base_url"			=> "http://www.pinterest.com/",
		"admin_text"		=> "Pinterest",
		"background_color"	=> "#cc2028",
		"text_color"		=> "#ffffff"
	),
	"tumblr" => array (
		"admin_label"		=> "Tumblr URL:",
		"image_path"		=> "img/tumblr.png",
		"medium_image_path"	=> "img/tumblr_medium.png",
		"mini_image_path"	=> "img/tumblr_mini.png",
		"base_url"			=> "http://www.tumblr.com/",
		"admin_text"		=> "Tumblr",
		"background_color"	=> "#35465d",
		"text_color"		=> "#ffffff"
	),
	"twitter" => array (
		"admin_label"		=> "Twitter URL:",
		"image_path"		=> "img/twitter.png",
		"medium_image_path"	=> "img/twitter_medium.png",
		"mini_image_path"	=> "img/twitter_mini.png",
		"base_url"			=> "http://www.twitter.com/",
		"admin_text"		=> "Twitter",
		"background_color"	=> "#56a0d6",
		"text_color"		=> "#ffffff"
	),
	"google_plus" => array (
		"admin_label"		=> "Google+ URL:",
		"image_path"		=> "img/google_plus.png",
		"medium_image_path"	=> "img/google_plus_medium.png",
		"mini_image_path"	=> "img/google_plus_mini.png",
		"base_url"			=> "http://plus.google.com/+",
		"admin_text"		=> "Google+",
		"background_color"	=> "#d64435",
		"text_color"		=> "#ffffff"
	),
	"linkedin" => array (
		"admin_label"		=> "Linkedin URL:",
		"image_path"		=> "img/linkedin.png",
		"medium_image_path"	=> "img/linkedin_medium.png",
		"mini_image_path"	=> "img/linkedin_mini.png",
		"base_url"			=> "http://www.linkedin.com/in/",
		"admin_text"		=> "Linkedin",
		"background_color"	=> "#0177b5",
		"text_color"		=> "#ffffff"
	),
	"youtube" => array (
		"admin_label"		=> "Youtube URL:",
		"image_path"		=> "img/youtube.png",
		"medium_image_path"	=> "img/youtube_medium.png",
		"mini_image_path"	=> "img/youtube_mini.png",
		"base_url"			=> "http://www.youtube.com/user/",
		"admin_text"		=> "Youtube",
		"background_color"	=> "#cc181e",
		"text_color"		=> "#ffffff"
	),
	"instagram" => array (
		"admin_label"		=> "Instagram URL:",
		"image_path"		=> "img/instagram.png",
		"medium_image_path"	=> "img/instagram_medium.png",
		"mini_image_path"	=> "img/instagram_mini.png",
		"base_url"			=> "http://instagram.com/",
		"admin_text"		=> "Instagram",
		"background_color"	=> "#623a32",
		"text_color"		=> "#ffffff"
	),
	"soundcloud" => array (
		"admin_label"		=> "Soundcloud URL:",
		"image_path"		=> "img/soundcloud.png",
		"medium_image_path"	=> "img/soundcloud_medium.png",
		"mini_image_path"	=> "img/soundcloud_mini.png",
		"base_url"			=> "http://soundcloud.com/",
		"admin_text"		=> "Soundcloud",
		"background_color"	=> "#ff6800",
		"text_color"		=> "#ffffff"
	),
	"stumbleupon" => array (
		"admin_label"		=> "StumbleUpon URL:",
		"image_path"		=> "img/stumbleupon.png",
		"medium_image_path"	=> "img/stumbleupon_medium.png",
		"mini_image_path"	=> "img/stumbleupon_mini.png",
		"base_url"			=> "http://www.stumbleupon.com/",
		"admin_text"		=> "StumbleUpon",
		"background_color"	=> "#ea4b24",
		"text_color"		=> "#ffffff"
	),
	"vkontakte" => array (
		"admin_label"		=> "VKontakte URL:",
		"image_path"		=> "img/vkontakte.png",
		"medium_image_path"	=> "img/vkontakte_medium.png",
		"mini_image_path"	=> "img/vkontakte_mini.png",
		"base_url"			=> "http://vk.com/",
		"admin_text"		=> "VKontakte",
		"background_color"	=> "#537599",
		"text_color"		=> "#ffffff"
	)
);
function js_social_bar_get_ordered_options() {
	global $js_social_bar_social_html_codes;
	$options = get_option("js_social_bar_options");
	$comma_separated_options = $options["icons_order"];
	if(empty($comma_separated_options)) {
		return array_keys($js_social_bar_social_html_codes);
	} else {
		return explode(",", $comma_separated_options);
	}
}

function js_social_bar_get_url_options() {
	global $js_social_bar_social_html_codes;
	$return_value = array();
	foreach($js_social_bar_social_html_codes as $array) {
		$return_value[] = $array['property_id'];
	}
	return $return_value;
}

function js_social_bar_get_social_html_codes() {
	global $js_social_bar_social_html_codes;
	return $js_social_bar_social_html_codes;
}

function js_social_bar_get_active_social_html_codes() {
	global $js_social_bar_social_html_codes;
	$options = get_option("js_social_bar_options");
	$ret = array();
	foreach($js_social_bar_social_html_codes as $key=>$value) {
		if($options['socials'][$key]['enabled'] == 'true') {
			$ret[$key] = $value;
		}
	}
	return $ret;
}

function js_social_bar_at_least_one_url_enabled() {
	$options = get_option("js_social_bar_options");
	foreach($options['socials'] as $option) {
		if($option['enabled']) {
			return true;
		}
	}
	return false;
}
?>