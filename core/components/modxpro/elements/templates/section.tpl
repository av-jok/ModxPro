{extends 'file:templates/base.tpl'}

{block 'content'}
    {var $res = $.App->runProcessor('community/topic/getlist', [
        'where' => ['comTopic.parent' => $_modx->resource.id],
    ])}

    <h1 class="section-title">
        {$_modx->resource.pagetitle}
    </h1>
    <div class="buttons">
        <a href="" class="btn btn-outline-primary mb-3">
            {$.en ? 'Write a topic' : 'Написать заметку'}
        </a>
    </div>
    <div class="topics-list">
        {$res.results}

        {include 'file:chunks/_pagination.tpl' res=$res}
    </div>
{/block}