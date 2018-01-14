<header>
    <div class="service-panel">
        <div class="container">
            <ul class="links">
                <li><a href="{$.en ? 'https://en.modstore.pro' : 'https://modstore.pro'}" target="_top">Modstore</a></li>
                <li><a href="{$.en ? 'https://en.modhost.pro' : 'https://modhost.pro'}" target="_top">Modhost</a></li>
                <li><a href="{$.en ? 'https://docs.modx.pro/en/' : 'https://docs.modx.pro'}" target="_top">Docs</a></li>
                <li><strong>MODX.Pro</strong></li>
            </ul>

            <div class="social">
                <a href="https://t.me/modstore_pro" target="_blank">
                    <span class="fab fa-telegram"></span>
                </a>
                <a href="/chat/" target="_blank">
                    <span class="fab fa-slack"></span>
                </a>
                <a href="https://twitter.com/modstore_pro" target="_blank">
                    <span class="fab fa-twitter"></span>
                </a>
                <a href="https://www.facebook.com/modstore.pro/" target="_blank">
                    <span class="fab fa-facebook-f"></span>
                </a>
                <a href="https://vk.com/modxaddons" target="_blank">
                    <span class="fab fa-vk"></span>
                </a>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{'site_url' | config}">
                <img src="/assets/components/modxpro/img/logo.png"
                     srcset="/assets/components/modxpro/img/logo@2x.png 2x" title="" alt="{'site_name' | config}">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="navbar-nav ">
                    {'pdoMenu' | snippet : [
                        'parents' => 0,
                        'level' => 1,
                        'scheme' => 'uri',
                        'rowClass' => 'nav-item',
                        'outerTpl' => '@INLINE {{+wrapper}}',
                        'tpl' => '@INLINE <li{{+classes}}><a href="/{{+link}}" class="nav-link">{{+menutitle}}</a></li>'
                    ]}
                    <li class="ml-0 ml-md-auto languages nav-item">
                        {if $.en}
                            <a href="{$.switch_link}" class="language nav-link ru">
                                <img src="/assets/components/modxpro/img/lang-ru.png" srcset="/assets/components/modxpro/img/lang-ru@2x.png 2x">
                                <span>Русский</span>
                            </a>
                        {else}
                            <a href="{$.switch_link}" class="language nav-link en">
                                <img src="/assets/components/modxpro/img/lang-en.png" srcset="/assets/components/modxpro/img/lang-en@2x.png 2x">
                                <span>English</span>
                            </a>
                        {/if}
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>