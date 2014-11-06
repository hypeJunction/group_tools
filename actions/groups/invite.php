<?php

$loggedin_user = elgg_get_logged_in_user_entity();

$group_guid = (int) get_input('group_guid');
$group = get_entity($group_guid);

if (!$group instanceof ElggGroup) {
	// no group or no editing permissions
	register_error(elgg_echo('group_tools:action:error:edit'));
	forward(REFERER);
}

$email_input = get_input('emails');
$friend_guids = get_input('friends', '');
$site_member_guids = get_input('members', '');

if (!is_array($friend_guids)) {
	$friend_guids = string_to_tag_array($friend_guids);
}
if (!is_array($site_member_guids)) {
	$site_member_guids = string_to_tag_array($site_member_guids);
}

$email_guids = array();
$emails = array();

// make sure we include unvalidated users
$ha = access_get_show_hidden_status();
access_show_hidden_entities(true);

// separate emails of registered and non-registered users
if ($email_input) {
	$email_addresses = trim($email_input);
	if (strlen($email_addresses) > 0) {
		$email_addresses = preg_split('/\\s+/', $email_addresses, -1, PREG_SPLIT_NO_EMPTY);
	}
	if (is_array($email_addresses)) {
		// filter out email address that already have an account
		foreach ($email_addresses as $email) {
			$users = get_user_by_email($email);
			if ($users) {
				$email_guids[] = $users[0]->guid;
			} else {
				$emails[] = $email;
			}
		}
	}
}

// process csv and separate emails of registered and non-registered users
$csv = get_uploaded_file('csv');
if (!empty($csv)) {
	$file_location = $_FILES['csv']['tmp_name'];

	if ($fh = fopen($file_location, 'r')) {
		while (($data = fgetcsv($fh, 0, ';')) !== false) {
			/*
			 * data structure
			 * data[0] => displayname
			 * data[1] => e-mail address
			 */
			$email = '';
			if (isset($data[1])) {
				$email = trim($data[1]);
			}

			if (!empty($email) && is_email_address($email)) {
				$users = get_user_by_email($email);
				if ($users) {
					$email_guids[] = $users[0]->guid;
				} else {
					$emails[] = $email;
				}
			}
		}
	}
}

$user_guids = array_merge($site_member_guids, $friend_guids, $email_guids);
$user_guids = array_unique($user_guids);

$text = get_input('comment');
$resend = (get_input('resend') == 'yes') ? true : false;

$add_as_admin = false;

$invite_action = get_input('invite_action', 'invite');
if (elgg_is_admin_logged_in()) {
	// add all users?
	if (get_input('all_users') == 'yes') {
		$site = elgg_get_site_entity();

		$options = array(
			'limit' => false,
			'callback' => 'group_tools_guid_only_callback'
		);

		$user_guids = $site->getMembers($options);
	}

	// add users directly?
	if ($invite_action == 'add') {
		$add_as_admin = true;
	}
}

if (empty($user_guids) && empty($emails)) {
	// no emails or guids to process
	register_error(elgg_echo('group_tools:action:error:input'));
	forward(REFERER);
}

// counters
$already_invited = $invited = $member = $join = 0;

// invite existing users
if (!empty($user_guids)) {
	if ($add_as_admin) {
		// add users directly
		foreach ($user_guids as $u_id) {
			if ($user = get_user($u_id)) {
				if (!$group->isMember($user)) {
					if (group_tools_add_user($group, $user, $text)) {
						$join++;
					}
				} else {
					$member++;
				}
			}
		}
	} else {
		// invite users
		foreach ($user_guids as $u_id) {
			if ($user = get_user($u_id)) {
				if (!$group->isMember($user)) {
					if (!check_entity_relationship($group->guid, 'invited', $user->guid) || $resend) {
						if (group_tools_invite_user($group, $user, $text, $resend)) {
							$invited++;
						}
					} else {
						// user was already invited
						$already_invited++;
					}
				} else {
					$member++;
				}
			}
		}
	}
}

// Invite members by e-mail address
if (!empty($emails)) {
	foreach ($emails as $email) {
		$invite_result = group_tools_invite_email($group, $email, $text, $resend);
		if ($invite_result === true) {
			$invited++;
		} elseif ($invite_result === null) {
			$already_invited++;
		}
	}
}

// which message to show
if (!empty($invited) || !empty($join)) {
	if ($add_as_admin) {
		system_message(elgg_echo('group_tools:action:invite:success:add', array($join, $already_invited, $member)));
	} else {
		system_message(elgg_echo('group_tools:action:invite:success:invite', array($invited, $already_invited, $member)));
	}
} else {
	if ($add_as_admin) {
		register_error(elgg_echo('group_tools:action:invite:error:add', array($already_invited, $member)));
	} else {
		register_error(elgg_echo('group_tools:action:invite:error:invite', array($already_invited, $member)));
	}
}

forward(REFERER);
