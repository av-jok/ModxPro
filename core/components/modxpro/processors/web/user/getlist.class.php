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
    public $getPages = true;

    protected $_max_limit = 20;


    /**
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
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
        $c->leftJoin('modUserProfile', 'Profile');
        $c->leftJoin('modUserGroupMember', 'UserGroupMembers');
        $c->leftJoin('comAuthor', 'AuthorProfile', 'AuthorProfile.id = modUser.id');

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
                'Profile.work' => 1,
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
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select('modUser.id, modUser.username');
        $c->select('Profile.fullname as name, Profile.photo, Profile.email, Profile.work, Profile.usename');
        $c->select('AuthorProfile.createdon, AuthorProfile.rating, AuthorProfile.topics, AuthorProfile.comments');
        $c->select('AuthorProfile.createdon, AuthorProfile.visitedon');

        return $c;
    }


    /**
     * @param array $array
     *
     * @return array
     */
    public function prepareArray(array $array)
    {
        $modifier = $this->App->pdoTools->getFenom()->getModifier('avatar');

        $array['avatar'] = $modifier($array, 48);
        $array['avatar_retina'] = $modifier($array, 96);
        $array['link'] = !empty($array['usename'])
            ? strtolower($array['username'])
            : (int)$array['id'];

        unset($array['id'], $array['usename'], $array['username'], $array['photo'], $array['email']);

        return $array;
    }

}

return 'UserGetListProcessor';