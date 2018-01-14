<div class="modal-dialog user-message">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{$.en ? 'To' : 'Кому'}: {$to}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="fal fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="user/message/submit" method="post" class="ajax-form">
                <input type="hidden" name="user" value="{$id}">

                <div class="form-group">
                    <div>
                        <input type="text" name="subject" class="form-control"
                               placeholder="{$.en ? 'Subject' : 'Тема письма'}"/>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <textarea type="text" name="body" class="form-control" rows="5"
                                  placeholder="{$.en ? 'Message' : 'Сообщение'}"></textarea>
                    </div>
                </div>

                <div class="alert alert-warning" role="alert">
                    {if $.en}
                        <p>You want to send an email to the user of our site and will not know his email, while he will not respond.</p>
                        <p>Be polite, try not to bother people in vain. If there will be complaints about spam, you can be disabled.</p>
                    {else}
                        <p>Вы хотите отправить письмо пользователю нашего сайта и не будете знать его email, пока он вам не ответит.</p>
                        <p>Будьте вежливы, старайтесь не беспокоить людей зря. Если будут жалобы на спам - вас могут отключить.</p>
                    {/if}
                </div>

                <div class="buttons justify-content-between">
                    <button type="submit" class="btn btn-success">{$.en ? 'Submit' : 'Отправить'}</button>
                    <button class="btn btn-secondary" data-dismiss="modal">{$.en ? 'Cancel' : 'Отмена'}</button>
                </div>
            </form>
        </div>
    </div>
</div>