{foreach $results as $item}
    <div class="topic-row">
        {if $showSection}
            <div class="section">
                <a href="/{$item.section_uri}">
                    <i class="fal fa-folder-open"></i> {$item.section_pagetitle} /
                </a>
            </div>
        {/if}
        <h2 class="topic-title">
            <a href="/{$item.section_uri}/{$item.id}">{$item.pagetitle}</a>
        </h2>

        <div class="topic-content">
            {$item.introtext | prism}
        </div>

        {include 'file:chunks/topics/_meta.tpl' item=$item}
    </div>
{/foreach}