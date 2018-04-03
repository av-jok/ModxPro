<?php


if (!class_exists('modObjectGetListProcessor')) {
    /** @noinspection PhpIncludeInspection */
    require_once MODX_CORE_PATH . 'model/modx/modprocessor.class.php';
}

use JasonGrimes\Paginator;

class AppGetListProcessor extends modObjectGetListProcessor
{
    /** @var App */
    public $App;
    public $getPages = false;
    public $tpl = '';

    protected $_max_limit = 100;
    protected $_page = 0;


    /**
     * @return bool
     */
    public function initialize()
    {
        $this->App = $this->modx->getService('App');
        if ($tpl = $this->getProperty('tpl')) {
            $this->tpl = $tpl;
        }
        $getPages = $this->getProperty('getPages');
        if ($getPages !== null) {
            $this->getPages = $getPages;
        }

        $limit = $this->getProperty('limit', 10);
        $start = 0;
        if ($this->getPages && !empty($_REQUEST['page'])) {
            $this->_page = (int)$_REQUEST['page'];
            if ($this->_page > 1) {
                $start = ($this->_page * $limit) - $limit;
            }
        }
        $this->setDefaultProperties([
            'start' => $start,
            'limit' => $limit,
            'sort' => $this->defaultSortField,
            'dir' => $this->defaultSortDirection,
            'combo' => false,
            'query' => '',
        ]);

        return true;
    }


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $check = $this->checkRequest();

        return $check !== true
            ? $check
            : parent::process();
    }


    public function checkRequest()
    {
        $request = $_REQUEST;
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        if (!$isAjax && !empty($request['page']) && $request['page'] == 1) {
            unset($_GET['page'], $_GET['q']);
            $url = preg_replace('#\?.*#', '', $_SERVER['REQUEST_URI']);
            if (!empty($_GET)) {
                $url .= '?' . http_build_query($_GET);
            }

            return $this->failure('', [
                'redirect' => $url,
            ]);
        }

        return true;
    }


    /**
     * Get the data of the query
     *
     * @return array
     */
    public function getData()
    {
        $data = [];
        $start = intval($this->getProperty('start'));
        $limit = intval($this->getProperty('limit'));
        if ($limit > $this->_max_limit) {
            $limit = $this->_max_limit;
        }

        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);

        if ($this->getPages) {
            $tstart = microtime(true);
            $data['total'] = $this->modx->getCount($this->classKey, $c);
            $this->modx->queryTime += microtime(true) - $tstart;
            $this->modx->executedQueries++;
        } else {
            $data['total'] = false;
        }

        $c = $this->prepareQueryAfterCount($c);

        $sortClassKey = $this->getSortClassKey();
        $sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '',
            [$this->getProperty('sort')]);
        if (empty($sortKey)) {
            $sortKey = $this->getProperty('sort');
        }
        $c->sortby($sortKey, $this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit, $start);
        }
        //$c->prepare();$this->modx->log(1, print_r($c->toSQL(),1));
        $data['results'] = [];
        $tstart = microtime(true);
        if ($c->prepare() && $c->stmt->execute()) {
            //$this->modx->log(modX::LOG_LEVEL_ERROR, $c->toSQL());
            $this->modx->queryTime += microtime(true) - $tstart;
            $this->modx->executedQueries++;
            $data['results'] = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,
                "[App] GetList error: " . print_r($c->stmt->errorInfo(), true) . $c->toSQL());
        }

        return $data;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));

        return $c;
    }


    /**
     * Iterate across the data
     *
     * @param array $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $list = [];
        $list = $this->beforeIteration($list);
        $this->currentIndex = intval($this->getProperty('start')) + 1;

        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $array) {
            $array = $this->prepareArray($array);
            if (!empty($array) && is_array($array)) {
                $array['idx'] = $this->currentIndex++;
                $list[] = $array;
            }
        }
        $list = $this->afterIteration($list);

        return $list;
    }


    /**
     * @param array $array
     *
     * @return array
     */
    public function prepareArray(array $array)
    {
        return $array;
    }


    /**
     * @param array $array
     * @param bool $count
     *
     * @return array
     */
    public function outputArray(array $array, $count = false)
    {
        if ($count === false) {
            $count = count($array);
        }

        $output = [
            'success' => !empty($array),
            'total' => $count,
            'results' => $array,
        ];

        if ($this->tpl) {
            $output['results'] = $this->App->pdoTools->getChunk(
                $this->getProperty('tpl', $this->tpl),
                array_merge($this->getProperties(), $output)
            );
        }

        if ($this->getPages && $limit = $this->getProperty('limit')) {
            $paginator = new Paginator($count, $limit, $this->_page, '?page=(:num)');
            $output['pages'] = $paginator->getPages();
        }

        return $output;
    }

}