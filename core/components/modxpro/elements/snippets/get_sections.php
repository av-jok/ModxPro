<?php

/** @var array $scriptProperties */
/** @var App $App */
$App = $modx->getService('App');
$pdoTools = $App->pdoTools;

$class = 'comSection';
$showUnpublished = $modx->getOption('showUnpublished', $scriptProperties, false);
$showDeleted = $modx->getOption('showDeleted', $scriptProperties, false);

$where = [
    'context_key' => $modx->context->key,
    'template' => 3,
];

if (!$showUnpublished) {
    $where['published'] = true;
}
if (!$showDeleted) {
    $where['deleted'] = false;
}

if (!empty($scriptProperties['where'])) {
    $where = array_merge($scriptProperties['where'], $where);
    unset($scriptProperties['where']);
}
$config = [
    'class' => $class,
    'where' => $where,
    'limit' => 0,
    'innerJoin' => [
        'Total' => ['class' => 'comTotal', 'on' => 'Total.id = comSection.id AND Total.class = "comSection"'],
    ],
    'select' => [
        'comSection' => $modx->getSelectColumns('comSection', 'comSection', '', ['pagetitle', 'uri', 'description']),
        'Total' => $modx->getSelectColumns('comTotal', 'Total', '', ['comments', 'views', 'topics']),
    ],
    'sortby' => 'Total.views',
    'sortdir' => 'desc',
    'tpl' => '@FILE chunks/sections/row.tpl',
    'additionalPlaceholders' => ['scriptProperties' => $scriptProperties],
    //'return' => 'sql',
];

$pdoTools = new pdoFetch($modx, array_merge($config, $scriptProperties));

$result = $pdoTools->run();
if (!empty($showLog)) {
    $result .= '<pre>'.$pdoTools->getTime().'</pre>';
}

return $result;

