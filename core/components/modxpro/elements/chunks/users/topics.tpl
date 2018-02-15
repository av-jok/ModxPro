{include 'file:chunks/users/_header.tpl' profile=$profile author=$author}

<div class="user-content">
    <div id="topics-list">
        {'@FILE snippets/get_topics.php' | snippet : [
            'where' => ['createdby' => $user.id],
        ]}
    </div>
</div>