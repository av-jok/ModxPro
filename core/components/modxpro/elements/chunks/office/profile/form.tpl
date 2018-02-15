{var $buttons}
    <button type="submit" class="btn btn-primary">{$.en ? 'Save' : 'Сохранить'}</button>
    <a class="btn btn-danger" href="?action=auth/logout">
        {$.en ? 'Log out' : 'Выйти'} <i class="far fa-sign-out"></i>
    </a>
{/var}
<form action="" method="post" id="office-profile-form" enctype="multipart/form-data">
    <input type="hidden" name="action" value="office/profile"/>
    <div class="d-flex flex-wrap no-gutters auth full">
        <div class="col-lg-7 pr-lg-5 ">
            <div class="background">
                <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                    <label class="col-md-3 control-label" for="office_profile_username">
                        {'office_profile_username' | lexicon}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="username" value="{$username}" id="office_profile_username"
                               placeholder="{'office_profile_username' | lexicon}" class="form-control"/>
                        <div class="help-block desc">{'office_profile_username_desc' | lexicon}</div>
                    </div>
                </div>

                <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                    <label class="col-md-3 control-label" for="office_profile_fullname">
                        {'office_profile_fullname' | lexicon}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="fullname" value="{$fullname}" id="office_profile_fullname"
                               placeholder="{'office_profile_fullname' | lexicon}" class="form-control"/>
                        <div class="help-block desc">{'office_profile_fullname_desc' | lexicon}</div>
                    </div>
                </div>

                <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                    <label class="col-md-3 control-label" for="office_profile_email">
                        {'office_profile_email' | lexicon}
                    </label>
                    <div class="col-md-9">
                        <input type="text" name="email" value="{$email}" id="office_profile_email"
                               placeholder="{'office_profile_email' | lexicon}" class="form-control"/>
                        <div class="help-block desc">{'office_profile_email_desc' | lexicon}</div>
                    </div>
                </div>

                <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                    <label class="col-md-3 control-label" for="office_profile_password">
                        {'office_profile_password' | lexicon}
                    </label>
                    <div class="col-md-9">
                        <input type="password" name="specifiedpassword" value="" id="office_profile_password"
                               placeholder="********" class="form-control"/>
                        <div class="help-block desc">{'office_profile_specifiedpassword_desc' | lexicon}</div>
                    </div>
                </div>
                <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                    <div class="offset-md-3 col-md-9">
                        <input type="password" name="confirmpassword" value="" placeholder="********"
                               class="form-control"/>
                        <div class="help-block desc">{'office_profile_confirmpassword_desc' | lexicon}</div>
                    </div>
                </div>

                <div class="form-group d-flex flex-wrap no-gutters align-items-center avatar">
                    <label class="col-md-3 control-label" for="office_profile_photo_file">
                        {'office_profile_avatar' | lexicon}
                    </label>
                    <div class="col-md-9">
                        <label for="office_profile_photo_file">
                            <img src="{$_pls | avatar : 100}" id="office_profile_photo" width="100"
                                 srcset="{$_pls | avatar : 200} 2x" data-gravatar="{$gravatar}?s=100"/>
                        </label>
                        <a href="#" id="office_profile_photo_remove"{if !$photo} class="d-none"{/if}">
                            {'office_profile_avatar_remove' | lexicon}
                            <i class="far fa-times"></i>
                        </a>
                        <div class="help-block">{'office_profile_avatar_desc' | lexicon}</div>
                        <input type="hidden" name="photo" value="{$photo}"/>
                        <input type="file" name="newphoto" id="office_profile_photo_file" class="d-none"/>
                    </div>
                </div>

                <div class="buttons d-none d-lg-block offset-md-3">{$buttons}</div>

                {if $providers?}
                    <div class="social mt-5 pr-0 pl-0">
                        <label>{$.en ? 'Social services' : 'Социальные сети'}</label>
                        <div class="providers">{$providers}</div>
                    </div>
                {/if}
            </div>
        </div>

        <div class="col-lg-5 pl-lg-5 mt-5 mt-lg-0">
            <div class="no-background">
                {var $items = [
                'city' => $.en ? 'New York' : 'Москва',
                'website' => 'example.com',
                'mobilephone' => '79234679214'
                ]}
                {foreach $items as $item => $placeholder}
                    <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="office_profile_{$item}">
                            {('office_profile_' ~ $item) | lexicon}
                        </label>
                        <div class="col-md-8">
                            <input type="text" name="{$item}" value="{$_pls[$item]}" placeholder="{$placeholder}"
                                   class="form-control" id="office_profile_{$item}"/>
                        </div>
                    </div>
                {/foreach}

                <hr/>

                {var $items = ['github', 'twitter', 'vkontakte', 'telegram', 'skype']}
                {foreach $items as $item}
                    <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                        <label class="col-md-4 control-label" for="office_profile_{$item}">
                            {('office_profile_' ~ $item) | lexicon}
                        </label>
                        <div class="col-md-8">
                            <input type="text" name="{$item}" value="{$extended[$item]}"
                                   placeholder="username" class="form-control" id="office_profile_{$item}"/>
                        </div>
                    </div>
                {/foreach}

                <hr/>

                {var $items = ['work', 'feedback', 'usename']}
                {foreach $items as $item}
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="{$item}" value="1" class="custom-control-input"
                               id="office_profile_{$item}" {$_pls[$item] ? 'checked' : ''}/>
                        <label class="custom-control-label" for="office_profile_{$item}">
                            {('office_profile_' ~ $item) | lexicon}
                        </label>
                    </div>
                {/foreach}

                <hr/>

                <div class="form-group d-flex flex-wrap no-gutters align-items-center">
                    <label class="control-label" for="office_profile_comment">
                        {$.en ? 'About' : 'О себе'}
                    </label>
                    <textarea name="comment" class="form-control" id="office_profile_comment">{$comment}</textarea>
                </div>
            </div>
        </div>

        <div class="buttons col-12 d-lg-none">{$buttons}</div>
    </div>
</form>
{'<script type="text/javascript">requirejs(["app/profile"]);</script>' | htmlToBottom}