<?php

class OnlineCommentsProcessor extends modProcessor
{

    public function process()
    {
        /** @var App $App */
        $App = $this->modx->getService('App');

        return $App->runProcessor('community/comment/getlatest', [
            'limit' => 10,
        ]);
    }

}

return 'OnlineCommentsProcessor';