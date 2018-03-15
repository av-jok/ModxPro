{include 'file:chunks/users/_header.tpl' profile=$profile author=$author}

<div class="user-content">
    <div class="topics-list">
        {if $user.id == $_modx->user.id}
            {var $username = $profile.usename ? $user.username : $user.id}
            <div class="d-flex mb-5 justify-content-center justify-content-md-end">
                <ul class="nav nav-pills d-flex">
                    {if $subpage == 'drafts'}
                        <li class="nav-item">
                            <a class="nav-link" href="/users/{$username}/topics">
                                {$.en ? 'Published' : 'Опубликованные'}
                                <sup class="badge badge-light">{$author.topics - $author.drafts}</sup>
                            </a>
                        </li>
                        <li class="nav-item">
                    <span class="nav-link active">
                        {$.en ? 'Drafts' : 'Черновики'}
                        <sup class="badge badge-light">{$author.drafts}</sup>
                    </span>
                        </li>
                    {else}
                        <li class="nav-item">
                    <span class="nav-link active">
                        {$.en ? 'Published' : 'Опубликованные'}
                        <sup class="badge">{$author.topics - $author.drafts}</sup>
                    </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/users/{$username}/topics/drafts">
                                {$.en ? 'Drafts' : 'Черновики'}
                                <sup class="badge badge-light">{$author.drafts}</sup>
                            </a>
                        </li>
                    {/if}
                </ul>
            </div>
        {/if}

        {if $subpage == 'drafts'}
            {var $res = $.App->runProcessor('community/topic/getlist', [
                'limit' => 10,
                'where' => ['comTopic.published' => false]
                'showSection' => true,
                'user' => $user.id,
            ])}
        {else}
            {var $res = $.App->runProcessor('community/topic/getlist', [
                'limit' => 10,
                'showSection' => true,
                'user' => $user.id,
            ])}
        {/if}

        {$res.results}
        {include 'file:chunks/_pagination.tpl' res=$res}
    </div>
</div>