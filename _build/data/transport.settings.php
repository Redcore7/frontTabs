<?php

$settings = array();

$tmp = array(
	'frontend_js' => array(
		'xtype' => 'textfield',
		'value' => '[[+jsUrl]]web/default.js',
		'area' => '',
	),
	'frontend_css' => array(
		'xtype' => 'textfield',
		'value' => '[[+cssUrl]]web/default.css',
		'area' => '',
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'ft_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
