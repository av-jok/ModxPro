<form method="get" action="/search">
    <div class="input-group">
        <input type="text" name="query" class="form-control" placeholder="{$.en ? 'Search' : 'Поиск'}">
        <div class="input-group-append">
            <button class="input-group-text">
                <i class="far fa-search"></i>
            </button>
        </div>
    </div>
</form>

<div  class="mt-5">
    {$.en ? 'Here will be search results' : 'Здесь будет поиск'}
</div>