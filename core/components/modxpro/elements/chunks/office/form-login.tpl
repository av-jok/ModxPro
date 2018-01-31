<div class="modal-dialog auth" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <ul class="nav">
                <li class="nav-item">
                    <a href="#auth/login" class="nav-link" data-target="#auth-login">
                        {$.en ? 'Login' : 'Авторизация'}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#auth/reg" class="nav-link" data-target="#auth-reg">
                        {$.en ? 'Register' : 'Регистрация'}
                    </a>
                </li>
                <li class="nav-item d-none">
                    <a href="#auth/reset" class="nav-link" data-target="#auth-reset">
                        {$.en ? 'Reset password' : 'Сброс пароля'}
                    </a>
                </li>
            </ul>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="fal fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="tab-content">
                <div id="auth-login" class="tab-pane">
                    <form action="office/login" method="post" id="office-auth-form" class="ajax-form">
                        <div class="d-flex flex-wrap">
                            <div class="column">
                                <div class="form-group">
                                    <input type="text" name="username"
                                           placeholder="{$.en ? 'Login' : 'Логин'}">
                                </div>
                            </div>
                            <div class="column">
                                <div class="form-group">
                                    <input type="password" name="password"
                                           placeholder="{$.en ? 'Password' : 'Пароль'}">
                                </div>
                            </div>
                        </div>
                        <div class="buttons">
                            <button class="btn btn-primary" type="submit">{$.en ? 'Login' : 'Войти'}</button>
                            <a href="#auth/reset" class="btn">
                                {$.en ? 'Reset password' : 'Сброс пароля'}
                            </a>
                        </div>
                    </form>
                    <div class="social">
                        <h4>{$.en ? 'Login via a social network' : 'Войти через социальную сеть'}</h4>
                        <div class="providers">
                            {$providers}
                        </div>
                        <div class="notice">
                            {if $.en}
                                Already registered? Tie your social networks in your account and log in without a password!
                            {else}
                                Уже зарегистрированы? Подключите соц. сеть в своем кабинете и заходите без пароля!
                            {/if}
                        </div>
                    </div>
                </div>
                <div id="auth-reg" class="tab-pane">
                    <form action="office/register" method="post" id="office-reg-form" class="ajax-form">
                        <div class="d-flex flex-wrap no-gutters align-items-center">
                            <div class="col-md-5 pr-md-2">
                                <div class="form-group">
                                    <input type="text" name="username" placeholder="{$.en ? 'Login' : 'Логин'}"
                                           autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password"
                                           placeholder="{$.en ? 'Password' : 'Пароль'}"
                                           autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Email"
                                           autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="fullname" placeholder="{$.en ? 'Full name' : 'Ваше имя'}"
                                           autocomplete="new-password">
                                </div>
                            </div>
                            <div class="col-md-7 pl-md-2 mt-2 mt-md-0">
                                <div class="p-2">
                                {if $.en}
                                    You can leave the "Password" field empty. Then it will be generated automatically
                                    and will come to your e-mail.<br><br>
                                    Later you can change it in your account.
                                {else}
                                    Вы можете оставить поле "Пароль" пустым. Тогда он будет сгенерирован автоматически
                                    и придёт вам на почту.<br><br>
                                    Позже вы сможете сменить его в личном кабинете.
                                {/if}
                                </div>
                            </div>
                        </div>
                        <div class="buttons">
                            <button class="btn btn-primary" type="submit">{$.en ? 'Submit' : 'Отправить'}</button>
                            <a href="#auth/login" class="btn btn-outline-secondary">{$.en ? 'Back' : 'Вернуться'}</a>
                        </div>
                    </form>
                </div>
                <div id="auth-reset" class="tab-pane">
                    <form action="office/reset" method="post" id="office-reset-form" class="ajax-form">
                        <h4 class="d-md-none">{$.en ? 'Reset password' : 'Сброс пароля'}</h4>
                        <div class="mt-md-1 mb-3">
                            {if $.en}
                                If you do not remember your password, enter your email address and you will receive a
                                new, along with an activation link.
                            {else}
                                Если вы не помните свой пароль, то введите ваш email и вы получите новый,
                                вместе со ссылкой на активацию.
                            {/if}
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email"
                                   autocomplete="new-password">
                        </div>
                        <div class="buttons">
                            <button class="btn btn-primary" type="submit">{$.en ? 'Submit' : 'Отправить'}</button>
                            <a href="#auth/login" class="btn btn-outline-secondary">{$.en ? 'Back' : 'Вернуться'}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>