{var $res = $.App->runProcessor('community/section/getlist', [
    'limit' => 0,
])}

{include 'file:chunks/_banner.tpl'}
<div class="topics-list">
    {$res.results}
</div>