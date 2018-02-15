{extends 'file:templates/base.tpl'}

{block 'content'}
    <h1 class="section-title">
        {$_modx->resource.pagetitle}
    </h1>
    <div class="buttons">
        <a href="" class="btn btn-outline-primary">
            {$.en ? 'Write a topic' : 'Написать заметку'}
        </a>
    </div>
    <div id="topics-list">
        {'@FILE snippets/get_topics.php' | snippet : [
            'includeSections' => $_modx->resource.alias,
        ]}
    </div>
{/block}