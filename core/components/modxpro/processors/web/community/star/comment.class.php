<?php

class CommunityStarCommentProcessor extends modObjectProcessor
{
    public $classKey = 'comStar';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        return $this->modx->user->isAuthenticated($this->modx->context->key)
            ? true
            : $this->modx->lexicon('access_denied');
    }


    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $key = [
            'id' => (int)$this->getProperty('id'),
            'class' => 'comComment',
        ];
        /** @var comTotal $total */
        if (!$total = $this->modx->getObject('comTotal', $key)) {
            $total = $this->modx->newObject('comTotal', $key);
            $total->fromArray($key, '', true, true);
        }
        $key['createdby'] = $this->modx->user->id;

        /** @var comStar $star */
        if (!$star = $this->modx->getObject($this->classKey, $key)) {
            $star = $this->modx->newObject($this->classKey, $key);
            $star->fromArray($key, '', true, true);
            $star->set('createdon', date('Y-m-d H:i:s'));
            /** @var comComment $object */
            if ($object = $this->modx->getObject('comComment', $key['id'])) {
                $star->set('owner', $object->createdby);
            }
            if ($star->save()) {
                $total->set($total->get('stars') + 1);
            }
        } else {
            if ($star->remove()) {
                $total->set($total->get('stars') - 1);
            }
        }
        $total->save();

        return $this->success('', $total->get(['stars']));
    }

}

return 'CommunityStarCommentProcessor';