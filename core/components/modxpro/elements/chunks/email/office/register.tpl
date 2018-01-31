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
        <h1>The authorization link</h1>
        <p>You have successfully registered at <a href="{'site_url' | config}">modstore.pro</a> using email
            <a href="mailto:{$email}">{$email}</a>.</p>
        <p>Now you need <a href="{$link}">follow the link</a> to activate your account.</p>
        {if $password?}
            <div class="password">
                Your password is: <b>{$password}</b>
            </div>
            <small>Do not forget to change it in your account!</small>
        {else}
            <small>If you know nothing about it, just delete this letter.</small>
        {/if}
    {else}
        <h1>Ссылка для входа</h1>
        <p>Вы успешно зарегистрировались на сайте <a href="{'site_url' | config}">modstore.pro</a>, указав email
            <a href="mailto:{$email}">{$email}</a>.</p>
        <p>Теперь вам нужно <a href="{$link}">пройти по ссылке</a>, чтобы активировать учётную запись.</p>
        {if $password?}
            <div class="password">
                Ваш пароль: <b>{$password}</b>
            </div>
            <small>Не забудьте изменить его в личном кабинете!</small>
        {else}
            <small>Если вы не знаете, о чем идёт речь, просто удалите это письмо.</small>
        {/if}
    {/if}
{/block}