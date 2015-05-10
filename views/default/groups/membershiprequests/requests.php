<?php

$group = elgg_extract('entity', $vars);

$title = elgg_echo('group_tools:groups:membershipreq:requests');

if (!empty($vars['requests']) && is_array($vars['requests'])) {
	$content = '<ul class="elgg-list">';
	foreach ($vars['requests'] as $user) {
		$content .= '<li class="elgg-item">';
		$content .= elgg_view('groups/membershiprequests/list/user', array(
			'user' => $user,
			'group' => $group
		));
		$content .= '</li>';
	}
	$content .= '</ul>';
} else {
	$content = '<p class="gt-no-results">' . elgg_echo('groups:requests:none') . '</p>';
}

echo elgg_view_module('info', $title, $content);