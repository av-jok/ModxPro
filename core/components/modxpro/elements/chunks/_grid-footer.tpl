<div class="toolbar bottom-bar{$hidden ? ' hidden' : ''}">
    <div class="left">
        <span class="prev-wrapper">
            <a href="#" class="btn btn-light prev"><i class="far fa-chevron-left"></i></a>
        </span>
        <span class="page-wrapper">
            {$.en ? 'Page' : 'Страница'}: <input type="number" value="" class="page"/>
            {$.en ? 'from' : 'из'} <span class="pages"></span>
        </span>
        <span class="next-wrapper">
            <a href="#" class="btn btn-light next"><i class="far fa-chevron-right"></i></a>
        </span>
        <span class="reload-wrapper">
            <a href="#" class="btn btn-light reload"><i class="far fa-sync"></i></a>
        </span>
    </div>
    {if $middle !== false}
        <div class="middle">
            <span class="limit-wrapper">
                {$.en ? 'Per page' : 'На странице'} <input type="number" value="" class="limit"/>
            </span>
        </div>
    {/if}
    <div class="right">
        <span class="total-wrapper">
            {$.en ? 'Total' : 'Всего результатов'}: <span class="total"></span>
        </span>
    </div>
</div>