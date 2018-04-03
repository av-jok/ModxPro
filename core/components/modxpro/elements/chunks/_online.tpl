{var $active = $.cookie['online-tabs'] ?: 'comments'}
{var $res = $.App->runProcessor('community/online/' ~ $active, ['limit' => 10])}
<ul class="nav nav-tabs" id="online-tabs">
    <li class="nav-item">
        <a class="nav-link{if $active == 'comments'} active{/if}" data-toggle="tab" href="#tab-comments">
            {$.en ? 'Comments' : 'Комментарии'}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{if $active == 'topics'} active{/if}" data-toggle="tab" href="#tab-topics">
            {$.en ? 'Topics' : 'Заметки'}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{if $active == 'jobs'} active{/if}" data-toggle="tab" href="#tab-jobs">
            {$.en ? 'Job' : 'Работа'}
        </a>
    </li>
</ul>
<div class="tab-content mt-3" id="online-content">
    <div class="comments-latest tab-pane fade{if $active == 'comments'} show active{/if}" id="tab-comments">
        {if $active == 'comments'}
            {$res.results}
        {/if}
    </div>
    <div class="topics-latest tab-pane fade{if $active == 'topics'} show active{/if}" id="tab-topics">
        {if $active == 'topics'}
            {$res.results}
        {/if}
    </div>
    <div class="topics-latest tab-pane fade{if $active == 'jobs'} show active{/if}" id="tab-jobs">
        {if $active == 'jobs'}
            {$res.results}
        {/if}
    </div>
</div>