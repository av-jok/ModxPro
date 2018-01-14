<?php

class UserMessageGet extends modProcessor
{
    const tpl = '@FILE chunks/users/_message.tpl';
    /** @var App $App */
    public $App;

    /**
     * @return bool
     */
    public function initialize()
    {
        if (!$this->modx->user->isAuthenticated()) {
            return $this->modx->lexicon('feedback_err_auth');
        }
        $this->App = $this->modx->getService('App');

        return parent::initialize();
    }


    /**
     * @return array|string
     */
    public function process()
    {
        $c = $this->modx->newQuery('modUser');
        $c->innerJoin('modUserProfile', 'Profile');
        $c->where([
            'modUser.active' => true,
            'Profile.blocked' => false,
        ]);
        $username = $this->getProperty('user');
        if (is_numeric($username)) {
            $c->where(['modUser.id' => (int)$username]);
        } else {
            $c->where(['modUser.username' => trim($username)]);
        }

        if ($user = $this->modx->getObject('modUser', $c)) {
            if (empty($user->Profile->feedback)) {
                return $this->failure($this->modx->lexicon('feedback_err_disabled'));
            }

            return $this->success('', [
                'html' => $this->App->pdoTools->getChunk(self::tpl, [
                    'id' => $user->id,
                    'to' => $user->Profile->fullname,
                ]),
            ]);
        }

        return $this->failure($this->modx->lexicon('access_denied'));
    }

}

return 'UserMessageGet';