{$_modx->lexicon->load('pdotools:pdoarchive')}

{foreach $results as $year => $months}
    {foreach $months as $month => $days}
        <h4 class="year">{('pdoarchive_month_' ~ $month) | lexicon} {$year}</h4>
        <ul class="month">
        {foreach $days as $day => $topics}
            <li class="day">
                <h5 class="mt-3">{$day} <sup class="badge badge-light">({count($topics)})</sup></h5>
                <ul class="topics">
                {foreach $topics as $topic}
                    <li class="topic-archive mt-2">
                        <div>
                            {$topic.createdon | date : 'H:i'}
                            <a href="{$topic.section_uri}/{$topic.id}" class="ml-1">{$topic.pagetitle}</a>
                        </div>
                        <div class="small mt-1">
                            <a href="{$topic.section_uri}"><i class="far fa-folder-open"></i> {$topic.section_pagetitle}</a>
                            <i class="far fa-comment ml-2"></i> {$topic.comments}
                            <i class="far fa-eye ml-2"></i> {$topic.views}
                        </div>
                    </li>
                {/foreach}
                </ul>
            </li>
        {/foreach}
        </ul>
    {/foreach}
{/foreach}