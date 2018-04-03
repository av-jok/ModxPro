<h3>{$.en ? 'Comments' : 'Комментарии'}: {$total}</h3>

<div class="comments-list thread">
    {foreach $results.comments as $item}
        {var $level = 0}
        {include 'file:chunks/comments/comments-thread.tpl' item=$item level=$level seen=$results.seen thread=$results.thread}
    {/foreach}
</div>

<br><br>
<h5>{$.en ? 'Comments form' : 'Форма добавления коммента'}</h5>