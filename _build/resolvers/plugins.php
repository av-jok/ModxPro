<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            if ($plugin = $modx->getObject('modPlugin', ['name' => 'Tickets', 'disabled' => false])) {
                $plugin->set('disabled', true);
                $plugin->save();
            }
            break;
    }
}

return true;