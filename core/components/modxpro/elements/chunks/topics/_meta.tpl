<div class="topic-meta d-flex flex-wrap no-gutters align-items-center">
    <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
        <div class="avatar">
            <a href="/users/{$usename ? $username : $createdby}">
                <img src="{['photo' => $photo, 'email' => $email] | avatar : 48}" width="48"
                     srcset="{['photo' => $photo, 'email' => $email] | avatar : 96} 2x"/>
            </a>
        </div>
        <div class="ml-2 created">
            <div class="author">
                <a href="/users/{$usename ? $username : $createdby}">{$fullname}</a>
            </div>
            <div class="date">{$publishedon | dateago}</div>
        </div>
    </div>
    <div class="meta col-12 col-md-6 mt-3 mt-md-0 col-md-3 ml-md-auto d-flex justify-content-around justify-content-md-end">
        <div class="stars">
            <i class="far fa-star"></i> {$stars}
        </div>
        <div class="views ml-md-3">
            <i class="far fa-eye"></i> {$views}
        </div>
        <div class="comments ml-md-3">
            <i class="far fa-comment"></i> {$comments}
        </div>
        <div class="rating ml-md-5">
            <i class="far fa-arrow-up"></i>
            {if $rating > 0}
                <span class="text-success">+{$rating}</span>
            {elseif $rating < 0}
                <span class="text-danger">{$rating}</span>
            {else}
                <span>{$rating}</span>
            {/if}
            <i class="far fa-arrow-down"></i>
        </div>
    </div>
</div>