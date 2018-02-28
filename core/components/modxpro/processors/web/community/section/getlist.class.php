<?php

require_once dirname(dirname(dirname(__FILE__))) . '/getlist.class.php';

class SectionGetListProcessor extends AppGetListProcessor
{
    public $objectType = 'comSection';
    public $classKey = 'comSection';
    public $defaultSortField = 'publishedon';
    public $defaultSortDirection = 'desc';

    const tpl = '@FILE chunks/sections/list.tpl';


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
            $this->classKey . '.context_key' => $this->modx->context->key,
            // @TODO Maybe replace it to class_key?
            $this->classKey . '.template' => 3,
        ];

        if ($tmp = $this->getProperty('where', [])) {
            $where = array_merge($tmp, $where);
        }

        if ($where) {
            $c->where($where);
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
        $c->leftJoin('comTotal', 'Total', 'Total.id = comSection.id AND Total.class = "comSection"');

        $c->select('comSection.id, comSection.pagetitle, comSection.uri, comSection.description');
        $c->select('Total.comments, Total.views, Total.topics');

        return $c;
    }
}

return 'SectionGetListProcessor';