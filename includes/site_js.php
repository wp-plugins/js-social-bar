<?php
defined('ABSPATH') or die;
?>
<script type="text/javascript">
	jQuery(".social-bar").css("position", "absolute");
	if(supports("transition")) {
		jQuery(window).scroll(
			jQuery.debounce( 250, function(){
				jQuery(".social-bar").stop().transition({top: 0.2*jQuery(window).height() + jQuery(document).scrollTop()}, <?=$animation_speed?>);
			})
		);
		jQuery(".social-bar-link, .social-bar-link-mini, .social-bar-link-medium").mouseover(
			function() {
				jQuery(this).stop().transition(
					{left: "0"}, 
					250
				)
			}
		);
		jQuery(".social-bar-link").mouseout(
			function() {
				jQuery(this).stop().transition(
					{left: "-7px"}, 
					250
				)
			}
		);
		jQuery(".social-bar-link-mini").mouseout(
			function() {
				jQuery(this).stop().transition(
					{left: "-4px"}, 
					250
				)
			}
		);
		jQuery(".social-bar-link-medium").mouseout(
			function() {
				jQuery(this).stop().transition(
					{left: "-6px"}, 
					250
				)
			}
		);
	} else {
		jQuery(window).scroll(
			jQuery.debounce( 250, function(){
				jQuery(".social-bar").stop().animate({top: 0.2*jQuery(window).height() + jQuery(document).scrollTop()}, <?=$animation_speed?>);
			})
		);
		jQuery(".social-bar-link, .social-bar-link-mini, .social-bar-link-medium").mouseover(
			function() {
				jQuery(this).stop().animate(
					{left: "0"}, 
					250
				)
			}
		);
		jQuery(".social-bar-link").mouseout(
			function() {
				jQuery(this).stop().animate(
					{left: "-7px"}, 
					250
				)
			}
		);
		jQuery(".social-bar-link-medium").mouseout(
			function() {
				jQuery(this).stop().animate(
					{left: "-6px"}, 
					250
				)
			}
		);
		jQuery(".social-bar-link-mini").mouseout(
			function() {
				jQuery(this).stop().animate(
					{left: "-4px"}, 
					250
				)
			}
		);
	}
</script>