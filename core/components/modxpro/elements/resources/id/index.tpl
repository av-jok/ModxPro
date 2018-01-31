{if $_modx->isAuthenticated()}
    {'!OfficeProfile@Profile' | snippet : [
    'tplProfile' => '@FILE chunks/office/profile/form.tpl',
    'avatarParams' => '{"w":200,"h":200,"zc":1,"bg":"ffffff","f":"jpg"}',
    'requiredFields' => 'username,email,fullname',
    'profileFields' => 'username:50,email:50,fullname:50,specifiedpassword,confirmpassword,city:50,mobilephone:15,comment,website:100,photo,work,feedback,usename,extended[twitter],extended[vkontakte],extended[github],extended[skype],extended[telegram]',
    ]}
{else}
    {'!OfficeAuth@Auth' | snippet : [
    'loginContext' => $_modx->context.key,
    'addContexts' => 'web,en,id',
    'tplLogin' => '@FILE chunks/office/login-full.tpl',
    ]}
{/if}