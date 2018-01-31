<header>
    {include 'file:chunks/_service-panel.tpl'}

    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand"
               href="https://{('http_host' | config) | preg_replace : '#^id\.#' : ($.en ? 'en.' : '')}">
                <img src="/assets/components/modxpro/img/logo.png" title="" alt="{'http_host' | config}"
                     srcset="/assets/components/modxpro/img/logo@2x.png 2x">
            </a>
            {if $_modx->isAuthenticated()}
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="/me" class="nav-link">{$.en ? 'Profile' : 'Профиль'}</a></li>
                </ul>
            {/if}
            <div class="languages ml-auto">
                {if $.en}
                    <a href="{$.switch_link}" class="language ru" rel="nofollow">
                        <img src="/assets/components/modxpro/img/lang-ru.png"
                             srcset="/assets/components/modxpro/img/lang-ru@2x.png 2x">
                    </a>
                {else}
                    <a href="{$.switch_link}" class="language en" rel="nofollow">
                        <img src="/assets/components/modxpro/img/lang-en.png"
                             srcset="/assets/components/modxpro/img/lang-en@2x.png 2x">
                    </a>
                {/if}
            </div>
        </div>
    </nav>
</header>