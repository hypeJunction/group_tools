<?php

$entity = elgg_extract('entity', $vars);
$filter_context = elgg_extract('filter_context', $vars, 'profile');

$tabs = array(
	'profile' => array(
		'text' => elgg_echo('group_tools:filter:edit:profile'),
		'href' => elgg_http_add_url_query_elements(current_page_url(), array(
			'filter' => 'profile',
		)),
		'priority' => 100,
		'data' => array(
			'selected' => ($filter_context == 'profile')
		)
	),
);

if (elgg_instanceof($entity, 'group')) {
	$tabs['other'] = array(
		'text' => elgg_echo('group_tools:filter:edit:other'),
		'href' => elgg_http_add_url_query_elements(current_page_url(), array(
			'filter' => 'other',
		)),
		'priority' => 200,
		'data' => array(
			'selected' => ($filter_context == 'other')
		)
	);
}

if (count($tabs) > 1) {
	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
}

echo elgg_view_menu('filter', array(
	'handler' => 'group_tools',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz gt-edit-filter',
));
