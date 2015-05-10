<?php

/**
 * Elgg groups plugin
 *
 * @package ElggGroups
 */
elgg_load_js('group_tools.invite');

$user = elgg_get_logged_in_user_entity();

$group = elgg_extract("entity", $vars, elgg_get_page_owner_entity());
$owner = $group->getOwnerEntity();

$invite_site_members = elgg_extract("invite", $vars, "no");
$invite_email = elgg_extract("invite_email", $vars, "no");
$invite_csv = elgg_extract("invite_csv", $vars, "no");

$forward_url = $group->getURL();

echo elgg_view('group_tools/invite/filter', $vars);

echo '<fieldset id="gt-invite-friends" class="gt-invite-form elgg-state-active">';
echo elgg_view('group_tools/invite/friends', $vars);
echo '</fieldset>';

// invite site members
if ($invite_site_members) {
	echo '<fieldset id="gt-invite-users" class="gt-invite-form hidden">';
	echo elgg_view('group_tools/invite/users', $vars);
	echo '</fieldset>';
}

// invite by email
if ($invite_email) {
	echo '<fieldset id="gt-invite-email" class="gt-invite-form hidden">';
	echo elgg_view('group_tools/invite/email', $vars);
	echo '</fieldset>';
}

// csv upload
if ($invite_csv) {
	echo '<fieldset id="gt-invite-csv" class="gt-invite-form hidden">';
	echo elgg_view('group_tools/invite/csv', $vars);
	echo '</fieldset>';
}

echo '<fieldset>';

// comment
echo '<div>';
echo '<label>' . elgg_echo('group_tools:group:invite:text') . '</label>';
echo elgg_view('input/plaintext', array(
	'name' => 'comment',
));
echo '</div>';

// renotify existing invites
if ($group->canEdit()) {
	echo '<div>';
	echo '<label>' . elgg_view('input/checkbox', array(
		'name' => 'resend',
		'value' => 'yes',
	)) . elgg_echo('group_tools:group:invite:resend') . '</label>';
	echo '</div>';
}

if (elgg_is_admin_logged_in()) {
	echo '<div>';
	echo elgg_view('input/radio', array(
		'name' => 'invite_action',
		'value' => 'invite',
		'options' => array(
			elgg_echo('group_tools:invite:action:invite') => 'invite',
			elgg_echo('group_tools:invite:action:add') => 'add'
		)
	));
	echo '</div>';
}
echo elgg_view('input/hidden', array(
	'name' => 'forward_url',
	'value' => $forward_url
));
echo elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid
));

echo '</fieldset>';

// show buttons
echo '<fieldset class="elgg-foot">';
echo elgg_view('input/submit', array(
	'name' => 'submit',
	'value' => elgg_echo('invite')
));
echo '</fieldset>';
