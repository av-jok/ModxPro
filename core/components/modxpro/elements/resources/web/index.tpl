<div id="topics-list">
    {include 'file:chunks/_banner.tpl'}

    {'@FILE snippets/get_topics.php' | snippet : [
        'excludeSections' => ['help', 'work'],
        'showSection' => true,
    ]}
</div>