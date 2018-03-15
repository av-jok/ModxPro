{foreach $results as $item}
    <div class="comment-row">
        <div class="d-flex no-gutters align-items-center">
            <div class="avatar">
                <a href="/users/{$item.usename ? $item.username : $item.createdby}">
                    <img src="{['photo' => $item.photo, 'email' => $item.email] | avatar : 25}" width="25"
                         srcset="{['photo' => $item.photo, 'email' => $item.email] | avatar : 50} 2x"/>
                </a>
            </div>
            <div class="ml-2 created">
                <div class="author">
                    <a href="/users/{$item.usename ? $item.username : $item.createdby}">{$item.fullname}</a>
                </div>
                <div class="date">{$item.createdon | dateago}</div>
            </div>
        </div>
        <a href="/{$item.uri}/{$item.topic}#comment-{$item.id}" class="link">
            <div class="text">
                {$item.text | strip_tags | escape | truncate : 150}
            </div>
        </a>
        <div class="d-flex align-items-center meta">
            <i class="far fa-file mr-1"></i>
            {$item.topic_title}
            {if $item.uri != 'work'}
                <i class="far fa-comment ml-3"></i><span class="ml-1">{$item.comments}</span>
            {/if}
        </div>
    </div>
{/foreach}