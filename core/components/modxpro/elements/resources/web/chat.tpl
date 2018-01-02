<div class="modx-slack">
    <div class="intro">
        Предлагаем вам присоединиться к тематическим MODX-каналам в сервисе Slack для общения в реальном времени.<br/>
    </div>
    <img src="/inc/img/chat/chat_ru2.png" srcset="/inc/img/chat/chat_ru2@2x.png 2x">
    <div class="row">
        <div class="col-lg-6">
            <h4>Русскоговорящее сообщество</h4>
            <div class="chat">
                <img src="/inc/img/chat/chat_modstore.png" srcset="/inc/img/chat/chat_modstore@2x.png 2x">
                <p class="indent">
                    Обсуждение тем, связанных с работой магазина дополнений Modstore и хостинга Modhost.<br>
                    Есть <strong>закрытая комната</strong> для авторов дополнений.
                </p>
                <p class="indent">
                    {$_modx->runSnippet('!AjaxForm', [
                    'snippet' => 'Slack',
                    'method' => 'invite',
                    'form' => '@INLINE
                        <form action="">
                            <div class="input-group">
                                <input placeholder="Ваш email" type="email" name="email" class="form-control" value="" />
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-thumbs-o-up "></i></button>
                                </span>
                            </div>
                            <small>Получите приглашение, если вы еще не в команде.</small>
                        </form>
                    ',
                    ])}
                </p>
                <p class="indent">
                    {$_modx->runSnippet('!AjaxSnippet', [
                    'snippet' => 'Slack',
                    'method' => 'users',
                    'tpl' => '@INLINE <br>
                        <a href="https://modstore.slack.com/messages/general/" target="_blank">Нас уже <span class="total">{$total}</span> {$total | declension:"человек|человека|человек"}.</a>
                        <br>
                        Прямо сейчас на связи &mdash; <span class="active">{$active}</span>.
                    '
                    ])}
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <h4>Международное сообщество</h4>
            <div class="chat">
                <img src="/inc/img/chat/chat_modx.png" srcset="/inc/img/chat/chat_modx@2x.png 2x">
                <p class="indent">
                    Чтобы начать работу, вам нужно получить приглашение через <strong>modx.org</strong>,
                    после чего вы сможете поболтать со всем сообществом.
                </p>
                <p class="indent">
                    <a href="http://modx.org" target="_blank" class="btn btn-primary">Присоединиться!</a>
                </p>
                <p class="small-indent">
                    <img src="/inc/img/lang/ru.png">
                </p>
                <p>Для русскоговорящих разработчиков<br> есть <a href="https://modxcommunity.slack.com/messages/russian/" target="_blank">отдельная комната</a>.</p>
            </div>
        </div>
    </div>
</div>