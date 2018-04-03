<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class CommentGetThreadProcessor extends AppGetListProcessor
{
    public $objectType = 'comComment';
    public $classKey = 'comComment';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'asc';

    public $_max_limit = 0;
    public $getPages = false;
    public $tpl = '@FILE chunks/comments/comments.tpl';
    /** @var comThread $thread */
    protected $thread;


    /**
     * @return bool
     */
    public function initialize()
    {
        if (!$this->thread = $this->modx->getObject('comThread', ['topic' => (int)$this->getProperty('topic')])) {
            return 'no_topic';
        }

        return parent::initialize();
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where([
            'thread' => $this->thread->id,
        ]);

        return $c;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->leftJoin('modUser', 'User');
        $c->leftJoin('modUserProfile', 'UserProfile');
        if ($this->modx->user->id) {
            $c->leftJoin('comStar', 'Star', 'Star.id = comComment.id AND Star.class = "comComment" AND Star.createdby = ' . $this->modx->user->id);
            $c->select('Star.id as star');
        }
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        $c->select('User.username');
        $c->select('UserProfile.fullname, UserProfile.photo, UserProfile.email, UserProfile.usename');


        return $c;
    }


    /**
     * @param array $array
     * @param bool $count
     *
     * @return array
     */
    public function outputArray(array $array, $count = false)
    {
        $count = count($array);
        $view = $this->modx->getObject('comView', [
            'topic_id' => $this->thread->topic,
            'user_id' => $this->modx->user->id
        ]);
        $array = [
            'comments' => $this->buildTree($array),
            'seen' => $view ? $view->get('timestamp'): false,
            'thread' => $this->thread->toArray(),
        ];

        return parent::outputArray($array, $count);
    }


    /**
     * @param array $tmp
     * @param string $id
     * @param string $parent
     * @param array $roots
     *
     * @return array
     */
    public function buildTree($tmp = [], $id = 'id', $parent = 'parent', array $roots = [])
    {
        $rows = $tree = [];
        foreach ($tmp as $v) {
            $rows[$v[$id]] = $v;
        }

        foreach ($rows as $id => &$row) {
            if (empty($row[$parent]) || (!isset($rows[$row[$parent]]) && in_array($id, $roots))) {
                $tree[$id] = &$row;
            } else {
                $rows[$row[$parent]]['children'][$id] = &$row;
            }
        }

        return $tree;
    }

}

return 'CommentGetThreadProcessor';