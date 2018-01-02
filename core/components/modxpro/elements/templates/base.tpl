<!DOCTYPE html>
<html lang="{$.en ? 'en-US' : 'ru-RU'}">
<head>
    {block 'head'}
        {include 'file:chunks/_head.tpl'}
    {/block}
    {'@FILE snippets/open-graph.php' | snippet}
</head>
<body>
{include 'file:chunks/_header.tpl'}

<section id="content" class="container">
    <div class="content-wrapper d-flex no-gutters flex-wrap">
        <div class="content col12 col-md-8 pr-md-3">
            {$_modx->resource.content}
        </div>
        <div class="sidebar col12 col-md-4 pl-md-3">
            <em>Sidebar</em>
        </div>
    </div>
</section>

{include 'file:chunks/_footer.tpl'}
</body>
</html>