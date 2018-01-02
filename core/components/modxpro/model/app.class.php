<?php

class App
{
    /** @var modX $modx */
    public $modx;
    /** @var pdoFetch $pdoTools */
    public $pdoTools;
    public $config = [];

    const assets_version = '1.04-dev';


    /**
     * @param modX $modx
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

        //$this->modx->addPackage('modxpro', $this->config['modelPath']);
        /** @noinspection PhpIncludeInspection */
        //require_once $this->config['corePath'] . 'vendor/autoload.php';
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
                $fenom->addAccessorSmart('en', 'en', Fenom::ACCESSOR_PROPERTY);
                $fenom->en = $this->modx->getOption('cultureKey') == 'en';

                $fenom->addAccessorSmart('assets_version', 'assets_version', Fenom::ACCESSOR_PROPERTY);
                $fenom->assets_version = $this::assets_version;

                $fenom->addAccessorSmart('switch_link', 'switch_link', Fenom::ACCESSOR_PROPERTY);
                $fenom->switch_link = ($this->modx->context->key == 'en'
                        ? '//' . preg_replace('#^en\.#', '', @$_SERVER['HTTP_HOST'])
                        : '//en.' . @$_SERVER['HTTP_HOST']
                    ) . preg_replace('#\?.*#', '', @$_SERVER['REQUEST_URI']);

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
                break;

            case 'OnHandleRequest':
                if ($this->modx->context->key == 'mgr') {
                    return;
                }

                // Remove slash and question signs at the end of url
                $uri = $_SERVER['REQUEST_URI'];
                if ($uri != '/' && in_array(substr($uri, -1), ['/', '?'])) {
                    $this->modx->sendRedirect(rtrim($uri, '/?'), ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
                }

                // Remove .html extension
                if (preg_match('#\.html$#i', $uri)) {
                    $this->modx->sendRedirect(preg_replace('#\.html$#i', '', $uri),
                        ['responseCode' => 'HTTP/1.1 301 Moved Permanently']
                    );
                }

                if (strpos($_SERVER['HTTP_HOST'], 'en.') === 0) {
                    $this->modx->switchContext('en');
                }
                // Switch context - uncomment it if you have more than one context
                /*
                $c = $this->modx->newQuery('modContextSetting', [
                    'key' => 'http_host',
                    'value' => $_SERVER['HTTP_HOST'],
                ]);
                $c->select('context_key');
                $tstart = microtime(true);
                if ($c->prepare() && $c->stmt->execute()) {
                    $this->modx->queryTime += microtime(true) - $tstart;
                    $this->modx->executedQueries++;
                    if ($context = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
                        if ($context != 'web') {
                            $this->modx->switchContext($context);
                        }
                    }
                }
                */
                break;
            case 'OnLoadWebDocument':
                break;
            case 'OnPageNotFound':
                break;
            case 'OnWebPagePrerender':
                // Compress output html for Google
                // $this->modx->resource->_output = preg_replace('#\s+#', ' ', $this->modx->resource->_output);
                break;
        }
    }


    /**
     *
     */
    protected function updateModel()
    {
        if ($this->modx->loadClass('modUserProfile')) {
            $this->modx->map['modUserProfile']['fields']['work'] = false;
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
                'columns' => [
                    'work' => [
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ],
                ],
            ];
        }
    }

}