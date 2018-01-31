<div class="container text-center col-lg-4 col-md-6 m-auto">
    {if $logo}
        <img src="{$logo}" class="mb-3" width="125">
    {/if}
    <form method="post">
        {if $.en}
            <h5>Do you want to authorize with your profile on site <b>{$title}</b>?</h5>
        {else}
            <h5>Вы хотите авторизоваться с помощью вашего аккаунта на сайте <b>{$title}</b>?</h5>
        {/if}
        {if $description}
            <div class="alert">{$description}</div>
        {/if}
        <div class="buttons justify-content-around mb-3">
            <button class="btn btn-primary" type="submit" name="agree"
                    value="yes">{$.en ? 'Yes, I agree' : 'Да, хочу'}</button>
            <button class="btn btn-outline-secondary" type="submit" name="agree"
                    value="no">{$.en ? 'No, cancel' : 'Нет, отмена'}</button>
        </div>
        {if !($title | preg_match : '#\bmod(x|host|store)\b#')}
            <div class="alert alert-warning">
                {$.en ? 'Please note: our community is not related to' : 'Обратите внимание: наше сообщество не имеет отношения к'}
                <b>{$title}</b>!
            </div>
        {/if}
    </form>
</div>
