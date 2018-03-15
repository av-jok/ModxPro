{foreach $results as $item}
    <div class="topic-row">
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
        <a href="/{$item.section_uri}/{$item.id}" class="link">
            <div class="text">
                <div>{$item.pagetitle}</div>
                {$item.introtext | strip_tags | truncate : 150}
            </div>
        </a>
        <div class="d-flex no-gutters align-items-center meta">
            {if $item.section_uri == 'work'}
                <i class="far fa-eye"></i><span class="ml-1">{$item.views}</span>
            {else}
                <a href="/{$item.section_uri}">
                    <i class="far fa-folder-open mr-1"></i>
                    {$item.section_pagetitle}
                </a>
                <i class="far fa-eye ml-3"></i><span class="ml-1">{$item.views}</span>
                <a href="/{$item.section_uri}/{$item.id}#comments">
                    <i class="far fa-comment ml-3"></i><span class="ml-1">{$item.comments}</span>
                </a>
            {/if}
        </div>
    </div>
{/foreach}