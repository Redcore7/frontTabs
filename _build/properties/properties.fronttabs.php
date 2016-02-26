<?php

$properties = array();

$tmp = array(
	'tplWrapper' => array(
		'type' => 'textfield',
		'value' => 'frontTabs.wrapper',
	),
	'tplHeader' => array(
		'type' => 'textfield',
		'value' => 'frontTabs.header',
	),
	'tpl' => array(
		'type' => 'textfield',
		'value' => 'frontTabs.item',
	),
	'toPlaceholder' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'activeClass' => array(
		'type' => 'textfield',
		'value' => 'active',
	),
	'rememberTab' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
	'resource' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'category' => array(
		'type' => 'textfield',
		'value' => 'frontTabs',
	),
	'sortby' => array(
		'type' => 'textfield',
		'value' => 'rank',
	),
	'sortdir' => array(
		'type' => 'textfield',
		'value' => 'ASC',
	),
);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;