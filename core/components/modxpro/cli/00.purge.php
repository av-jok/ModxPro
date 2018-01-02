<?php
/** @var $modx modX */
/** @var $pdo PDO */
require '_initialize.php';
$dir = dirname(__FILE__) . '/';
$base = MODX_BASE_PATH;
$assets = MODX_ASSETS_PATH;

//$modx->prepare("TRUNCATE {$modx->getTableName('modUser')};")->execute();
//$modx->prepare("TRUNCATE {$modx->getTableName('modUserProfile')};")->execute();
//$modx->prepare("TRUNCATE {$modx->getTableName('haUserService')};")->execute();

$modx->prepare("TRUNCATE {$modx->getTableName('modResource')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('modResourceGroupResource')};")->execute();

$modx->prepare("TRUNCATE {$modx->getTableName('TicketThread')};")->execute();
$modx->prepare("TRUNCATE {$modx->getTableName('TicketComment')};")->execute();

echo shell_exec("php {$base}Extras/ModxPro/_build/build.php");

echo "Users\n";
ob_flush();
echo shell_exec("php {$dir}01.users.php");

echo "Tickets\n";
ob_flush();
echo shell_exec("php {$dir}02.tickets.php");