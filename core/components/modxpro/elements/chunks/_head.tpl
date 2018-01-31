<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{'@FILE snippets/title.php' | snippet}</title>
<meta name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
<meta name="google-site-verification" content="LjcSqt0rdBuB4KHmHXUoLbsAsNIa7Ygquf7gN53xbxg" />
<meta name="page-context" content="{$_modx->context.key}">
<link rel="apple-touch-icon" sizes="57x57" href="/assets/components/modxpro/img/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/assets/components/modxpro/img/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/assets/components/modxpro/img/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/assets/components/modxpro/img/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/assets/components/modxpro/img/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/assets/components/modxpro/img/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/assets/components/modxpro/img/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/assets/components/modxpro/img/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/assets/components/modxpro/img/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/assets/components/modxpro/img/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/components/modxpro/img/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/assets/components/modxpro/img/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/components/modxpro/img/favicon/favicon-16x16.png">
<link rel="manifest" href="/assets/components/modxpro/img/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
{*<meta name="page-id" content="{$_modx->resource.id}">*}
{('<meta name="csrf-token" content="' ~ $.session['csrf-token'] ~ '">') | htmlToHead}
{('<meta name="assets-version" content="' ~ $.assets_version ~ '">') | htmlToHead}
{('/assets/components/modxpro/css/web/main.css?v=' ~ $.assets_version) | cssToHead}
{('/assets/components/modxpro/js/web/lib/require.min.js?v=' ~ $.assets_version) | jsToHead : false}
{('/assets/components/modxpro/js/web/config.js?v=' ~ $.assets_version) | jsToHead : false}
{if !$_modx->isAuthenticated()}
    {'<script type="text/javascript">requirejs(["app/auth"]);</script>' | htmlToBottom}
{/if}
{if $_modx->isMember('Administrator')}
    {'<script type="text/javascript">requirejs(["app/adminpanel"]);</script>' | htmlToBottom}
{/if}
{'<script type="text/javascript">requirejs(["app/counters"]);</script>' | htmlToBottom}