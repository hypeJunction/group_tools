<?php

$user = elgg_extract('user', $vars);
$group = elgg_extract('group', $vars);
$size = elgg_extract('size', $vars, 'small');

$icon = elgg_view_entity_icon($user, $size);

$menu = elgg_view_menu('group_tools_requests', array(
	'user' => $user,
	'group' => $group,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
		));

$user_link = elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => $user->name,
	'is_trusted' => true,
		));

$body = elgg_view('user/elements/summary', array(
	'entity' => $user,
	'title' => $user_link,
		));

echo elgg_view_image_block($icon, $body, array(
	'image_alt' => $menu,
	'class' => 'gt-membership-request-item',
));
