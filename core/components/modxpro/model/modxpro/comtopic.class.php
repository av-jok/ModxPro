<?php

class comTopic extends xPDOSimpleObject
{

    /**
     * @param string $k
     * @param null $v
     * @param string $vType
     *
     * @return bool
     */
    public function set($k, $v = null, $vType = '')
    {
        if (is_string($k) && is_numeric($v) && preg_match('#on$#', $k)) {
            $v = !empty($v)
                ? date('Y-m-d H:i:s', $v)
                : null;
        }

        return parent::set($k, $v, $vType);
    }

}