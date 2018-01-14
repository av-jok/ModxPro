<?php

class AppRouter
{
    /** @var modX $modx */
    public $modx;
    public $config = [];
    /** @var pdoFetch $pdoTools */
    public $pdoTools;
    /** @var FastRoute\Dispatcher $dispatcher */
    public $dispatcher;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx = $modx;
        $this->pdoTools = $modx->getService('pdoFetch');
        $this->initialize();
    }


    /**
     * Add routes
     */
    public function initialize()
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            $r->addGroup('/users/{user}', function (FastRoute\RouteCollector $r) {
                $r->addRoute('GET', '', 'userPage');
                $r->addRoute('GET', '/{page:topics|comments|favorites}[/{subpage:drafts|comments}]', 'userPage');
                // Redirect from old url
                $r->addRoute('GET', '/tickets[/drafts]', function ($uri) {
                    $redirect = rtrim(str_replace('/tickets', '/topics', $_REQUEST['q']), '/');
                    $this->modx->sendRedirect($redirect, ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
                });
            });
        });
    }


    /**
     * Process request
     */
    public function process()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        // Remove slash and question signs at the end of url
        if ($uri != '/' && in_array(substr($uri, -1), ['/', '?'])) {
            $this->modx->sendRedirect(rtrim($uri, '/?'), ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
        }

        // Remove .html extension
        if (preg_match('#\.html$#i', $uri)) {
            $this->modx->sendRedirect(preg_replace('#\.html$#i', '', $uri), ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
        }

        // Switch contexts
        if (strpos($host, 'en.') === 0) {
            $this->modx->switchContext('en');
        }

        // FastRoute
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $route = $this->dispatcher->dispatch($method, $uri);
        if ($route[0] == FastRoute\Dispatcher::FOUND) {
            if (is_callable($route[1])) {
                $route[1]($route[2]);
            } elseif (is_string($route[1])) {
                $this->{$route[1]}($route[2]);
            }
        }
    }


    /**
     * @param $username
     *
     * @return bool|modUser
     */
    public function getUser($username)
    {
        $where = ['active' => 1];
        if (is_numeric($username)) {
            $where['id'] = (int)$username;
        } else {
            /** @var appUserName $record */
            if (!$record = $this->modx->getObject('appUserName', ['username' => $username])) {
                return false;
            }
            $where['id'] = $record->userid;
        }

        /** @var modUser $user */
        if ($user = $this->modx->getObject('modUser', $where)) {
            if ($user->isMember('Users')) {
                $redirect = false;
                if (!empty($user->Profile->usename) && $username != strtolower($user->username)) {
                    $redirect = str_replace('/' . $username, '/' . strtolower($user->username), $_REQUEST['q']);
                } elseif (empty($user->Profile->usename) && !is_numeric($username)) {
                    $redirect = str_replace('/' . $username, '/' . $user->id, $_REQUEST['q']);
                }
                if (!empty($redirect)) {
                    $this->modx->sendRedirect($redirect);
                }

                return $user;
            }
        }

        return false;
    }


    /**
     * @param array $vars
     */
    public function userPage(array $vars)
    {
        if (!$user = $this->getUser($vars['user'])) {
            return;
        }

        if (!empty($vars['subpage'])) {
            if ($vars['subpage'] == 'drafts' && ($vars['page'] != 'topics' || $user->id != $this->modx->user->id)) {
                return;
            } elseif ($vars['subpage'] == 'comments' && $vars['page'] != 'favorites') {
                return;
            }
        }
        $this->modx->resource = $this->modx->getObject('modResource', $this->modx->getOption('users_id'));

        // Prepare data
        $author = $user->getOne('AuthorProfile');
        $data = [
            'user' => $user->get(['id', 'username', 'external_key']),
            'profile' => $user->Profile->get(['fullname', 'email', 'photo', 'blocked', 'extended', 'comment', 'website', 'city', 'feedback', 'usename']),
            'author' => $author->toArray(),
        ];
        $data['author']['favorites'] = $this->modx->getCount('TicketStar', ['createdby' => $user->id]);
        $data['tab'] = !empty($vars['page'])
            ? $vars['page']
            : 'info';

        $title = [];
        switch ($vars['page']) {
            case 'topics':
                $title[] = $this->modx->lexicon('user_topics');
                break;
            case 'comments':
                $title[] = $this->modx->lexicon('user_comments');
                break;
            case 'favorites':
                $title[] = $this->modx->lexicon('user_favorites');
                break;
            default:
                if (!empty($data['profile']['website'])) {
                    $data['website'] = [
                        'url' => $data['profile']['website'],
                        'name' => mb_strlen($data['profile']['website'], 'UTF-8') > 20
                            ? mb_substr($data['profile']['website'], 0, 20, 'UTF-8') . '...'
                            : $data['profile']['website'],
                    ];
                } else {
                    $data['website'] = [];
                }
                $services = [
                    'github' => 'https://github.com',
                    'twitter' => 'https://twitter.com',
                    'vkontakte' => 'https://vk.com',
                    'skype' => '',
                ];
                foreach ($services as $service => $link) {
                    if (!empty($data['profile']['extended'][$service])) {
                        $data['services'][] = [
                            'user' => $data['profile']['extended'][$service],
                            'link' => $link,
                            'name' => ucfirst($service),
                        ];
                    }
                }
        }
        $title[] = $user->Profile->fullname;
        $title[] = $this->modx->resource->pagetitle;

        $this->modx->resource->set('longtitle', implode(' / ', $title));
        $this->modx->resource->set('content', $this->pdoTools->getChunk('@FILE chunks/users/' . $data['tab'] . '.tpl', $data));

        $this->modx->request->prepareResponse();
    }

}