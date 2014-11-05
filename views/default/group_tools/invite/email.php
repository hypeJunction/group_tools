<?php
$group = elgg_extract('entity', $vars);
?>

<p class='elgg-text-help'>
	<?php echo elgg_echo('group_tools:group:invite:email:description') ?>
</p>
<div>
	<label><?php echo elgg_echo('group_tools:group:invite:email:label') ?></label>
	<?php
	echo elgg_view('input/plaintext', array(
		'name' => 'emails',
	));
	?>
</div>