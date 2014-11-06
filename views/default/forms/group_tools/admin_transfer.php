<?php
$group = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();

if ($group->owner_guid != $user->guid) {
	?>
	<div class="gt-form-field">
		<label>
			<?php
			echo elgg_view('input/checkbox', array(
				'name' => 'assume_ownership',
				'default' => false,
				'value' => 'yes',
				'id' => 'gt-admin-transfer-assume',
			));
			echo elgg_echo('group_tools:admin_transfer:assume_ownership');
			?>
		</label>
	</div>
	<?php
}
?>
<div id="gt-admin-transfer-owner">
	<div class="gt-form-field">
		<label><?php echo elgg_echo('group_tools:admin_transfer:transfer') ?></label>
		<span class="elgg-text-help"><?php echo elgg_echo('group_tools:admin_transfer:transfer:help') ?></span>
		<?php
		echo elgg_view('input/group_tools/admin_transfer', array(
			'name' => 'owner_guid',
			'entity' => $group,
		));
		?>
	</div>
</div>
<div class="elgg-foot">
	<?php
	echo elgg_view('input/hidden', array(
		'name' => 'group_guid',
		'value' => $group->guid
	));
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('group_tools:admin_transfer:submit')
	));
	?>
</div>
