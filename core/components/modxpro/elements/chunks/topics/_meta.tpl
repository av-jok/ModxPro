<div class="topic-meta d-flex flex-wrap no-gutters align-items-center">
    <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
        {if !$user}
            <div class="avatar">
                <a href="/users/{$item.usename ? $item.username : $item.createdby}">
                    <img src="{['photo' => $item.photo, 'email' => $item.email] | avatar : 48}" width="48"
                         srcset="{['photo' => $item.photo, 'email' => $item.email] | avatar : 96} 2x"/>
                </a>
            </div>
            <div class="ml-2 created">
                <div class="author">
                    <a href="/users/{$item.usename ? $item.username : $item.createdby}">{$item.fullname}</a>
                </div>
                <div class="date">{$item.publishedon | dateago}</div>
            </div>
        {else}
            <div class="date">
                <i class="far fa-calendar-alt"></i> {$item.publishedon | dateago}
            </div>
        {/if}
    </div>
    <div class="meta col-12 col-md-6 mt-3 mt-md-0 col-md-3 ml-md-auto d-flex justify-content-around justify-content-md-end">
        <div class="stars">
            <i class="far fa-star"></i> {$item.stars}
        </div>
        <div class="views ml-md-3">
            <i class="far fa-eye"></i> {$item.views}
        </div>
        <div class="comments ml-md-3">
            <i class="far fa-comment"></i> {$item.comments}
        </div>
        <div class="rating ml-md-5">
            <i class="far fa-arrow-up"></i>
            {if $item.rating > 0}
                <span class="text-success">+{$item.rating}</span>
            {elseif $item.rating < 0}
                <span class="text-danger">{$item.rating}</span>
            {else}
                <span>{$item.rating}</span>
            {/if}
            <i class="far fa-arrow-down"></i>
        </div>
    </div>
</div>