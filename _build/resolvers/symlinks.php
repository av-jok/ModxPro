<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/ModxPro/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/modxpro')) {
            $cache->deleteTree(
                $dev . 'assets/components/modxpro/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/modxpro/', $dev . 'assets/components/modxpro');
        }
        if (!is_link($dev . 'core/components/modxpro')) {
            $cache->deleteTree(
                $dev . 'core/components/modxpro/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/modxpro/', $dev . 'core/components/modxpro');
        }
    }
}

return true;