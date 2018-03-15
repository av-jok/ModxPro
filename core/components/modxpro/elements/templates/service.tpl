{extends 'file:templates/base.tpl'}

{block 'wrapper'}
    <div class="content-wrapper">
        <div class="content">
            {$_modx->resource.content}
        </div>
    </div>
{/block}