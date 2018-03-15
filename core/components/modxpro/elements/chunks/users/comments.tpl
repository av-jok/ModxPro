{include 'file:chunks/users/_header.tpl' profile=$profile author=$author}
{var $res = $.App->runProcessor('community/comment/getlist', [
    'limit' => 20,
    'user' => $user.id,
])}

<div class="user-content">
    <div class="comments-list">
        {$res.results}

        {include 'file:chunks/_pagination.tpl' res=$res}
    </div>
</div>