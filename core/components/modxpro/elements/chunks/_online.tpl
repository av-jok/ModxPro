<ul class="nav nav-tabs" id="online-tabs">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-home" role="tab">
            {$.en ? 'Comments' : 'Комментарии'}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-topics" role="tab">
            {$.en ? 'Topics' : 'Заметки'}
        </a>
    </li><li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-job" role="tab">
            {$.en ? 'Job' : 'Работа'}
        </a>
    </li>
</ul>
<div class="tab-content mt-3" id="online-content">
    <div class="tab-pane fade show active" id="tab-home">
        {var $res = $.App->runProcessor('community/comment/getlatest', [
            'limit' => 10,
        ])}
        <div class="comments-latest">
            {$res.results}
        </div>
    </div>
    <div class="tab-pane fade" id="tab-topics">
        {var $res = $.App->runProcessor('community/topic/getlatest', [
            'limit' => 10,
            'where' => ['Section.alias:NOT IN' => ['work']],
        ])}
        <div class="topics-latest">
            {$res.results}
        </div>
    </div>
    <div class="tab-pane fade" id="tab-job">
        {var $res = $.App->runProcessor('community/topic/getlatest', [
            'limit' => 10,
            'where' => ['Section.alias' => 'work'],
        ])}
        <div class="topics-latest">
            {$res.results}
        </div>
    </div>
</div>