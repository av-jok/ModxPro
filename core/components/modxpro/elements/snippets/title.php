<?php

if ($modx->resource) {
    $title = !empty($modx->resource->longtitle)
        ? $modx->resource->longtitle
        : $modx->resource->pagetitle;

    return $title . ' / modx.pro';
}

return 'modx.pro';