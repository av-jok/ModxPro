<?php
if (!class_exists('modObjectGetListProcessor')) {
    /** @noinspection PhpIncludeInspection */
    require_once MODX_CORE_PATH . 'model/modx/modprocessor.class.php';
}


class AppGetListProcessor extends modObjectGetListProcessor
{
    protected $_max_limit = 100;
    protected $_idx = 0;
    /** @var App */
    public $App;
    const tpl = '';


    public function initialize()
    {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => $this->defaultSortField,
            'dir' => $this->defaultSortDirection,
            'combo' => false,
            'query' => '',
        ));

        $this->_idx = intval($this->getProperty('start')) + 1;
        $this->App = $this->modx->getService('App');

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

        $tstart = microtime(true);
        $data['total'] = $this->modx->getCount($this->classKey, $c);
        $this->modx->queryTime += microtime(true) - $tstart;
        $this->modx->executedQueries++;

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
    public function prepareQueryBeforeCount(xPDOQuery $c)
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
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $array) {
            $array = $this->prepareArray($array);
            if (!empty($array) && is_array($array)) {
                $list[] = $array;
                $this->currentIndex++;
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

        if (!empty($this::tpl)) {
            $output['results'] = $this->App->pdoTools->getChunk(
                $this->getProperty('tpl', $this::tpl),
                array_merge($this->getProperties(), $output)
            );
        }

        return $output;
    }

}