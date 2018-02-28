{foreach $results as $item}
    <li>
        <a href="/' ~ $item.uri ~ '/{$item.id}">{$item.pagetitle}</a>
    </li>
{/foreach}