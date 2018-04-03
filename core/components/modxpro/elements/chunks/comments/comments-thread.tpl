{if $item.deleted}
    <div class="comment-row deleted" id="comment-{$item.id}">
        <div class="comment-wrapper">
            <div class="comment-text">
                {$.en ? 'This comment was deleted' : 'Это сообщение было удалено'}
            </div>
        </div>
    </div>
{else}
    <div class="comment-row{if $item.createdby == $thread.createdby} author{/if}{if $seen && $item.createdon > $seen} unseen{/if}" id="comment-{$item.id}">
        <div class="comment-wrapper">
            <div class="comment-dot-wrapper">
                <div class="comment-dot"></div>
            </div>
            <div class="comment-meta d-flex flex-wrap no-gutters align-items-center item-data" data-id="{$item.id}" data-type="comment">
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="avatar">
                        <a href="/users/{$item.usename ? $item.username : $item.createdby}">
                            <img src="{['photo' => $item.photo, 'email' => $item.email] | avatar : 30}" width="30"
                                 srcset="{['photo' => $item.photo, 'email' => $item.email] | avatar : 60} 2x"/>
                        </a>
                    </div>
                    <div class="ml-2 created">
                        <div class="author">
                            <a href="/users/{$item.usename ? $item.username : $item.createdby}">{$item.fullname}</a>
                        </div>
                        <div class="date">{$item.createdon | dateago}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mt-2 mt-md-0 col-md-3 ml-md-auto d-flex justify-content-around justify-content-md-end">
                    <div class="d-flex">
                        <div class="link-comment">
                            <a href="#comment-{$item.id}">
                                <i class="far fa-hashtag"></i>
                            </a>
                        </div>
                        {if $_modx->user.id}
                            <div class="star ml-3{if $item.star} active{/if}">
                                <a href="#" class="placeholder"></a>
                            </div>
                        {/if}
                    </div>
                    <div class="ml-md-5">
                        <div class="rating">
                            <i class="far fa-arrow-up mr-2"></i>
                            {if $item.rating > 0}
                                <span class="text-success">+{$item.rating}</span>
                            {elseif $item.rating < 0}
                                <span class="text-danger">{$item.rating}</span>
                            {else}
                                <span>{$item.rating}</span>
                            {/if}
                            <i class="far fa-arrow-down ml-2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="comment-text mt-2">
                {$item.text | escape | prism}
            </div>
            <div class="comment-footer mt-2 d-flex flex-wrap">
                <a href="#" class=""><i class="far fa-pencil"></i> {$.en ? 'Reply' : 'Ответить'}</a>
            </div>
        </div>
    </div>
{/if}

{if $item.children}
    {var $level = $level + 1}
    {if $level < 10}
        <ul class="comments-list">
    {/if}
    {foreach $item.children as $child}
        {include 'file:chunks/comments/comments-thread.tpl' item=$child level=$level seen=$seen thread=$thread}
    {/foreach}
    {if $level < 10}
        </ul>
    {/if}
{/if}