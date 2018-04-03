<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class CommentGetLatestProcessor extends AppGetListProcessor
{
    public $objectType = 'comComment';
    public $classKey = 'comComment';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'desc';

    public $getCount = false;
    public $tpl = '@FILE chunks/comments/latest.tpl';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $last = $this->App->pdoTools->getCollection('comThread', [
            'Section.context_key' => $this->modx->context->key,
        ], [
            'innerJoin' => [
                'Topic' => ['class' => 'comTopic'],
                'Section' => ['class' => 'comSection', 'on' => 'Section.id = Topic.parent'],
            ],
            'select' => [
                'comThread' => 'comment_last',
            ],
            'sortby' => 'comThread.comment_time',
            'sortdir' => 'desc',
            'limit' => $this->getProperty('limit'),
            'setTotal' => false,
        ]);

        $where = [
            $this->classKey . '.deleted' => false,
            'id:IN' => [],
        ];
        foreach ($last as $v) {
            $where['id:IN'][] = $v['comment_last'];
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
        $c->leftJoin('comThread', 'Thread');
        $c->leftJoin('comTopic', 'Topic', 'Thread.topic = Topic.id');
        $c->leftJoin('comSection', 'Section', 'Section.id = Topic.parent');
        $c->leftJoin('modUser', 'User');
        $c->leftJoin('modUserProfile', 'UserProfile');

        $c->select('comComment.id, comComment.text, comComment.createdon, comComment.createdby');
        $c->select('Thread.topic, Thread.comments');
        $c->select('User.username');
        $c->select('UserProfile.fullname, UserProfile.photo, UserProfile.email, UserProfile.usename');
        $c->select('Topic.pagetitle as topic_title, Section.uri');

        return $c;
    }

}

return 'CommentGetLatestProcessor';