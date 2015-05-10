<?php

run_function_once("group_tools_version_1_3");
run_function_once('group_tools_20141106');

/**
 * Upgrade script to fix some problem
 *
 * @return void
 */
function group_tools_version_1_3() {
	$dbprefix = elgg_get_config("dbprefix");

	$query = "SELECT ac.id AS acl_id, ac.owner_guid AS group_guid, er.guid_one AS user_guid
		FROM {$dbprefix}access_collections ac
		JOIN {$dbprefix}entities e ON e.guid = ac.owner_guid
		JOIN {$dbprefix}entity_relationships er ON ac.owner_guid = er.guid_two
		WHERE e.type = 'group'
		AND er.relationship = 'member'
		AND er.guid_one NOT IN (
			SELECT acm.user_guid
			FROM {$dbprefix}access_collections ac2
			JOIN {$dbprefix}access_collection_membership acm ON ac2.id = acm.access_collection_id
			WHERE ac2.owner_guid = ac.owner_guid
		)";

	$data = get_data($query);
	if (!empty($data)) {
		foreach ($data as $row) {
			add_user_to_access_collection($row->user_guid, $row->acl_id);
		}
	}
}

/**
 * Upgrade auto_join and suggested flags to group metadata
 * @return void
 */
function group_tools_20141106() {

	$auto_joins = elgg_get_plugin_setting('auto_join', 'group_tools');
	if ($auto_joins) {
		$auto_joins = string_to_tag_array($auto_joins);
		foreach ($auto_joins as $group_guid) {
			$group = get_entity($group_guid);
			if (elgg_instanceof($group, 'group')) {
				$group->group_tools_auto_join = true;
			}
		}
		elgg_unset_plugin_setting('auto_join', 'group_tools');
	}

	$suggested = elgg_get_plugin_setting('suggested_groups', 'group_tools');
	if ($suggested) {
		$suggested_groups = string_to_tag_array($suggested);
		foreach ($suggested_groups as $group_guid) {
			$group = get_entity($group_guid);
			if (elgg_instanceof($group, 'group')) {
				$group->group_tools_suggested = true;
			}
		}
		elgg_unset_plugin_setting('suggested_groups', 'group_tools');
	}
	
}
