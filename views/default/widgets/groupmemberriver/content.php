<?php 

// get variables we need
$widget = $vars["entity"];
$group = elgg_get_page_owner_entity();

$members = elgg_get_entities_from_relationship(array(
				'relationship' => 'member',
				'relationship_guid' => $group->guid,
				'inverse_relationship' => TRUE,
				'type' => 'user',
				'limit' => false,
				'callback' => false
));


// create a comma delimited list of member guids for sql
$member_guids = array();
foreach($members as $member){
	$member_guids[] = $member->guid;
}

$member_guids = array_unique($member_guids);
$member_guids = array_filter($member_guids);

$members_in = implode(',', $member_guids);


$db_prefix = elgg_get_config('dbprefix');


// set up options for elgg_list_river
$options = array(
	'pagination' => FALSE,
	'limit' => $widget->activity_count ? $widget->activity_count : 10,
	'types' => 'object',
	'subject_guids' => $member_guids,
);


switch ($widget->subset) {
	case "all":
		// nothing to do here, just basic options
		break;
	case "notgroup":
		$options['joins'] = array("JOIN {$db_prefix}entities AS e2 ON e2.guid = rv.object_guid");
		$options['wheres'] = array("e2.container_guid != {$group->guid}");
		break;
	case "justgroup":
	default:
		$options['joins'] = array("JOIN {$db_prefix}entities AS e2 ON e2.guid = rv.object_guid");
		$options['wheres'] = array("e2.container_guid = {$group->guid}");
		break;
}


// display results
if ($widget->viewtype == 'condensed') {
	$path = elgg_get_view_location('river/elements/body');
	elgg_set_view_location('river/elements/body', dirname(__FILE__) . '/');
	echo elgg_list_river($options);
	elgg_set_view_location('river/elements/body', $path);
}
else {
	echo elgg_list_river($options);
}

