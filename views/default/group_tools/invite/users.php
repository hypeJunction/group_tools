<?php
$group = elgg_extract('entity', $vars);

if (elgg_is_admin_logged_in()) {
	?>
	<div>
		<label>
			<?php
			echo elgg_view('input/checkbox', array(
				'name' => 'all_users',
				'value' => 'yes',
				'id' => 'gt-invite-users-all',
			));
			echo elgg_echo('group_tools:group:invite:users:all');
			?>
		</label>
	</div>
	<?php
}
?>
<div id="gt-invite-users-pick">
	<p class="elgg-text-help">
		<?php echo elgg_echo('group_tools:group:invite:users:description') ?>
	</p>
	<div>
		<label><?php echo elgg_echo('group_tools:group:invite:users:label') ?></label>
		<?php
		echo elgg_view('input/userpicker');
		?>
	</div>
</div>

