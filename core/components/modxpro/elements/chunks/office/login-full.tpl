<div class="d-flex flex-wrap no-gutters auth full">
    <div class="col-lg-6 pr-lg-5 office-auth-login-wrapper">
        <h4>{'office_auth_login' | lexicon}</h4>

        <form action="office/login" method="post" id="office-auth-login" class="ajax-form no-background">
            <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                <label for="office-auth-login-username" class="col-lg-2 control-label">
                    {'office_auth_login_username' | lexicon}
                </label>
                <div class="col-lg-10">
                    <input type="text" name="username" class="form-control" id="office-auth-login-username"/>
                    <div class="help-block">
                        {'office_auth_login_username_desc' | lexicon}
                    </div>
                </div>
            </div>
            <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                <label for="office-auth-login-password" class="col-lg-2 control-label">
                    {'office_auth_login_password' | lexicon}
                </label>
                <div class="col-lg-10">
                    <input type="password" name="password" class="form-control" id="office-auth-login-password"/>
                    <div class="help-block">{'office_auth_login_password_desc' | lexicon}</div>
                </div>
            </div>
            <div class="buttons no-gutters">
                <div class="offset-lg-2 col-lg-10">
                    <button type="submit" class="btn btn-primary">{'office_auth_login_btn' | lexicon}</button>
                </div>
            </div>
        </form>
        {if $providers?}
            <div class="social mt-5 pr-0 pl-0">
                <label>{'office_auth_login_ha' | lexicon}</label>
                <div class="providers">{$providers}</div>
                <div class="help-block">{'office_auth_login_ha_desc' | lexicon}</div>
            </div>
        {/if}
        {if $error?}
            <div class="alert alert-danger mt-3">{$error}</div>
        {/if}
    </div>

    <div class="col-lg-6 pl-lg-5 office-auth-register-wrapper mt-5 mt-lg-0">
        <h4>{'office_auth_register' | lexicon}</h4>
        <form action="office/register" method="post" id="office-auth-register" class="ajax-form background">
            <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                <label for="office-auth-register-username" class="col-lg-2 control-label">
                    {'office_auth_register_username' | lexicon}
                </label>
                <div class="col-lg-10">
                    <input type="text" name="username" class="form-control" id="office-auth-register-username"/>
                    <div class="help-block">{'office_auth_register_username_desc' | lexicon}</div>
                </div>
            </div>
            <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                <label for="office-auth-register-password" class="col-lg-2 control-label">
                    {'office_auth_register_password' | lexicon}
                </label>
                <div class="col-lg-10">
                    <input type="password" name="password" class="form-control" id="office-auth-register-password"/>
                    <div class="help-block">{'office_auth_register_password_desc' | lexicon}</div>
                </div>
            </div>
            <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                <label for="office-auth-register-email" class="col-lg-2 control-label">
                    {'office_auth_register_email' | lexicon}
                </label>
                <div class="col-lg-10">
                    <input type="email" name="email" class="form-control" id="office-auth-register-email"/>
                    <div class="help-block">{'office_auth_register_email_desc' | lexicon}</div>
                </div>
            </div>
            <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                <label for="office-auth-register-fullname" class="col-lg-2 control-label">
                    {'office_auth_register_fullname' | lexicon}
                </label>
                <div class="col-lg-10">
                    <input type="text" name="fullname" class="form-control" id="office-auth-register-fullname"/>
                    <div class="help-block">{'office_auth_register_fullname_desc' | lexicon}</div>
                </div>
            </div>
            <div class="buttons no-gutters">
                <div class="offset-lg-2 col-lg-10">
                    <button type="submit" class="btn btn-danger">{'office_auth_register_btn' | lexicon}</button>
                </div>
            </div>
        </form>
    </div>
</div>