{var $res = $.App->runProcessor('community/topic/getarchive', [
    'limit' => 100,
])}

{include 'file:chunks/_banner.tpl'}
<div class="topics-list">
    {$res.results}

    {include 'file:chunks/_pagination.tpl' res=$res}
</div>