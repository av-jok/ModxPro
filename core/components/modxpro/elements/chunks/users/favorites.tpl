{include 'file:chunks/users/_header.tpl' profile=$profile author=$author}

{var $username = $profile.usename ? $user.username : $user.id}
<div class="user-content">
    <div class="d-flex mb-5 justify-content-center justify-content-md-end">
        <ul class="nav nav-pills d-flex">
            {if $subpage == 'comments'}
                <li class="nav-item">
                    <a class="nav-link" href="/users/{$username}/favorites">
                        {$.en ? 'Topics' : 'Заметки'}
                        <sup class="badge badge-light">{$author.favorite_topics}</sup>
                    </a>
                </li>
                <li class="nav-item">
                    <span class="nav-link active">
                        {$.en ? 'Comments' : 'Комментарии'}
                        <sup class="badge badge-light">{$author.favorite_comments}</sup>
                    </span>
                </li>
            {else}
                <li class="nav-item">
                    <span class="nav-link active">
                        {$.en ? 'Topics' : 'Заметки'}
                        <sup class="badge">{$author.favorite_topics}</sup>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/users/{$username}/favorites/comments">
                        {$.en ? 'Comments' : 'Комментарии'}
                        <sup class="badge badge-light">{$author.favorite_comments}</sup>
                    </a>
                </li>
            {/if}
        </ul>
    </div>

    {if $subpage == 'comments'}
        {var $res = $.App->runProcessor('community/comment/getlist', [
            'limit' => 10,
            'getPages' => true,
            'favorites' => $user.id,
        ])}
        <div class="comments-list">
            {$res.results}
            {include 'file:chunks/_pagination.tpl' res=$res}
        </div>
    {else}
        {var $res = $.App->runProcessor('community/topic/getlist', [
            'limit' => 10,
            'showSection' => true,
            'getPages' => true,
            'favorites' => $user.id,
        ])}
        <div class="topics-list">
            {$res.results}
            {include 'file:chunks/_pagination.tpl' res=$res}
        </div>
    {/if}
</div>