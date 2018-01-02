<?php
/** @var $modx modX */
define('MODX_API_MODE', true);
$path = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
/** @noinspection PhpIncludeInspection */
require $path . '/index.php';

/** @var modX $modx */
$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('ECHO');
if (!XPDO_CLI_MODE) {
    echo '<pre>';
}

$c = $modx->newQuery('transport.modTransportPackage');
$c->select('package_name');
$c->groupby('package_name');
if ($c->prepare() && $c->stmt->execute()) {
    while ($package = $c->stmt->fetchColumn()) {
        $c2 = $modx->newQuery('transport.modTransportPackage', ['package_name' => $package]);
        $c2->where(['installed:!=' => '0000-00-00 00:00:00']);
        $c2->sortby('installed', 'desc');
        $c2->limit(1000, 1);
        $c2->select('signature');
        if ($c2->prepare() && $c2->stmt->execute()) {
            while ($signature = $c2->stmt->fetchColumn()) {
                $res = $modx->runProcessor('workspace/packages/version/remove', ['signature' => $signature]);
                if (!$res->isError()) {
                    echo $signature . " removed!\n";
                    ob_flush();
                } else {
                    $modx->log(modX::LOG_LEVEL_ERROR, "Could not remove {$signature}:" . $res->getMessage());
                }
            }
        }
    }
}
if (!XPDO_CLI_MODE) {
    echo '</pre>';
}