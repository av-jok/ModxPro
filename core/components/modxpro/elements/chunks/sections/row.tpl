<div class="topic-row">
    <h2 class="topic-title">
        <a href="/{$uri}">{$pagetitle}</a>
    </h2>

    <div class="topic-content">
        {$description}
        <ul class="last-topics">
            {'@FILE snippets/get_topics.php' | snippet : [
                'includeSections' => $uri,
                'limit' => 5,
                'tpl' => '@INLINE <li><a href="/' ~ $uri ~ '/{$id}">{$pagetitle}</a></li>',
                'select' => ['comTopic' => 'id,pagetitle'],
            ]}
        </ul>
    </div>

    {include 'file:chunks/sections/_meta.tpl'}
</div>