<?php
$user = elgg_get_logged_in_user_entity();
$friends = $user->getFriends("", false);

if (empty($friends)) {
	echo '<p class="gt-no-results">' . elgg_echo('groups:nofriendsatall') . '</p>';
	return true;
}
?>
<div class="gt-form-field">
	<label>
		<?php
		echo elgg_view('input/checkbox', array(
			'id' => 'gt-friends-toggle',
		));
		echo elgg_echo("group_tools:group:invite:friends:toggle");
		?>
	</label>
</div>
<div class="gt-form-field">
	<label><?php echo elgg_echo('group_tools:group:invite:friends:label') ?></label>
	<div id="gt-invite-friends-friendspicker">
		<?php
		echo elgg_view('input/friendspicker', array(
			'entities' => $friends,
			'name' => 'friends',
			'highlight' => 'all'
		));
		?>
	</div>
</div>