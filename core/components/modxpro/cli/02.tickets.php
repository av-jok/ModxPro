<?php
/** @var $modx modX */
/** @var $pdo PDO */
require '_initialize.php';

$statuses = [
    'Новый' => 1,
    'Решено' => 2,
    'Готово' => 2,
    'Работа выполнена' => 2,
    'В поиске' => 3,
    'В работе' => 3,
    'Обсуждение' => 3,
    'Исполнитель найден' => 3,
    'Подготовка завершена' => 3,
    'Никто не помогает!' => 4,
    'Отменено' => 4,
];

$sections = [];
$c = $modx->newQuery('modResource');
$c->select(['id', 'alias', 'context_key']);
if ($c->prepare() && $c->stmt->execute()) {
    while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
        $sections[$row['context_key']][$row['alias']] = $row['id'];
    }
}


//$modx->prepare("TRUNCATE {$modx->getTableName('comSection')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('comTopic')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('comTotal')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('comThread')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('comComment')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('comStar')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('comView')};")->execute();

// Tickets
$c = $modx->newQuery('modResource', ['class_key' => 'Ticket']);
$c->innerJoin('modResource', 'Parent');
$c->leftJoin('modTemplateVarResource', 'Status', 'Status.contentid = modResource.id AND Status.tmplvarid = 6');
$c->select($modx->getSelectColumns('modResource', 'modResource', '', ['id', 'pagetitle', 'introtext', 'content', 'createdon', 'createdby', 'published', 'publishedon', 'publishedby', 'deleted', 'editedby', 'editedon', 'deletedon', 'deletedby', 'context_key']));
$c->select('Parent.alias as parent, Status.value as status, modResource.show_on_start, modResource.hide_on_start');
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!isset($sections[$row['context_key']][$row['parent']])) {
            exit(print_r($row));
        }
        $row['status'] = isset($statuses[$row['status']])
            ? $statuses[$row['status']]
            : 0;
        $row['parent'] = $sections[$row['context_key']][$row['parent']];
        $row['pagetitle'] = trim($row['pagetitle']);
        $row['introtext'] = trim($row['introtext']);
        $row['content'] = trim($row['content']);
        if ($row['hide_on_start']) {
            $row['important'] = -1;
        } elseif ($row['show_on_start']) {
            $row['important'] = 1;
        }

        /** @var comTopic $item */
        $item = $modx->newObject('comTopic');
        $item->fromArray($row, '', true);
        $item->save();
    }
}

// Copy images
shell_exec('rm -rf ~/www/assets/images/tickets');
shell_exec('scp -r s264@h1.modhost.pro:/home/s264/www/assets/images/tickets ~/www/assets/images/');

// Threads
$c = $modx->newQuery('TicketThread');
$c->select($modx->getSelectColumns('TicketThread', 'TicketThread'));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['key'] = 'topic-' . $row['resource'];
        $row['topic'] = $row['resource'];
        /** @var comThread $item */
        $item = $modx->newObject('comThread');
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// Comments
$c = $modx->newQuery('TicketComment');
$c->select($modx->getSelectColumns('TicketComment', 'TicketComment', '', ['published'], true));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    $missing = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        /** @var comComment $item */
        $item = $modx->newObject('comComment');
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// Totals
$c = $modx->newQuery('TicketTotal');
$c->select($modx->getSelectColumns('TicketTotal', 'TicketTotal'));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!$row['id']) {
            continue;
        }
        $row['topics'] = $row['tickets'];
        if ($row['class'] == 'TicketsSection') {
            $row['class'] = 'comSection';
            $c2 = $modx->newQuery('modResource', ['class_key' => 'TicketsSection', 'id' => $row['id']]);
            $c2->select('alias,context_key');
            $c2->prepare();
            if ($stmt2 = $pdo->prepare($c2->toSQL())) {
                if (!$stmt2->execute()) {
                    print_r($stmt2->errorInfo());
                    exit;
                }
                if ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    if (isset($sections[$row2['context_key']][$row2['alias']])) {
                        $row['id'] = $sections[$row2['context_key']][$row2['alias']];
                    } else {
                        exit("Could`t find section {$row2['context_key']}-{$row2['alias']}");
                    }
                }
            }
        } elseif ($row['class'] == 'Ticket') {
            $row['class'] = 'comTopic';
        } elseif ($row['class'] == 'TicketComment') {
            $row['class'] = 'comComment';
        }

        /** @var comTotal $item */
        $item = $modx->newObject('comTotal');
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// Stars
$c = $modx->newQuery('TicketStar');
$c->select($modx->getSelectColumns('TicketStar', 'TicketStar'));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['class'] == 'Ticket') {
            $row['class'] = 'comTopic';
        } elseif ($row['class'] == 'TicketComment') {
            $row['class'] = 'comComment';
        }

        /** @var comStar $item */
        $item = $modx->newObject('comStar');
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

// Views
$limit = 100000;
$offset = 0;
while (true) {
    $c = $modx->newQuery('TicketView', ['guest_key' => '']);
    $c->select('`parent`,`uid`,`timestamp`');
    $c->limit($limit, $offset);
    $c->prepare();
    if ($stmt = $pdo->prepare($c->toSQL())) {
        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());
            exit;
        }
        $q = $modx->prepare("INSERT INTO {$modx->getTableName('comView')} (`topic_id`,`user_id`,`timestamp`) VALUES (:parent,:uid,:timestamp);");
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $q->execute($row);
            $i++;
        }
        if (!$row && !$i) {
            break;
        }
    }
    $offset += $limit;
}
unset($offset, $limit, $i);