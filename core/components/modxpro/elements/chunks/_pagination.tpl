{if !$res.success}
    {if $res.object.redirect}
        {$_modx->sendRedirect($res.object.redirect)}
    {/if}
{elseif $res.pages}
    <ul class="pagination flex-wrap justify-content-center justify-content-md-start mt-5">
        {foreach $res.pages as $page}
            {if !$page.url}
                {var $class = 'disabled'}
            {elseif ($.get.page == $page.num || (!$.get.page && $page.num == 1) ? 'active' : '')}
                {var $class = 'active'}
            {else}
                {var $class = ''}
            {/if}
            <li class="page-item {$class}">
                <a class="page-link" href="{$page.url}">{$page.num}</a>
            </li>
        {/foreach}
    </ul>
{/if}