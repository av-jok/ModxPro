<?php
if (!class_exists('AppGetListProcessor')) {
    require_once dirname(dirname(__FILE__)) . '/getlist.class.php';
}


class UserGetListProcessor extends AppGetListProcessor
{
    public $objectType = 'modUser';
    public $classKey = 'modUser';
    public $defaultSortField = 'rating';
    public $defaultSortDirection = 'desc';
    /** @var App */
    public $App;
    /** @var FenomX $fenom */
    public $Fenom;

    protected $_idx = 0;
    protected $_max_limit = 20;

    /**
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        $this->App = $this->modx->getService('App');
        $this->Fenom = $this->App->pdoTools->getFenom();
        $this->_idx = intval($this->getProperty('start')) + 1;

        $sort = $this->getProperty('sort');
        if (!in_array($sort, ['name', 'comments', 'topics', 'rating'])) {
            $this->setProperty('sort', $this->defaultSortField);
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('modUserProfile', 'Profile');
        $c->innerJoin('TicketAuthor', 'AuthorProfile');
        $c->innerJoin('modUserGroupMember', 'UserGroupMembers');

        $c->select('modUser.username');
        $c->select('Profile.fullname as name, Profile.photo, Profile.email, Profile.work');
        $c->select('AuthorProfile.createdon, AuthorProfile.rating, AuthorProfile.tickets as topics, AuthorProfile.comments');
        $c->select('AuthorProfile.createdon, AuthorProfile.visitedon');

        $c->where([
            'modUser.active' => 1,
            'Profile.blocked' => 0,
            'Profile.fullname:!=' => ' ',
            'UserGroupMembers.user_group' => 2,
        ]);

        if ($query = $this->getProperty('query')) {
            $query = trim($query);
            $c->where([
                'modUser.username:LIKE' => "%{$query}%",
                'OR:Profile.fullname:LIKE' => "%{$query}%",
            ]);
        }
        $work = $this->getProperty('work');
        if (!empty($work) && $work != 'false') {
            $c->where([
                'Profile.work' => 1
            ]);
        }
        $rating = $this->getProperty('rating');
        if (!empty($rating) && $rating != 'false') {
            $c->where([
                'AuthorProfile.rating:>' => 0,
            ]);
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
        $modifier = $this->Fenom->getModifier('avatar');
        $array['idx'] = $this->_idx++;
        $array['avatar'] = $modifier($array, 96);
        unset($array['photo'], $array['email']);

        return $array;
    }

}

return 'UserGetListProcessor';