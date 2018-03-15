{var $res = $.App->runProcessor('community/topic/getlist', [
    'limit' => 10,
    'showSection' => true,
    'where' => [
        'Section.alias:NOT IN' => ['help', 'work'],
        'comTopic.important:>=' => 0,
        'Total.rating:>' => -3,
    ],
])}

{include 'file:chunks/_banner.tpl'}
<div class="topics-list">
    {$res.results}

    {include 'file:chunks/_pagination.tpl' res=$res}
</div>
