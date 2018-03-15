{foreach $results as $item}
    <li>
        <a href="/{$item.section_uri}/{$item.id}">{$item.pagetitle}</a>
    </li>
{/foreach}