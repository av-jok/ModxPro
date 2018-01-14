<?php
/** @var $modx modX */
/** @var $pdo PDO */
require '_initialize.php';
$groups = [
    'Administrator' => 1,
    'Users' => 2,
    'Modstore' => 3,
];

// Clear tables
$modx->prepare("TRUNCATE {$modx->getTableName('modUser')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('TicketAuthor')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('modUserProfile')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('modUserGroupMember')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('haUserService')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('appUserName')};")->execute();

// Users
$c = $modx->newQuery('modUser'/*, ['active' => 1]*/);
$c->innerJoin('TicketAuthor', 'AuthorProfile');
$c->select($modx->getSelectColumns('modUser', 'modUser'));
$c->select($modx->getSelectColumns('TicketAuthor', 'AuthorProfile'));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $composite = $modx->newObject('TicketAuthor');
        $composite->fromArray($row, '', true, true);
        $composite->save();

        $item = $modx->newObject('modUser');
        $item->fromArray($row, '', true, true);
        $item->set('createdon', $row['createdon'] == '0000-00-00 00:00:00' ? 0 : strtotime($row['createdon']));
        $item->save();
    }
}

// Profiles
$c = $modx->newQuery('modUserProfile');
$c->innerJoin('modUser', 'User');
//$c->where(['User.active' => 1]);
$c->select($modx->getSelectColumns('modUserProfile', 'modUserProfile', '', ['work', 'usename', 'feedback'], true));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item = $modx->newObject('modUserProfile');
        if ($extended = json_decode($row['extended'], true)) {
            $row['work'] = !empty($extended['work']);
            $row['feedback'] = !empty($extended['feedback']);
            $row['usename'] = !empty($extended['username']);
            unset($extended['work'], $extended['feedback'], $extended['username']);
            $row['extended'] = $extended;
        }
        /*
        if ($extended = json_decode($row['extended'], true)) {
            foreach (['facebook', 'twitter', 'vkontakte'] as $k) {
                if (!empty($extended[$k])) {
                    if ($path = parse_url($extended[$k], PHP_URL_PATH)) {
                        $extended[$k] = trim($path, '/');
                    }
                }
            }
            $row['extended'] = json_encode($extended);
        }*/
        //$row['photo'] = '';
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// Groups
$c = $modx->newQuery('modUserGroupMember');
$c->innerJoin('modUser', 'User');
$c->innerJoin('modUserGroup', 'UserGroup');
//$c->where(['User.active' => 1]);
$c->select($modx->getSelectColumns('modUserGroupMember', 'modUserGroupMember'));
$c->select($modx->getSelectColumns('modUserGroup', 'UserGroup', '', ['name']));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!isset($groups[$row['name']])) {
            continue;
        }
        $row['user_group'] = $groups[$row['name']];

        $item = $modx->newObject('modUserGroupMember');
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// HybridAuth
$c = $modx->newQuery('haUserService');
$c->select($modx->getSelectColumns('haUserService', 'haUserService'));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item = $modx->newObject('haUserService');
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// Usernames
if ($stmt = $pdo->prepare("SELECT user_id as userid, username, createdon FROM {$modx->config['table_prefix']}user_names")) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item = $modx->newObject('appUserName');
        $row['username'] = strtolower($row['username']);
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}