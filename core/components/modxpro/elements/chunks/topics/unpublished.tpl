<div id="topic-page">
    <h1 class="topic-title">
        {$.en ? 'Access to publication is closed' : 'Доступ к публикации закрыт'}
    </h1>

    <div class="topic-content">
        {if $.en}
            <p>
                You are trying to open a note that is hidden in drafts (by the author himself or by the Administration).
            </p>
            <a href="/" class="btn btn-outline-primary mt-3">Back to start</a>
        {else}
            <p>
                Вы пытаетесь открыть заметку, которая скрыта в черновики (самим автором или Администрацией).
            </p>
            <a href="/" class="btn btn-outline-primary mt-3">Вернуться на главную</a>
        {/if}
    </div>
</div>