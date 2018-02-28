{'<script type="text/javascript">requirejs(["app","app/user"]);</script>' | htmlToBottom}

{var $username = $profile.usename ? $user.username : $user.id}
<div class="user-header{if $profile.blocked} blocked{/if}" data-username="{$username}">
    <div class="avatar pr-md-3">
        <img src="{$profile | avatar : 64}" srcset="{$profile | avatar : 128} 2x" width="64" />
    </div>
    <div class="name pt-2 pt-md-0">
        <h1>{$profile.fullname}</h1>
        <small>
            {$.en ? 'Since' : 'С нами с'}
            {$author.createdon | dateago : json_encode(['dateFormat' => 'd F Y'])}
        </small>
    </div>
    <div class="message pt-2 pt-md-0 ml-md-auto">
        {if $profile.blocked}
            <button class="btn btn-danger" disabled>
                <i class="fal fa-ban"></i> {$.en ? 'User is blocked' : 'Юзер заблокирован'}
            </button>
        {elseif $profile.feedback}
            {if $_modx->isAuthenticated()}
                <a href="#message" class="btn btn-success">
                    <i class="fal fa-at"></i> {$.en ? 'Write a message' : 'Написать сообщение'}
                </a>
            {else}
                <a href="#auth/login" class="btn btn-secondary">
                    <i class="fal fa-at"></i> {$.en ? 'Write a message' : 'Написать сообщение'}
                </a>
            {/if}
        {else}
            <button class="btn btn-secondary" disabled>
                <i class="fal fa-power-off"></i> {$.en ? 'Messages are disabled' : 'Сообщения отключены'}
            </button>
        {/if}
    </div>
</div>

{var $link = '/users/' ~ $username}
<div class="user-tabs">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="{$link}" class="nav-link{if $tab == 'info'} active{/if}">
                {'user_info' | lexicon}
            </a>
        </li>
        <li class="nav-item">
            <a href="{$link}/topics" class="nav-link{if $tab == 'topics'} active{/if}">
                {'user_topics' | lexicon}
                <sup class="badge badge-light">{number_format($author.topics, 0, ',', ' ')}</sup>
            </a>
        </li>
        <li class="nav-item">
            <a href="{$link}/comments" class="nav-link{if $tab == 'comments'} active{/if}">
                {'user_comments' | lexicon}
                <sup class="badge badge-light">{number_format($author.comments, 0, ',', ' ')}</sup>
            </a>
        </li>
        <li class="nav-item">
            <a href="{$link}/favorites" class="nav-link{if $tab == 'favorites'} active{/if}">
                {'user_favorites' | lexicon}
                <sup class="badge badge-light">{number_format($author.favorites, 0, ',', ' ')}</sup>
            </a>
        </li>
    </ul>
</div>

