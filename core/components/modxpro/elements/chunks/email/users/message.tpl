{extends 'file:chunks/email/default.tpl'}

{block 'content'}
    {var $site_url = ('site_url' | config)}
    {var $username = ($profile.usename ? $user.username : $user.id)}
    {if $.en}
        <p>
            <a href="{$site_url}users/{$username}">{$profile.fullname}</a>
            has sent you the message with <a href="{$site_url}">modx.pro</a>:
        </p>
        <pre style="background-color:#efefef;">{$text}</pre>
        <p>
            While you will not answer to <a href="mailto:{$profile.email}">{$profile.email}</a>,
            your email will kept in secret.
        </p>
        <p>
            If you do not want to receive these messages any more, disable them in your profile settings.
        </p>
    {else}
        <p>
            <a href="{$site_url}users/{$username}">{$profile.fullname}</a>
            отправил вам сообщение через форму обратной связи на <a href="https://modx.pro">modx.pro</a>:
        </p>
        <pre style="background-color:#efefef;">{$text}</pre>
        <p>
            Пока вы не ответите по адресу <a href="mailto:{$profile.email}">{$profile.email}</a>,
            ваш email останется в тайне.
        </p>
        <p>
            Если вы не хотите больше получать такие сообщения - отключите их в настройках своего профиля.
        </p>
    {/if}
{/block}