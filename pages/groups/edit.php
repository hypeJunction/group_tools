<?php

gatekeeper();

elgg_load_library('elgg:groups');

$page = get_input('page');
$guid = get_input('guid');
$group = get_entity($guid);

$container_guid = get_input('container_guid');
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$container = get_entity($container_guid);

$filter_context = get_input('filter', 'profile');
$filter = elgg_view('group_tools/edit/filter', array(
	'filter_context' => $filter_context,
	'entity' => $group,
		));

if (elgg_instanceof($group, 'group')) {
	$title = elgg_echo('groups:edit');

	elgg_set_page_owner_guid($group->guid);

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	if ($group->canEdit()) {
		$content = elgg_view("group_tools/edit/$filter_context", array(
			'entity' => $group
		));
	}
	if (!$content) {
		$content = elgg_echo('groups:noaccess');
	}
} else {
	$title = elgg_echo('groups:add');

	elgg_set_page_owner_guid($container->guid);

	elgg_push_breadcrumb($title);

	if (!$container || !$container->canWriteToContainer(0, 'group')) {
		$content = elgg_echo('groups:noaccess');
	} else {
		if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
			$content = elgg_view("group_tools/edit/$filter_context");
		}
	}
	if (!$content) {
		$content = elgg_echo('groups:cantcreate');
	}
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => $filter,
	'class' => 'gt-edit-group-layout',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
