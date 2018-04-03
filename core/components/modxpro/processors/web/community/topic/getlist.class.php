<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class TopicGetListProcessor extends AppGetListProcessor
{
    public $objectType = 'comTopic';
    public $classKey = 'comTopic';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'desc';

    public $getPages = true;
    public $tpl = '@FILE chunks/topics/list.tpl';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('comSection', 'Section');
        if (!$this->getProperty('fastMode')) {
            $c->leftJoin('comTotal', 'Total', 'Total.id = comTopic.id AND Total.class = "comTopic"');
        }

        $where = [
            $this->classKey . '.published' => true,
            $this->classKey . '.deleted' => false,
        ];
        if ($user = (int)$this->getProperty('user')) {
            $where[$this->classKey . '.createdby'] = $user;
        } elseif ($favorites = (int)$this->getProperty('favorites')) {
            $q = $this->modx->newQuery('comStar', ['createdby' => $favorites, 'class' => 'comTopic']);
            $tstart = microtime(true);
            if ($q->prepare() && $q->stmt->execute()) {
                $this->modx->queryTime += microtime(true) - $tstart;
                $this->modx->executedQueries++;
                $where[$this->classKey . '.id:IN'] = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            }
        } else {
            $where['Section.context_key'] = $this->modx->context->key;
        }

        if ($tmp = $this->getProperty('where', [])) {
            $where = array_merge($where, $tmp);
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
        $c->select('comTopic.id, comTopic.pagetitle, comTopic.introtext, comTopic.createdby, comTopic.createdon');
        $c->select($this->modx->getSelectColumns('comSection', 'Section', 'section_', ['pagetitle', 'context_key', 'uri']));
        if (!$this->getProperty('fastMode')) {
            $c->leftJoin('modUser', 'User');
            $c->leftJoin('modUserProfile', 'UserProfile');
            if ($this->modx->user->id) {
                $c->leftJoin('comThread', 'Thread');
                $c->select('Thread.id as thread');
                $c->leftJoin('comStar', 'Star', 'Star.id = comTopic.id AND Star.class = "comTopic" AND Star.createdby = ' . $this->modx->user->id);
                $c->select('Star.id as star');
            }

            $c->select('Total.comments, Total.views, Total.stars, Total.rating, Total.rating_plus, Total.rating_minus');
            $c->select('User.username');
            $c->select('UserProfile.photo, UserProfile.email, UserProfile.fullname, UserProfile.usename');
        }

        return $c;
    }


    /**
     * @param array $array
     *
     * @return array
     */
    public function prepareArray(array $array)
    {
        if (!$this->getProperty('fastMode') && $this->modx->user->id) {
            /** @var comView $view */
            $view = $this->modx->getObject('comView', ['topic_id' => $array['id'], 'user_id' => $this->modx->user->id]);
            if ($view && !empty($array['thread'])) {
                $array['new_comments'] = $this->modx->getCount('comComment', [
                    'thread' => $array['thread'],
                    'createdon:>' => $view->timestamp,
                    'createdby:!=' => $this->modx->user->id,
                ]);
            }
        } else {
            $array['new'] = 0;
        }

        return $array;
    }

}

return 'TopicGetListProcessor';