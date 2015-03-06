<?php
defined('ABSPATH') or die;
?>
<script type="text/javascript">
	jQuery("#js_tabs").tabs({
		active: <?= $selected_tab ?>
	});
	jQuery("#js_slider").slider(
		{
			min: -1350,
			max: -150,
			step: 150,
			value: -<?= $animation_speed ?>,
			change: function(event, ui) {
				jQuery(this).parent().find("input").val(Math.abs(ui.value));
			}
		}
	);
	jQuery("#icons-order-list").sortable({
		scrollSpeed: 4,
		cursor: "move",
		update: function(event, ui) {
			orderedElements = [];
			jQuery("#icons-order-list li").each(
				function(index) {
					orderedElements[index] = jQuery(this).attr("id");
				}
			);
			jQuery("#social-bar-icon-order-input").val(orderedElements.join());
		}
	});
	jQuery("#icons-order-list").disableSelection();
	jQuery(".toggle-button .input-checkbox, .toggle-button .input-radio").bootstrapSwitch({
			size: "mini",
			onSwitchChange: function(event, state) {
				jQuery(this).parent().parent().parent().parent().find(".input-checkbox-hidden").val(state);
			}
	});
</script>