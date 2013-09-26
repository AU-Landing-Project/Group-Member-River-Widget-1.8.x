<?php


function groupmemberriver_init() {
	elgg_register_widget_type('groupmemberriver', elgg_echo('groupmemberriver:widget:title'), elgg_echo('groupmemberriver:widget:description'), "groups", TRUE);
}

register_elgg_event_handler('init', 'system', 'groupmemberriver_init');
