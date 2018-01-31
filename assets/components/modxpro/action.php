<?php

function failure($message = '', array $data = [])
{
    $response = [
        'success' => false,
        'message' => $message,
        'data' => $data,
    ];
    @session_write_close();
    http_response_code(422);

    exit(json_encode($response));
}

if (empty($_REQUEST['action'])) {
    failure('Access denied');
}

/** @var modX $modx */
define('MODX_API_MODE', true);
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
$modx->getService('error', 'error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->reset();

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    if ($modx->user->id && (empty($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] != $_SESSION['csrf-token'])) {
        failure($modx->lexicon('csrf_error'), ['reload' => true]);
    }
}

/** @var modContext $ctx */
if (!empty($_SERVER['HTTP_X_PAGE_CONTEXT']) && $ctx = $modx->getObject('modContext', ['key' => $_SERVER['HTTP_X_PAGE_CONTEXT']])) {
    if ($ctx->key != $modx->context->key) {
        $modx->switchContext($ctx->key);
        $modx->user = null;
        $modx->getUser($ctx->key);
    }
    if ($ctx->key == 'id' && !empty($_SESSION['lang']) && $_SESSION['lang'] != $modx->getOption('cultureKey')) {
        $modx->setOption('cultureKey', $_SESSION['lang']);
    }
}

/** @var App $App */
if ($App = $modx->getService('app', 'App', MODX_CORE_PATH . 'components/modxpro/model/')) {
    $action = str_replace(' ', '', $_REQUEST['action']);
    unset($_REQUEST['action']);
    if ($response = $App->runProcessor($action, $_REQUEST)) {
        if (empty($response['success'])) {
            http_response_code(422);
        }
        @session_write_close();
        exit(json_encode($response));
    }
}

failure('Unknown error');