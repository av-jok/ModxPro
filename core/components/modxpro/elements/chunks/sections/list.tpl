{foreach $results as $item}
    <div class="topic-row">
        <h2 class="topic-title">
            <a href="/{$item.uri}">{$item.pagetitle}</a>
        </h2>

        <div class="topic-content">
            {$item.description}
            <ul class="last-topics">
                {var $res = $.App->runProcessor('community/topic/getlist', [
                    'limit' => 5,
                    'fastMode' => true,
                    'getPages' => false,
                    'where' => ['parent' => $item.id],
                    'tpl' => '@FILE chunks/sections/topics.tpl ',
                ])}
                {$res.results}
            </ul>
        </div>

        {include 'file:chunks/sections/_meta.tpl' item=$item}
    </div>
{/foreach}