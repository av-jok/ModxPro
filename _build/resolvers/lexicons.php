<?php

/** @var xPDOTransport $transport */
/** @var array $options */
if ($transport->xpdo) {
    /** @var modX $modx */
    $modx =& $transport->xpdo;

    $lexicons = [
        'ru' => [
            'office' => [
                'auth' => [
                    'office_auth_login_username_desc' => 'Укажите логин или email, которые вы использовали при регистрации.',
                    'office_auth_register_username_desc' => 'Ваш логин, который будет использоваться для входа и ссылки на профиль',
                ],
                'profile' => [
                    'office_profile_username' => 'Логин',
                    'office_profile_city' => 'Город',
                    'office_profile_website' => 'Веб-сайт',
                    'office_profile_mobilephone' => 'Телефон',
                    'office_profile_github' => 'Github',
                    'office_profile_twitter' => 'Twitter',
                    'office_profile_vkontakte' => 'Вконтакте',
                    'office_profile_skype' => 'Skype',
                    'office_profile_telegram' => 'Телеграм',
                    'office_profile_work' => 'Беру работу на заказ',
                    'office_profile_feedback' => 'Я хочу получать письма от пользователей сайта',
                    'office_profile_usename' => 'Использовать мой username вместо id в адресе профиля',
                ]
            ],
        ],
        'en' => [
            'office' => [
                'auth' => [
                    'office_auth_login' => 'Login',
                    'office_auth_login_btn' => 'Submit',
                    'office_auth_login_username_desc' => 'Enter the login or email, that you used for registration',
                    'office_auth_register' => 'Register',
                    'office_auth_register_btn' => 'Register',
                    'office_auth_register_username_desc' => 'Your username, which will be used to login and link to the profile',
                ],
                'profile' => [
                    'office_profile_username' => 'Login',
                    'office_profile_city' => 'City',
                    'office_profile_website' => 'Website',
                    'office_profile_mobilephone' => 'Phone',
                    'office_profile_github' => 'Github',
                    'office_profile_twitter' => 'Twitter',
                    'office_profile_vkontakte' => 'Vkontakte',
                    'office_profile_skype' => 'Skype',
                    'office_profile_telegram' => 'Telegram',
                    'office_profile_work' => 'Available for work',
                    'office_profile_feedback' => 'I want to receive emails from users of the site',
                    'office_profile_usename' => 'Use my username instead of id in the profile address',
                ]
            ],
        ],
    ];
    foreach ($lexicons as $language => $strings) {
        foreach ($strings as $namespace => $topics) {
            foreach ($topics as $topic => $values) {
                foreach ($values as $name => $value) {
                    $key = [
                        'name' => $name,
                        'namespace' => $namespace,
                        'language' => $language,
                        'topic' => $topic,
                    ];
                    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
                        case xPDOTransport::ACTION_INSTALL:
                        case xPDOTransport::ACTION_UPGRADE:
                            if (!$entry = $modx->getObject('modLexiconEntry', $key)) {
                                $entry = $modx->newObject('modLexiconEntry', $key);
                            }
                            $entry->set('value', $value);
                            $entry->save();
                            break;
                        case xPDOTransport::ACTION_UNINSTALL:
                            if ($entry = $modx->getObject('modLexiconEntry', $key)) {
                                $entry->remove();
                            }
                            break;
                    }
                }
            }

        }
    }
}
return true;