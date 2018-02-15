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
$modx->prepare("TRUNCATE {$modx->getTableName('comAuthor')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('modUserProfile')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('modUserGroupMember')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('haUserService')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('appUserName')};")->execute();

// Users
$c = $modx->newQuery('modUser'/*, ['active' => 1]*/);
$c->innerJoin('TicketAuthor', 'AuthorProfile', 'modUser.id = AuthorProfile.id');
$c->select($modx->getSelectColumns('modUser', 'modUser'));
$c->select($modx->getSelectColumns('TicketAuthor', 'AuthorProfile'));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $composite = $modx->newObject('comAuthor');
        $row['topics'] = $row['tickets'];
        $row['stars_topics'] = $row['stars_tickets'];
        $row['votes_topics'] = $row['votes_tickets'];
        $row['votes_topics_up'] = $row['votes_tickets_up'];
        $row['votes_topics_down'] = $row['votes_tickets_down'];
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
            unset($extended['work'], $extended['feedback'], $extended['username'], $extended['lang'], $extended['registered'], $extended['lastactivity']);
            $row['extended'] = $extended;
        }
        //$row['photo'] = '';
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// Copy avatars
shell_exec('rm -rf ~/www/assets/images/users');
shell_exec('scp -r s264@h1.modhost.pro:/home/s264/www/assets/images/users ~/www/assets/images/');

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
if ($stmt = $pdo->prepare("SELECT user_id, username, createdon FROM {$modx->config['table_prefix']}user_names")) {
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