<?php

/**
 * A group's member requests
 *
 * @uses $vars['entity']   ElggGroup
 * @uses $vars['requests'] Array of ElggUsers who requested membership
 * @uses $vars['invitations'] Array of ElggUsers who where invited
 */

elgg_load_js('group_tools.invite');

echo elgg_view('groups/membershiprequests/requests', $vars);
echo elgg_view('groups/membershiprequests/invitations', $vars);
echo elgg_view('groups/membershiprequests/email_invitations', $vars);

