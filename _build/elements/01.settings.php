<?php

return [
    'friendly_urls' => [
        'key' => 'friendly_urls',
        'xtype' => 'combo-boolean',
        'value' => true,
        'area' => 'furls',
        'namespace' => 'core',
    ],
    'site_name' => [
        'key' => 'site_name',
        'xtype' => 'textfield',
        'value' => 'modx.pro',
        'area' => 'site',
        'namespace' => 'core',
    ],
    'link_tag_scheme' => [
        'key' => 'link_tag_scheme',
        'xtype' => 'textfield',
        'value' => 'abs',
        'area' => 'site',
        'namespace' => 'core',
    ],
    'emailsender' => [
        'key' => 'emailsender',
        'xtype' => 'textfield',
        'value' => 'no_reply@modx.pro',
        'area' => 'authentication',
        'namespace' => 'core',
    ],

    'pdotools_elements_path' => [
        'key' => 'pdotools_elements_path',
        'xtype' => 'textfield',
        'value' => '{core_path}components/modxpro/elements/',
        'area' => 'pdotools_main',
        'namespace' => 'pdotools',
    ],
    'ap_frontend_js' => [
        'key' => 'ap_frontend_js',
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'ap_style',
        'namespace' => 'adminpanel',
    ],
    'mse2_filters_handler_class' => [
        'key' => 'mse2_filters_handler_class',
        'xtype' => 'textfield',
        'value' => 'comFilterHandler',
        'area' => 'mse2_main',
        'namespace' => 'msearch2',
    ],
];