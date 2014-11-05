<?php
$group = elgg_extract('entity', $vars);
?>
<p class='elgg-text-help'>
	<?php echo elgg_echo('group_tools:group:invite:csv:description') ?>
</p>
<div>
	<label><?php echo elgg_echo('group_tools:group:invite:csv:label') ?></label>
	<?php
	echo elgg_view("input/file", array(
		'name' => 'csv'
	));
	?>
</div>