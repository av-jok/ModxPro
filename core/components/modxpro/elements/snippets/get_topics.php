<?php

/** @var array $scriptProperties */
/** @var App $App */
$App = $modx->getService('App');

$class = 'comTopic';
$excludeSections = $modx->getOption('excludeSections', $scriptProperties, []);
$includeSections = $modx->getOption('includeSections', $scriptProperties, []);
$showUnpublished = $modx->getOption('showUnpublished', $scriptProperties, false);
$showDeleted = $modx->getOption('showDeleted', $scriptProperties, false);
$select = [
    'Section' => $modx->getSelectColumns('modResource', 'Section', 'section_', ['pagetitle', 'context_key', 'uri']),
    'comTopic' => $modx->getSelectColumns('comTopic', 'comTopic', '', ['id', 'pagetitle', 'introtext', 'createdby', 'publishedon']),
    'Total' => $modx->getSelectColumns('comTotal', 'Total', '', ['comments', 'views', 'stars', 'rating', 'rating_plus', 'rating_minus']),
    'Author' => $modx->getSelectColumns('modUser', 'Author', '', ['username']),
    'AuthorProfile' => $modx->getSelectColumns('modUserProfile', 'AuthorProfile', '', ['photo', 'email', 'fullname', 'usename']),
];

$where = [
    'Section.context_key' => $modx->context->key,
];

if (!empty($excludeSections)) {
    $c = $modx->newQuery('comSection', ['context_key' => $modx->context->key]);
    $c->select('id');
    if (is_array($excludeSections)) {
        $c->where(['alias:IN' => $excludeSections]);
    } else {
        $c->where(['alias' => $excludeSections]);
    }
    if ($c->prepare() && $c->stmt->execute()) {
        $where['parent:NOT IN'] = $c->stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
if (!empty($includeSections)) {
    $c = $modx->newQuery('comSection', ['context_key' => $modx->context->key]);
    $c->select('id');
    if (is_array($includeSections)) {
        $c->where(['alias:IN' => $includeSections]);
    } else {
        $c->where(['alias' => $includeSections]);
    }
    if ($c->prepare() && $c->stmt->execute()) {
        $where['parent:IN'] = $c->stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

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
    'select' => $select,
    'where' => $where,
    'innerJoin' => [
        'Section' => ['class' => 'modResource'],
        'Author' => ['class' => 'modUser'],
        'AuthorProfile' => ['class' => 'modUserProfile'],
        'Total' => ['class' => 'comTotal', 'on' => 'Total.id = comTopic.id AND Total.class = "comTopic"'],
    ],
    'sortby' => 'publishedon',
    'sortdir' => 'desc',
    'tpl' => '@FILE chunks/topics/row.tpl',
    'additionalPlaceholders' => ['scriptProperties' => $scriptProperties],
    //'return' => 'sql',
];

$pdoTools = new pdoFetch($modx, array_merge($config, $scriptProperties));

$result = $pdoTools->run();
if (!empty($showLog)) {
    $result .= '<pre>'.$pdoTools->getTime().'</pre>';
}

return $result;