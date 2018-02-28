{include 'file:chunks/users/_header.tpl' profile=$profile author=$author}
{var $res = $.App->runProcessor('community/topic/getlist', [
    'limit' => 10,
    'user' => $user.id,
])}

<div class="user-content">
    <div class="topics-list">
        {$res['results']}
    </div>
</div>