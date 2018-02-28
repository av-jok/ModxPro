<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class TopicGetListProcessor extends AppGetListProcessor
{
    public $objectType = 'comTopic';
    public $classKey = 'comTopic';
    public $defaultSortField = 'publishedon';
    public $defaultSortDirection = 'desc';

    const tpl = '@FILE chunks/topics/list.tpl';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('comSection', 'Section');

        $where = [
            $this->classKey . '.published' => true,
            $this->classKey . '.deleted' => false,
        ];
        if ($user = (int)$this->getProperty('user')) {
            $where[$this->classKey . '.createdby'] = $user;
        } else {
            $where['Section.context_key'] = $this->modx->context->key;
        }

        if ($tmp = $this->getProperty('where', [])) {
            $where = array_merge($tmp, $where);
        }

        if ($where) {
            $c->where($where);
        }

        if ($query = $this->getProperty('query')) {
            if (is_numeric($query)) {
                $c->where([
                    $this->classKey . '.createdby' => (int)$query,
                ]);
            } else {
                $query = trim($query);
                $c->where([
                    $this->classKey . '.text:LIKE' => "%{$query}%",
                ]);
            }
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
        $c->select('comTopic.id, comTopic.pagetitle, comTopic.introtext, comTopic.createdby, comTopic.publishedon');
        if (!$this->getProperty('fastMode')) {
            $c->leftJoin('comTotal', 'Total', 'Total.id = comTopic.id AND Total.class = "comTopic"');
            $c->leftJoin('modUser', 'User');
            $c->leftJoin('modUserProfile', 'UserProfile');

            $c->select($this->modx->getSelectColumns('comSection', 'Section', 'section_', ['pagetitle', 'context_key', 'uri']));
            $c->select('Total.comments, Total.views, Total.stars, Total.rating, Total.rating_plus, Total.rating_minus');
            $c->select('User.username');
            $c->select('UserProfile.photo, UserProfile.email, UserProfile.fullname, UserProfile.usename');
        }

        return $c;
    }
}

return 'TopicGetListProcessor';