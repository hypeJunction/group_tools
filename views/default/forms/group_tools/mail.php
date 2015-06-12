<?php
elgg_load_js('group_tools.mail');
$group = elgg_extract('entity', $vars);
$members = elgg_extract('members', $vars);
$friendpicker_value = array();
if (is_array($members)) {
	foreach ($members as $member) {
		$friendpicker_value[] = $member->guid;
	}
}
?>

<div class="gt-form-field">
	<label>
		<?php echo elgg_echo('group_tools:mail:form:recipients') ?>
		<span data-counter="gt-mail-recipients-count"><?php echo count($members) ?></span>
		<?php
		echo elgg_view('output/url', array(
			'href' => 'javascript:void(0);',
			'text' => elgg_view_icon('pencil'),
			'title' => elgg_echo('group_tools:mail:form:members:selection'),
			'id' => 'gt-mail-recipients-edit',
		));
		?>
	</label>
</div>
<div id="gt-mail-recipients-list" class="hidden">
	<div class="gt-form-field">
		<label>
			<?php
			echo elgg_view('input/checkbox', array(
				'checked' => true,
				'id' => 'gt-mail-reicipients-toggle',
			));
			echo elgg_echo('group_tools:mail:form:members:toggle');
			?>
		</label>
	</div>
	<div class="gt-form-field">
		<label><?php echo elgg_echo('group_tools:mail:form:members') ?></label>
		<div id="gt-mail-recipients-friendspicker">
			<?php
			echo elgg_view('input/friendspicker', array(
				'entities' => $members,
				'value' => $friendpicker_value,
				'highlight' => 'all',
				'name' => 'user_guids'
			));
			?>
		</div>
	</div>
</div>
<div class="gt-form-field">
	<label><?php echo elgg_echo('group_tools:mail:form:title') ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'title'
	));
	?>
</div>
<div class="gt-form-field">
	<label><?php echo elgg_echo('group_tools:mail:form:description') ?></label>
	<?php
	echo elgg_view('input/longtext', array(
		'name' => 'description'
	));
	?>
</div>
<div class="gt-form-field">
	<?php
	$methods = array();
	$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethods();
	foreach ($NOTIFICATION_HANDLERS as $method => $dummy) {
		$methods[elgg_echo("notification:method:$method")] = $method;
	}
	?>
	<label><?php echo elgg_echo('group_tools:mail:form:method') ?></label>
	<span class="elgg-text-help"><?php echo elgg_echo('group_tools:mail:form:method:help') ?></span>
	<?php
	echo elgg_view('input/checkboxes', array(
		'name' => 'method',
		'default' => '', // use default nofication method
		'value' => $methods,
		'options' => $methods,
	));
	?>
</div>
<div class="gt-form-field elgg-foot">
	<?php
	echo elgg_view('input/hidden', array(
		'name' => 'group_guid',
		'value' => $group->guid
	));
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('send')
	));
	?>
</div>
