{extends 'file:chunks/email/default.tpl'}
{block 'style'}
    .password {
    border: 1px solid #000;
    text-align: center;
    margin: 30px 0;
    padding: 50px 0;
    font-size: 16px;
    }
{/block}
{block 'content'}
    {if $.en}
        <h1>The activation link</h1>
        <p>You (or someone else) has requested a password reset for your account <a href="mailto:{$email}">{$email}</a>
            at <a href="{'site_url' | config}">modstore.pro</a>.</p>
        <p>If this was you, then you need to <a href="{$link}">follow this link</a>
            in order to activate the new password.</p>
        <div class="password">
            Your password after activation: <b>{$password}</b>
        </div>
        <small>Don't have a clue what this email is about? Just delete it.</small>
    {else}
        <h1>Ссылка для активации</h1>
        <p>Вы (или кто-то другой) запросил сброс пароля для вашей учётной записи <a href="mailto:{$email}">{$email}</a>
            на сайте <a href="{'site_url' | config}">modstore.pro</a>.</p>
        <p>Если это действительно были вы, то вам нужно <a href="{$link}">пройти по ссылке</a>,
            для активации нового пароля.</p>
        <div class="password">
            Ваш пароль после активации: <b>{$password}</b>
        </div>
        <small>Если вы не знаете, о чем идет речь, просто удалите это письмо.</small>
    {/if}
{/block}