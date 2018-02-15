<?php

if ($modx->resource) {
    $title = !empty($modx->resource->longtitle)
        ? $modx->resource->longtitle
        : $modx->resource->pagetitle;
    if (!empty($title)) {
        return $title . ' / modx.pro';
    }

}

return $modx->getOption('site_name');