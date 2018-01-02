<div class="modx-slack">
    <div class="intro">
        We encourage you to join the MODX-community on Slack for real-time communication<br/>
    </div>
    <img src="/inc/img/chat/chat_en.png" srcset="/inc/img/chat/chat_en@2x.png 2x">
    <div class="row">
        <div class="col-lg-6">
            <h4>International Community</h4>
            <div class="chat">
                <img src="/inc/img/chat/chat_modx.png" srcset="/inc/img/chat/chat_modx@2x.png 2x">
                <p class="indent">
                    To get started, you need to get an invite from <strong>modx.org</strong>.
                    Once invited you will be able to chat with the entire community.
                </p>
                <p class="indent">
                    <a href="http://modx.org" target="_blank" class="btn btn-primary">Join right now!</a>
                </p>
                <p class="small-indent">
                    <img src="/inc/img/lang/ru.png">
                </p>
                <p>
                    There is <a href="https://modxcommunity.slack.com/messages/russian/" target="_blank">a separate room</a> for Russian-speaking developers.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <h4>Russian Community</h4>
            <div class="chat">
                <img src="/inc/img/chat/chat_modstore.png" srcset="/inc/img/chat/chat_modstore@2x.png 2x">
                <p class="indent">
                    Discussion topics related to the work of Modstore and Modhost.
                    There is also <strong>a private room</strong> for component authors.
                </p>
                <p class="indent">
                    {*$_modx->runSnippet('!AjaxForm', [
                    'snippet' => 'Slack',
                    'method' => 'invite',
                    'form' => '@INLINE
                        <form action="">
                            <div class="input-group">
                                <input placeholder="Your email" type="email" name="email" class="form-control" value="" />
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-thumbs-o-up "></i></button>
                                </span>
                            </div>
                            <small>Get your invitation if you are not already in the team.</small>
                        </form>
                    ',
                    ])*}
                </p>
                <p class="indent">
                    {*$_modx->runSnippet('!AjaxSnippet', [
                    'snippet' => 'Slack',
                    'method' => 'users',
                    'tpl' => '@INLINE <br>
                        <a href="https://modstore.slack.com/messages/general/" target="_blank">We have <span class="total">{$total}</span> registered members.</a>
                        <br>
                        Online right now &mdash; <span class="active">{$active}</span>.
                    '
                    ])*}
                </p>
            </div>
        </div>
    </div>
</div>