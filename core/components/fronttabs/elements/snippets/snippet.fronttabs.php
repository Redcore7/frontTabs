<?php
/** @var array $scriptProperties */
/** @var frontTabs $frontTabs */
if (!$frontTabs = $modx->getService('fronttabs', 'frontTabs', $modx->getOption('fronttabs_core_path', null, $modx->getOption('core_path') . 'components/fronttabs/') . 'model/fronttabs/', $scriptProperties)) {
	return 'Could not load frontTabs class!';
}

$frontTabs->initialize($modx->context->key, $scriptProperties);

$resource = $modx->getOption('resource', $scriptProperties, '');
if (!$resource) {
	$resource = $modx->resource->get('id');
}

if (!$tabsCategory = $modx->getObject('modCategory', array('category' => $category))) {
	$modx->log(MODX::LOG_LEVEL_ERROR, 'Не найдена категория ' . $category);
	return false;
}

$header = $body = '';

$query = $modx->newQuery('modTemplateVar', array('category' => $tabsCategory->get('id')));
$query->sortby($sortby, $sortdir);

$tvs = $modx->getCollection('modTemplateVar', $query);
foreach ($tvs as $tv) {
	$tvc = $tv->getOne('TemplateVarResources', array('contentid' => $resource, 'tmplvarid' => $tv->get('id')));
	$fromData = $tv->toArray();
	if ($tvc) {
		$fromData['value'] = $tvc->get('value');
	} else {
		$fromData['value'] = '';
	}
	$header .= $modx->getChunk($tplHeader, $fromData);
	$individualTpl = 'tpl_' . $fromData['name'];
	if (isset($$individualTpl) && $$individualTpl) {
		$body .= $modx->getChunk($$individualTpl, $fromData);
	} else {
		$body .= $modx->getChunk($tpl, $fromData);
	}
}
$output = $modx->getChunk($tplWrapper, array('header' => $header, 'body' => $body));

if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
} else {
	return $output;
}