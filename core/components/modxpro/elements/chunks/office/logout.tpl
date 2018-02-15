{var $profile = '/users/' ~ ($_modx->user.usename ? $_modx->user.username : $_modx->user.id)}
<div class="btn-group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        <span class="user-name d-none d-lg-inline">
            {$_modx->user.fullname}
            <span class="far fa-chevron-down"></span>
        </span>
        <span class="user-icon d-lg-none"><i class="fa fa-user"></i></span>
    </button>
    <div class="dropdown-menu user-menu">
        <div class="header">
            <a href="{$profile}" class="avatar">
                <img src="{$_modx->user | avatar : 80}" srcset="{$_modx->user | avatar : 160} 2x" width="80" />
            </a>
            <div class="wrapper">
                <div class="email">{$email}</div>
                <div>
                    <a href="https://id.{'http_host' | config | preg_replace : '#^en\.#' : ''}" class="profile">
                        {$.en ? 'Settings' : 'Настройки'}
                    </a>
                </div>
            </div>
        </div>
        <div class="links">
            <a href="/topic">{$.en ? 'Make a topic' : 'Написать заметку'}</a>
            <a href="{$profile}">{$.en ? 'My profile' : 'Мой профиль'}</a>
            <div class="dropdown-divider"></div>
            <a href="?action=auth/logout" class="logout" >{$.en ? 'Log out' : 'Выйти'}</a>
        </div>
        {*
        <div class="authorized">
            {if $authorized?}
                {foreach $authorized as $id => $user}
                    <div class="item">
                        <a href="?action=auth/change&user_id={$id}" class="item">
                            <img src="//gravatar.com/avatar/{$user.email | lower | md5}?s=60&d=mm" class="avatar">
                            <div class="d-flex flex-column">
                                <div class="name">{$user.fullname}</div>
                                <div class="email">{$user.email}</div>
                            </div>
                        </a>
                        <a href="?action=auth/logout&user_id={$id}" class="ml-auto"><i class="fa fa-sign-out"></i></a>
                    </div>
                {/foreach}
            {/if}
            <a href="#auth/add" class="item">
                <div class="avatar"><i class="fa fa-plus-circle"></i></div>
                <div class="name">{$.en ? 'Add account' : 'Добавить аккаунт'}</div>
            </a>
        </div>
        *}
    </div>
</div>
