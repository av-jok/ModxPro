<?php
/** @var $modx modX */
/** @var $pdo PDO */
require '_initialize.php';

$c = $modx->newQuery('Ticket', ['class_key' => 'Ticket', 'published' => 1]);
$c->innerJoin('TicketThread', 'Thread', 'Thread.resource = Ticket.id');
$c->leftJoin('modTemplateVarResource', 'TV', 'TV.contentid = Ticket.id AND TV.tmplvarid = 3');
$c->leftJoin('msProduct', 'Product', 'Product.id = TV.value');
$c->select('Ticket.pagetitle as title, Ticket.content, Product.pagetitle as package_id');
$c->select($modx->getSelectColumns('TicketThread', 'Thread', '', ['title', 'content', 'access_id', 'package_id'], true));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        switch ($row['package_id']) {
            case 'msQuickView':
                $row['package_id'] = 'QuickView';
                break;
            case 'Главная':
                $row['package_id'] = '';
        }
        if (!empty($row['package_id'])) {
            if ($package = $modx->getObject('extraPackage', ['name' => $row['package_id']])) {
                $row['package_id'] = $package->id;
            } else {
                exit($row['package_id'] . "\n");
            }
        } else {
            $row['package_id'] = 0;
        }
        $row['closed'] = 1;
        $row['name'] = 'support-' . $row['id'];
        /** @var TicketThread $item */
        if (!$item = $modx->getObject('TicketThread', $row['id'])) {
            $item = $modx->newObject('TicketThread');
        }
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}

$c = $modx->newQuery('TicketComment');
$c->select($modx->getSelectColumns('TicketComment', 'TicketComment'));
$c->prepare();
if ($stmt = $pdo->prepare($c->toSQL())) {
    if (!$stmt->execute()) {
        print_r($stmt->errorInfo());
        exit;
    }
    $missing = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (isset($missing[$row['thread']]) || !$modx->getCount('TicketThread', $row['thread'])) {
            $missing[$row['thread']] = true;
            continue;
        }
        /** @var TicketComment $item */
        if (!$item = $modx->getObject('TicketComment', $row['id'])) {
            $item = $modx->newObject('TicketComment');
        }
        $item->fromArray($row, '', true, true);
        $item->save();
    }
}