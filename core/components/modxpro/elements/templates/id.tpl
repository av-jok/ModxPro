<!DOCTYPE html>
<html lang="{$.en ? 'en-US' : 'ru-RU'}">
<head>
    {block 'head'}
        {include 'file:chunks/_head.tpl'}
    {/block}
</head>
<body>
{include 'file:chunks/_header-id.tpl'}

<section id="content" class="container">
    {$_modx->resource.content}
</section>

{include 'file:chunks/_footer.tpl'}
</body>
</html>