<header>
    {include 'file:chunks/_service-panel.tpl'}

    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand order-1" href="/">
                <img src="/assets/components/modxpro/img/logo.png" title="" alt="{'http_host' | config}"
                     srcset="/assets/components/modxpro/img/logo@2x.png 2x">
            </a>

            <div class="collapse navbar-collapse order-md-2 order-10" id="navbar-menu">
                <ul class="navbar-nav">
                    {'pdoMenu' | snippet : [
                    'parents' => 0,
                    'level' => 1,
                    'scheme' => 'uri',
                    'rowClass' => 'nav-item',
                    'outerTpl' => '@INLINE {{+wrapper}}',
                    'tpl' => '@INLINE <li{{+classes}}><a href="/{{+link}}" class="nav-link">{{+menutitle}}</a></li>'
                    ]}
                    <div class="languages ml-md-auto mr-md-3">
                        {if $.en}
                            <a href="{$.switch_link}" class="language nav-link ru">
                                <img src="/assets/components/modxpro/img/lang-ru.png" srcset="/assets/components/modxpro/img/lang-ru@2x.png 2x">
                                <span class="d-md-none">Русский</span>
                            </a>
                        {else}
                            <a href="{$.switch_link}" class="language nav-link en">
                                <img src="/assets/components/modxpro/img/lang-en.png" srcset="/assets/components/modxpro/img/lang-en@2x.png 2x">
                                <span class="d-md-none">English</span>
                            </a>
                        {/if}
                    </div>
                </ul>
            </div>
            <div class="user login order-3 ml-auto">
                {'!OfficeAuth@Auth' | snippet : [
                'loginContext' => $_modx->context.key,
                'tplLogin' => '@FILE chunks/office/login.tpl',
                'tplLogout' => '@FILE chunks/office/logout.tpl',
                ]}
            </div>
            <button class="navbar-hide btn btn-outline-secondary order-4 ml-3 d-md-none collapsed"
                    data-toggle="collapse" data-target="#navbar-menu"></button>
        </div>
    </nav>
</header>