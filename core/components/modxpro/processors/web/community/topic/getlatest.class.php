<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class TopicGetLatestProcessor extends AppGetListProcessor
{
    public $objectType = 'comTopic';
    public $classKey = 'comTopic';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'desc';

    public $tpl = '@FILE chunks/topics/latest.tpl';


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
            'Section.context_key' => $this->modx->context->key
        ];


        if ($tmp = $this->getProperty('where', [])) {
            $where = array_merge($where, $tmp);
        }
        if ($where) {
            $c->where($where);
        }

        return $c;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select('comTopic.id, comTopic.pagetitle, comTopic.introtext, comTopic.createdby, comTopic.createdon');
        $c->leftJoin('comTotal', 'Total', 'Total.id = comTopic.id AND Total.class = "comTopic"');
        $c->leftJoin('modUser', 'User');
        $c->leftJoin('modUserProfile', 'UserProfile');

        $c->select($this->modx->getSelectColumns('comSection', 'Section', 'section_', ['pagetitle', 'context_key', 'uri']));
        $c->select('Total.comments, Total.views');
        $c->select('User.username');
        $c->select('UserProfile.photo, UserProfile.email, UserProfile.fullname, UserProfile.usename');

        return $c;
    }
}

return 'TopicGetLatestProcessor';