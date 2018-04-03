<?php

class OnlineTopicsProcessor extends modProcessor
{

    public function process()
    {
        /** @var App $App */
        $App = $this->modx->getService('App');

        return $App->runProcessor('community/topic/getlatest', [
            'limit' => 10,
            'where' => ['Section.alias:NOT IN' => ['work']],
        ]);
    }

}

return 'OnlineTopicsProcessor';