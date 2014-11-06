<?php

$group = elgg_extract('entity', $vars);

// show invitations
$title = elgg_echo('group_tools:groups:membershipreq:invitations');

if (!empty($vars['invitations']) && is_array($vars['invitations'])) {
	$content = '<ul class="elgg-list">';
	foreach ($vars['invitations'] as $user) {
		$content .= '<li class="elgg-item">';
		$content .= elgg_view('groups/membershiprequests/list/user', array(
			'user' => $user,
			'group' => $group
		));
		$content .= '</li>';
	}
	$content .= '</ul>';
} else {
	$content = '<p class="gt-no-results">' . elgg_echo('group_tools:groups:membershipreq:invitations:none') . '</p>';
}

echo elgg_view_module('info', $title, $content);