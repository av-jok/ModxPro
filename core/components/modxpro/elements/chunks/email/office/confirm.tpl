{extends 'file:chunks/email/default.tpl'}
{block 'content'}
    {if $.en}
        <h1>The confirmation link</h1>
        <p>You have changed email in yours profile on the website <a href="{'site_url' | config}">modstore.pro</a>.</p>
        <p>To confirm the new address you need <a href="{$link}">follow the link</a>.</p>
        <small>If you know nothing about it, just delete this letter.</small>
    {else}
        <h1>Ссылка для подтверждения</h1>
        <p>Вы изменили email в своём профиле на сайте <a href="{'site_url' | config}">modstore.pro</a>.</p>
        <p>Для подтверждения нового адреса вам нужно <a href="{$link}">пройти по ссылке</a>.</p>
        <small>Если вы не знаете, о чем идет речь, просто удалите это письмо.</small>
    {/if}
{/block}