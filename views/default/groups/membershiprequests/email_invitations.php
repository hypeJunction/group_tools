<?php

$group = elgg_extract('entity', $vars);

// show invitations
$title = elgg_echo('group_tools:groups:membershipreq:email_invitations');

if (!empty($vars['emails']) && is_array($vars['emails'])) {
	$content = '<ul class="elgg-list">';
	foreach ($vars['emails'] as $annotation) {
		$content .= '<li class="elgg-item">';
		$content .= elgg_view('groups/membershiprequests/list/email', array(
			'annotation' => $annotation,
			'group' => $group
		));
		$content .= '</li>';
	}
	$content .= '</ul>';
} else {
	$content = '<p class="gt-no-results">' . elgg_echo('group_tools:groups:membershipreq:email_invitations:none') . '</p>';
}

echo elgg_view_module('info', $title, $content);
