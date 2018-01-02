<?php

class LexiconGetProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {
        $entries = $this->modx->lexicon->getFileTopic(
            $this->modx->config['cultureKey'],
            'modxpro',
            'frontend'
        );

        return $this->success('', $entries);
    }

}

return 'LexiconGetProcessor';