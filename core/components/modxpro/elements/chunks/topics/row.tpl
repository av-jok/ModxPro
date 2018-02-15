<div class="topic-row">
    {if $scriptProperties.showSection}
        <div class="section">
            <a href="/{$section_uri}">
                <i class="fal fa-folder-open"></i> {$section_pagetitle} /
            </a>
        </div>
    {/if}
    <h2 class="topic-title">
        <a href="/{$section_uri}/{$id}">{$pagetitle}</a>
    </h2>

    <div class="topic-content">
        {$introtext | prism}
    </div>

    {include 'file:chunks/topics/_meta.tpl'}
</div>