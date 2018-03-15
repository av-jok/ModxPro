<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class TopicGetArchiveProcessor extends AppGetListProcessor
{
    public $objectType = 'comTopic';
    public $classKey = 'comTopic';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'desc';

    public $getPages = true;
    public $tpl = '@FILE chunks/topics/archive.tpl';
    public $dateField = 'createdon';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('comSection', 'Section');
        $where = [
            $this->classKey . '.published' => true,
            $this->classKey . '.deleted' => false,
            'Section.context_key' => $this->modx->context->key,
        ];
        if ($tmp = $this->getProperty('where', [])) {
            $where = array_merge($where, $tmp);
        }
        $c->where($where);

        return $c;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select('comTopic.id, comTopic.pagetitle, comTopic.createdby, comTopic.createdon');
        $c->leftJoin('comTotal', 'Total', 'Total.id = comTopic.id AND Total.class = "comTopic"');
        //$c->leftJoin('modUser', 'User');
        //$c->leftJoin('modUserProfile', 'UserProfile');

        $c->select($this->modx->getSelectColumns('comSection', 'Section', 'section_', ['pagetitle', 'uri']));
        $c->select('Total.comments, Total.views');
        //$c->select('User.username');
        //$c->select('UserProfile.photo, UserProfile.email, UserProfile.fullname, UserProfile.usename');

        return $c;
    }


    /**
     * @param array $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $tree = [];
        foreach ($data['results'] as $row) {
            $date = $row[$this->dateField];
            if (!is_numeric($date)) {
                $date = strtotime($date);
            }
            $tree[date('Y', $date)][date('m', $date)][date('d', $date)][] = $row;
        }

        return $tree;
    }

}

return 'TopicGetArchiveProcessor';