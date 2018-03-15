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
    {block 'wrapper'}
        <div class="content-wrapper d-flex no-gutters flex-wrap">
            <div class="content col-12 col-md-8 pr-md-3">
                {block 'content'}
                    {$_modx->resource.content}
                {/block}
            </div>
            <div class="sidebar col-12 mt-5 col-md-4 pl-md-3 mt-md-0">
                {block 'sidebar'}
                    <form method="get" action="/search" class="mb-5">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control"
                                   placeholder="{$.en ? 'Search' : 'Поиск'}">
                            <div class="input-group-append">
                                <button class="input-group-text">
                                    <i class="far fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    {include 'file:chunks/_online.tpl'}
                {/block}
            </div>
        </div>
    {/block}
</section>

{include 'file:chunks/_footer.tpl'}
</body>
</html>