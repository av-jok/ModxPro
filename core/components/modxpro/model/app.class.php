<?php

class App
{
    /** @var modX $modx */
    public $modx;
    /** @var pdoFetch $pdoTools */
    public $pdoTools;
    public $config = [];

    const assets_version = '1.11-dev';


    /**
     * @param modX $this ->modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx = $modx;
        $this->pdoTools = $modx->getService('pdoFetch');
        $corePath = MODX_CORE_PATH . 'components/modxpro/';
        $assetsUrl = MODX_ASSETS_URL . 'components/modxpro/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);
        $this->initialize();
    }


    /**
     * Initialize App
     */
    public function initialize()
    {
        $this->pdoTools = $this->modx->getService('pdoFetch');
        if (!isset($_SESSION['csrf-token'])) {
            $_SESSION['csrf-token'] = bin2hex(openssl_random_pseudo_bytes(16));
        }

        $this->updateModel();

        $this->modx->addPackage('modxpro', $this->config['modelPath']);
        /** @noinspection PhpIncludeInspection */
        require_once $this->config['corePath'] . 'vendor/autoload.php';

        $this->modx->getService('mail', 'AppMail', $this->config['modelPath']);
        $this->modx->lexicon->load('modxpro:default');
        $this->modx->lexicon->load('ru:modxpro:frontend');
        $this->modx->lexicon->load('en:modxpro:frontend');
    }


