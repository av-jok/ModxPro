<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    // @TODO возможно сюда стоит добавить помещение некоторых категорий в группу ресурсов ModStore

    $groups = [
        'Modstore' => [
            'TicketUserPolicy' => 9999,
        ]
    ];

    $contexts = [
        'web' => [
            'Administrator' => 0,
            'TicketVipPolicy' => 0,
        ],
        'en' => [
            'Administrator' => 0,
            'TicketVipPolicy' => 0,
        ],
    ];

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            if ($policy = $modx->getObject('modAccessPolicy', ['name' => 'miniShopManagerPolicy'])) {
                if ($template = $modx->getObject('modAccessPolicyTemplate',
                    ['name' => 'miniShopManagerPolicyTemplate'])
                ) {
                    $policy->set('template', $template->get('id'));
                    $policy->save();
                } else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR,
                        '[miniShop2] Could not find miniShopManagerPolicyTemplate Access Policy Template!');
                }


                /** @var modUserGroup $adminGroup */
                if ($adminGroup = $modx->getObject('modUserGroup', ['name' => 'Administrator'])) {
                    $properties = [
                        'target' => 'mgr',
                        'principal_class' => 'modUserGroup',
                        'principal' => $adminGroup->get('id'),
                        'authority' => 9999,
                        'policy' => $policy->get('id'),
                    ];
                    if (!$modx->getObject('modAccessContext', $properties)) {
                        $access = $modx->newObject('modAccessContext');
                        $access->fromArray($properties);
                        $access->save();
                    }
                }
                break;
            } else {
                $modx->log(xPDO::LOG_LEVEL_ERROR, '[miniShop2] Could not find miniShopManagerPolicy Access Policy!');
            }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            $success = true;
            break;
    }
}

return true;