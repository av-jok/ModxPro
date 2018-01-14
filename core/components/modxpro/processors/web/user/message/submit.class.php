<?php

class UserMessageSubmit extends modProcessor
{
    const tpl = '@FILE chunks/email/users/message.tpl';
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
        $subject = trim($this->modx->stripTags($this->getProperty('subject')));
        $body = trim($this->modx->stripTags($this->getProperty('body')));
        if (empty($subject) || empty($body)) {
            return $this->failure($this->modx->lexicon('feedback_err_fields'));
        }

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
        if (!$user = $this->modx->getObject('modUser', $c)) {
            return $this->failure($this->modx->lexicon('feedback_err_user'));
        }

        /** @var modUserProfile $profile */
        $profile = $user->getOne('Profile');
        if (empty($profile->feedback)) {
            return $this->failure($this->modx->lexicon('feedback_err_disabled'));
        }

        $sent = $this->App->sendEmail(
            $profile->email,
            $subject,
            $this->App->pdoTools->getChunk(self::tpl, [
                'text' => $body,
                'user' => $this->modx->user->toArray(),
                'profile' => $this->modx->user->Profile->toArray(),
            ]), [
                'reply-to' => $this->modx->user->Profile->email,
                'fromName' => $this->modx->user->Profile->fullname,
            ]
        );

        return $sent
            ? $this->success($this->modx->lexicon('feedback_success'), ['callback' => 'User.callbacks.message'])
            : $this->failure($this->modx->lexicon('feedback_err_send'), ['callback' => 'User.callbacks.message']);
    }

}

return 'UserMessageSubmit';