    /**
     * @param $action
     * @param array $data
     *
     * @return array|bool|mixed
     */
    public function runProcessor($action, array $data = [])
    {
        $action = 'web/' . trim($action, '/');
        /** @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($action, $data, ['processors_path' => $this->config['processorsPath']]);
        if ($response) {
            $data = $response->getResponse();
            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            return $data;
        }

        return false;
    }


    /**
     * @param modSystemEvent $event
     * @param array $scriptProperties
     */
    public function handleEvent(modSystemEvent $event, array $scriptProperties)
    {
        extract($scriptProperties);
        switch ($event->name) {
            case 'pdoToolsOnFenomInit':
                $modx = $this->modx;
                /** @var Fenom|FenomX $fenom */
                $fenom->addAllowedFunctions([
                    'array_keys',
                    'array_values',
                ]);

                $fenom->addAccessorSmart('App', 'App', Fenom::ACCESSOR_PROPERTY);
                $fenom->App = $this;

                $fenom->addAccessorSmart('en', 'en', Fenom::ACCESSOR_PROPERTY);
                $fenom->en = $this->modx->getOption('cultureKey') == 'en';

                $fenom->addAccessorSmart('assets_version', 'assets_version', Fenom::ACCESSOR_PROPERTY);
                $fenom->assets_version = $this::assets_version;

                $fenom->addAccessorSmart('switch_link', 'switch_link', Fenom::ACCESSOR_PROPERTY);
                if ($this->modx->context->key == 'en') {
                    $fenom->switch_link = '//' . preg_replace('#^en\.#', '', $_SERVER['HTTP_HOST']) .
                        preg_replace('#\?.*#', '', $_SERVER['REQUEST_URI']);
                } elseif ($this->modx->context->key == 'id') {
                    $lang = $this->modx->getOption('cultureKey') == 'en' ? 'ru' : 'en';
                    $fenom->switch_link = strpos($_SERVER['REQUEST_URI'], '?') !== false
                        ? $_SERVER['REQUEST_URI'] . '&lang=' . $lang
                        : $_SERVER['REQUEST_URI'] . '?lang=' . $lang;
                } else {
                    $fenom->switch_link = '//en.' . $_SERVER['HTTP_HOST'];
                    if (!empty($this->modx->resource) && !empty($this->modx->resource->is_topic)) {
                        $fenom->switch_link .= preg_replace('#\/\d+(?:\?.*)?#', '', $_SERVER['REQUEST_URI']);
                    } else {
                        $fenom->switch_link .= preg_replace('#\?.*#', '', $_SERVER['REQUEST_URI']);
                    }
                }

                $fenom->addModifier('avatar', function ($data, $size = 48) use ($modx) {
                    if (is_numeric($data)) {
                        $data = [];
                        if ($user = $modx->getObject('modUserProfile', ['internalKey' => (int)$data])) {
                            $data = $user->get(['photo', 'email']);
                        }
                    }
                    $avatar = empty($data['photo'])
                        ? 'https://www.gravatar.com/avatar/' . md5(strtolower($data['email'])) . '?d=mm&s=' . $size
                        : $data['photo'];

                    return $avatar;
                });

                $fenom->addModifier('website', function ($input, $options = 'www.') {
                    if (!$url = parse_url($input)) {
                        return $input;
                    }
                    $output = $url['host'];
                    if (!empty($options)) {
                        $remove = array_map('trim', explode(',', $options));
                        $output = str_replace($remove, '', $output);
                    }

                    return strtolower($output);
                });

                $fenom->addModifier('prism', function ($input) {
                    /** @noinspection HtmlUnknownAttribute */
                    preg_match_all('#(?:<pre><code>|<pre class\="prettyprint">)(.*?)(?:</code></pre>|</pre>)#s', $input, $code);
                    foreach ($code[0] as $idx => $from) {
                        // html, css, javascript
                        $lang = 'markup';
                        $content = str_replace(
                            ['&#91;', '&#93;', '&#96;', '&#123;', '&#125;'],
                            ['[', ']', '``', '{', '}'],
                            htmlspecialchars_decode($from)
                        );
                        if (strpos($content, '<?') !== false || strpos($content, '->') !== false) {
                            $lang = 'php';
                        } elseif (preg_match('#=`\w+`#s', $content) || preg_match('#\[\[#s', $content)) {
                            //$lang = 'modx';
                        } elseif (preg_match('#\b(select|from|update|table|insert|into)\b#is', $input)) {
                            $lang = 'sql';
                        } elseif (preg_match('#\b(location|include|server)\b#s', $input)) {
                            $lang = 'nginx';
                        } elseif (preg_match('#\{(\$|\/|\w+(\s|\(|\|)|\(|\')#', $content)) {
                            $lang = 'smarty';
                        }
                        $input = str_replace(
                            $from,
                            '<pre><code class="language-' . $lang . '">' . trim($code[1][$idx]) . '</code></pre>',
                            $input
                        );
                    }

                    return $input;
                });
                break;

            case 'OnHandleRequest':
                if ($this->modx->context->key != 'mgr') {
                    /** @var AppRouter $router */
                    if ($router = $this->modx->getService('AppRouter', 'AppRouter', $this->config['modelPath'])) {
                        $router->process();
                    }
                }
                break;

            case 'OnLoadWebDocument':
                break;

            case 'OnUserFormSave':
                /** @var modUser $user */
                if (!$username = $this->modx->getObject('appUserName', ['username' => $user->username])) {
                    /** @var appUserName $username */
                    $username = $this->modx->newObject('appUserName');
                    $username->fromArray([
                        'username' => $user->username,
                        'user_id' => $user->id,
                    ], '', true, true);
                    $username->save();
                }
                /** @var string $mode */
                if ($mode == modSystemEvent::MODE_NEW) {
                    $user->Profile->set('usename', true);
                    $user->Profile->save();
                }
                break;

            case 'OnPageNotFound':
                break;

            case 'OnWebPagePrerender':
                // Compress output html for Google
                //$this->modx->resource->_output = preg_replace('#\s+#', ' ', $this->modx->resource->_output);
                break;
        }
    }


    /**
     * @param $to
     * @param $subject
     * @param string $body
     * @param array $properties
     *
     * @return bool
     */
    public function sendEmail($to, $subject, $body = '', array $properties = [])
    {
        if (is_numeric($to)) {
            /** @var modUserProfile $profile */
            if ($profile = $this->modx->getObject('modUserProfile', ['internalKey' => $to])) {
                $to = $profile->email;
            } else {
                return false;
            }
        }
        /** @var appMailQueue $queue */
        $queue = $this->modx->newObject('appMailQueue', [
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
                'properties' => $properties,
            ]
        );

        return $this->modx->getOption('app_mail_queue', null, false)
            ? $queue->save()
            : $queue->send();
    }


    /**
     *
     */
    protected function updateModel()
    {
        if ($this->modx->loadClass('modUser')) {
            // Remove Tickets connection
            unset($this->modx->map['modUser']['composites']['AuthorProfile']);
        }
        if ($this->modx->loadClass('modUserProfile')) {
            $this->modx->map['modUserProfile']['fields']['feedback'] =
            $this->modx->map['modUserProfile']['fields']['usename'] =
            $this->modx->map['modUserProfile']['fields']['work'] = false;
            $this->modx->map['modUserProfile']['fieldMeta']['feedback'] =
            $this->modx->map['modUserProfile']['fieldMeta']['usename'] =
            $this->modx->map['modUserProfile']['fieldMeta']['work'] = [
                'dbtype' => 'tinyint',
                'precision' => 1,
                'phptype' => 'bool',
                'null' => true,
                'default' => 0,
            ];
            $this->modx->map['modUserProfile']['indexes']['work'] = [
                'alias' => 'work',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => ['work' => ['length' => '', 'collation' => 'A', 'null' => false]],
            ];
        }
    }

}