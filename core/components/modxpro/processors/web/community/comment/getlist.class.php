<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class CommentGetListProcessor extends AppGetListProcessor
{
    public $objectType = 'comComment';
    public $classKey = 'comComment';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'desc';

    public $getPages = true;
    public $tpl = '@FILE chunks/comments/list.tpl';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $where = [
            $this->classKey . '.published' => true,
            $this->classKey . '.deleted' => false,
        ];

        if ($user = (int)$this->getProperty('user')) {
            $where[$this->classKey . '.createdby'] = $user;
        } elseif ($favorites = (int)$this->getProperty('favorites')) {
            $q = $this->modx->newQuery('comStar', ['createdby' => $favorites, 'class' => 'comComment']);
            $tstart = microtime(true);
            if ($q->prepare() && $q->stmt->execute()) {
                $this->modx->queryTime += microtime(true) - $tstart;
                $this->modx->executedQueries++;
                $where[$this->classKey . '.id:IN'] = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            }
        } elseif ($topic = (int)$this->getProperty('topic')) {
            // Select only comments from current topic
            $q = $this->modx->newQuery('comThread', ['topic' => $topic]);
            $q->select('id');
            $tstart = microtime(true);
            if ($q->prepare() && $q->stmt->execute()) {
                $this->modx->queryTime += microtime(true) - $tstart;
                $this->modx->executedQueries++;
                $where[$this->classKey . '.thread'] = (int)$q->stmt->fetchColumn();

            }
        } else {
            // Select only comments from current context
            $q = $this->modx->newQuery('comThread');
            $q->leftJoin('comTopic', 'Topic');
            $q->leftJoin('comSection', 'Section', 'Section.id = Topic.parent');
            $q->where([
                'Section.context_key' => $this->modx->context->key,
                'Topic.published' => true,
                'Topic.deleted' => false,
            ]);
            $q->select('comThread.id');
            $tstart = microtime(true);
            if ($q->prepare() && $q->stmt->execute()) {
                $this->modx->queryTime += microtime(true) - $tstart;
                $this->modx->executedQueries++;
                $where[$this->classKey . '.thread:IN'] = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            }
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
        $c->leftJoin('comThread', 'Thread');
        $c->leftJoin('comTopic', 'Topic', 'Thread.topic = Topic.id');
        $c->leftJoin('comSection', 'Section', 'Section.id = Topic.parent');
        $c->leftJoin('modUser', 'User');
        $c->leftJoin('modUserProfile', 'UserProfile');

        $c->select('comComment.id, comComment.text, comComment.createdon, comComment.createdby, comComment.rating, comComment.rating_plus, comComment.rating_minus, comComment.thread');
        $c->select('Thread.topic, Thread.comments');
        $c->select('User.username');
        $c->select('UserProfile.fullname, UserProfile.photo, UserProfile.email, UserProfile.usename');
        $c->select('Topic.pagetitle as topic_title, Section.uri, Section.pagetitle as section_title');

        return $c;
    }

}

return 'CommentGetListProcessor';