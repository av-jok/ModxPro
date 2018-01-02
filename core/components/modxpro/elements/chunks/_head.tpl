<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{'@FILE snippets/title.php' | snippet}</title>
<meta name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
<meta name="google-site-verification" content="LjcSqt0rdBuB4KHmHXUoLbsAsNIa7Ygquf7gN53xbxg" />
<meta name="page-context" content="{$_modx->context.key}">
{*<meta name="page-id" content="{$_modx->resource.id}">*}
{('<meta name="csrf-token" content="' ~ $.session['csrf-token'] ~ '">') | htmlToHead}
{('<meta name="assets-version" content="' ~ $.assets_version ~ '">') | htmlToHead}
{('/assets/components/modxpro/css/web/main.css?v=' ~ $.assets_version) | cssToHead}
{('/assets/components/modxpro/js/web/lib/require.min.js?v=' ~ $.assets_version) | jsToHead : false}
{('/assets/components/modxpro/js/web/config.js?v=' ~ $.assets_version) | jsToHead : false}
{*'<script type="text/javascript">requirejs(["app","app/auth"]);</script>' | htmlToBottom*}
{'<script type="text/javascript">requirejs(["app/counters"]);</script>' | htmlToBottom}
{if $_modx->isMember('Administrator')}
    {'<script type="text/javascript">requirejs(["app/adminpanel"]);</script>' | htmlToBottom}
{/if}