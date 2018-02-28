{var $res = $.App->runProcessor('community/topic/getlist', [
    'limit' => 10,
    'showSection' => true,
    'where' => ['Section.alias:NOT IN' => ['help', 'work']]
])}

{include 'file:chunks/_banner.tpl'}
<div class="topics-list">
    {$res.results}
</div>