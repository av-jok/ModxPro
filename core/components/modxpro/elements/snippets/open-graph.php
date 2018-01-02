<?php
/** @var modX $modx */
if (!$modx->resource) {
    return;
}
/** @var pdoTools $pdoTools */
$pdoTools = $modx->getService('pdoTools');
/** @var FenomX $fenom */
$fenom = $pdoTools->getFenom();
$default_image = $modx->getOption('site_url') . 'assets/components/modxpro/img/logo-share.png';

$tags = [];
/*
if ($modx->resource->class_key == 'msProduct') {
    $description = [];
    // @var modUser $user
    $user = $modx->resource->getOne('CreatedBy');
    if ($price = $modx->resource->get('price')) {
        $format = $fenom->getModifier('price');
        $description[] = $format($price, '');
    }
    $description[] = $user->Profile->fullname . ': ' . $modx->resource->description;

    $description = htmlentities(strip_tags(implode(', ', $description)), ENT_QUOTES, 'utf-8');
    // Social
    $tags = array(
        'og:title' => $modx->resource->pagetitle,
        'og:type' => 'article',
        'og:url' => $modx->makeUrl($modx->resource->id, '', '', 'full'),
        'og:description' => $description,
        'og:site_name' => $modx->getOption('site_name'),
    );
    if ($image = $modx->resource->get('image')) {
        $tags['og:image'] =
        $tags['twitter:image:src'] = $modx->getOption('site_url') . ltrim($image, '/');
    } else {
        $tags['og:image'] =
        $tags['twitter:image:src'] = $default_image;
    }

    // Twitter
    $tags = array_merge($tags, array(
        'twitter:card' => 'summary',
        'twitter:site' => '@bezumkin',
        'twitter:creator' => '@bezumkin',
        'twitter:title' => $tags['og:title'],
        'twitter:description' => mb_strlen($tags['og:description']) > 200
            ? mb_substr($tags['og:description'], 0, 196, 'utf-8') . '...'
            : $tags['og:description'],

    ));
} elseif ($modx->resource->id == $modx->getOption('authors_id')) {
    if ($user = $modx->getObject('modUser', (int)$modx->getPlaceholder('extras_author'))) {
        $c = $modx->newQuery('extraPackage', ['active' => true, 'createdby' => $user->id]);
        $c->select('name');
        $c->limit(5);
        $c->sortby('rand()');
        $description = '';
        if ($c->prepare() && $c->stmt->execute()) {
            $description = implode(', ', $c->stmt->fetchAll(PDO::FETCH_COLUMN));
        }
        if (!empty($description)) {
            $description = ($modx->getOption('cultureKey') == 'en'
                    ? 'The developer of '
                    : 'Разработчик ') . $description;
        }
        $image = 'https://gravatar.com/avatar/' . md5(strtolower($user->Profile->email)) . '?s=300&d=mm';
        $tags = array(
            'og:title' => $user->Profile->fullname,
            'og:type' => 'article',
            'og:url' => $modx->makeUrl($modx->resource->id, '', '', 'full') . '/' . ($user->remote_key ?: $user->id),
            'og:description' => $description,
            'og:site_name' => $modx->getOption('site_name'),
            'og:image' => $image,
            'twitter:image:src' => $image,
            'twitter:card' => 'summary',
            'twitter:site' => '@bezumkin',
            'twitter:creator' => '@bezumkin',
            'twitter:title' => $user->Profile->fullname,
            'twitter:description' => $description,
        );
    }
}
*/
if (empty($tags)) {
    $tags = array(
        'og:title' => $modx->resource->longtitle ?: $modx->resource->pagetitle,
        'og:type' => 'article',
        'og:url' => $modx->makeUrl($modx->resource->id, '', '', 'full'),
        'og:description' => htmlentities(strip_tags($modx->resource->description), ENT_QUOTES, 'utf-8'),
        'og:site_name' => $modx->getOption('site_name'),
        'og:image' => $default_image,
        'twitter:image:src' => $default_image,
        'twitter:card' => 'summary',
        'twitter:site' => '@bezumkin',
        'twitter:creator' => '@bezumkin',
        'twitter:title' => $modx->resource->longtitle ?: $modx->resource->pagetitle,
        'twitter:description' => $modx->resource->description,
    );
}


$html = [];
foreach ($tags as $tag => $value) {
    $html[] = "<meta property=\"{$tag}\" content=\"{$value}\" />";
}
$html[] = "<meta name=\"description\" content=\"{$tags['og:description']}\">";
$html[] = "<meta name=\"title\" content=\"{$tags['og:title']}\">";

$modx->regClientStartupHTMLBlock(implode("\n", $html));