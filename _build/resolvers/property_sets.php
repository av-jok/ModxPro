<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $sets = [
                'Jevix' => [
                    'Typography' => [
                        'name' => 'Typography',
                        'properties' => [
                            'cfgAllowTags' => [
                                'name' => 'cfgAllowTags',
                                'value' => 'kbd,a,p,div,img,i,b,u,em,strong,li,ol,ul,sup,abbr,pre,acronym,h3,h4,h5,h6,br,code,s,blockquote,table,th,tbody,tr,td,video,small',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ],
                            'cfgAllowTagParams' => [
                                'name' => 'cfgAllowTagParams',
                                'value' => '{"p":{"0":"class"},"ul":{"0":"class"},"table":{"0":"class"},"div":{"0":"class"},"a":["title","href","rel"],"img":{"0":"src","alt":"#text","1":"title","2":"class","align":["right","left","center"],"width":"#int","height":"#int"}}',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ],
                            'cfgSetTagParamDefault' => [
                                'name' => 'cfgSetTagParamDefault',
                                'value' => '',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ],
                            'escapeTags' => [
                                'name' => 'escapeTags',
                                'value' => true,
                                'type' => 'combo-boolean',
                                'lexicon' => 'jevix:properties',
                            ],
                            'cfgSetAutoPregReplace' => [
                                'name' => 'cfgSetAutoPregReplace',
                                'value' => '[["\/<video>(http|https):\\\\\/\\\\\/(?:www\\\\.|)youtube\\\\.com\\\\\/watch\\\\?v=([a-zA-Z0-9_\\\\-]+)(&.+)?<\\\\\/video>\/Ui","\/<video>(http|https):\\\\\/\\\\\/(?:www\\\\.|)youtu\\\\.be\\\\\/([a-zA-Z0-9_\\\\-]+)(&.+)?<\\\\\/video>\/Ui"],["<div class=\"embed-responsive embed-responsive-16by9\"><iframe src=\"https:\/\/www.youtube.com\/embed\/$2\" allowfullscreen><\/iframe><\/div>","<div class=\"embed-responsive embed-responsive-16by9\"><iframe src=\"https:\/\/www.youtube.com\/embed\/$2\" allowfullscreen><\/iframe><\/div>"]]',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ],
                            'cfgSetAutoReplace' => array(
                                'name' => 'cfgSetAutoReplace',
                                'value' => '[["+/-","(c)","(с)","(r)","(C)","(С)","(R)","<code","code>"],["±","©","©","®","©","©","®","<pre class=\\"prettyprint\\"","pre>"]]',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ),
                            'cfgSetTagChilds' => array(
                                'name' => 'cfgSetTagChilds',
                                'value' => '[["ul",["li"],false,true],["ol",["li"],false,true],["table",["tr"],false,true],["tr",["td","th"],false,true],["div",["table"],false,true]]',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ),
                            'cfgSetTagNoTypography' => [
                                'name' => 'cfgSetTagNoTypography',
                                'value' => 'pre,code,kbd,video',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ],
                            'cfgSetTagPreformatted' => [
                                'name' => 'cfgSetTagPreformatted',
                                'value' => 'pre,code,kbd,video',
                                'type' => 'textfield',
                                'lexicon' => 'jevix:properties',
                            ],
                        ],
                    ],
                ],
                'OfficeAuth' => [
                    'Auth' => [
                        'name' => 'Auth',
                        'properties' => [
                            'groups' => [
                                'name' => 'groups',
                                'value' => 'Users',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],
                            'addContexts' => [
                                'name' => 'addContexts',
                                'value' => 'web,en,id',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],
                            'providerTpl' => [
                                'name' => 'providerTpl',
                                'value' => '@FILE chunks/office/profile/provider.tpl',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],
                            'tplActivate' => [
                                'name' => 'tplActivate',
                                'value' => '@FILE chunks/email/office/reset.tpl',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],
                            'tplRegister' => [
                                'name' => 'tplRegister',
                                'value' => '@FILE chunks/email/office/register.tpl',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],

                        ],
                    ],
                ],
                'OfficeProfile' => [
                    'Profile' => [
                        'name' => 'Profile',
                        'properties' => [
                            'providerTpl' => [
                                'name' => 'providerTpl',
                                'value' => '@FILE chunks/office/profile/provider.tpl',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],
                            'activeProviderTpl' => [
                                'name' => 'activeProviderTpl',
                                'value' => '@FILE chunks/office/profile/provider-active.tpl',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],
                            'tplActivate' => [
                                'name' => 'tplActivate',
                                'value' => '@FILE chunks/email/office/reset.tpl',
                                'type' => 'textfield',
                                'lexicon' => 'office:properties',
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($sets as $snippet_name => $params) {
                /** @var modSnippet $snippet */
                if ($snippet = $modx->getObject('modSnippet', array('name' => $snippet_name))) {
                    foreach ($params as $set_name => $set_params) {
                        if (!$set = $modx->getObject('modPropertySet', array('name' => $set_name))) {
                            $set = $modx->newObject('modPropertySet');
                        }
                        $set->fromArray($set_params);
                        if ($set->save() && $snippet->addPropertySet($set)) {
                            $modx->log(xPDO::LOG_LEVEL_INFO,
                                "[Extras] Property set \"{$set_name}\" for snippet <b>\"{$snippet_name}\"</b> was created or updated"
                            );
                        } else {
                            $modx->log(xPDO::LOG_LEVEL_ERROR,
                                "[Extras] Could not create property set \"{$set_name}\" for snippet <b>\"{$snippet_name}\"</b>"
                            );
                        }
                    }
                }
            }
            break;
        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}

return true;