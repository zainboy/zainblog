<div class="sidebar">
    <h4>存档</h4>
    <ol class="list-unstyled">
    @foreach($countArticleByMonth as $month)
        <li><a href="/?m={{$month->month}}">{{$month->month}} ({{$month->count}})</a></li>
    @endforeach
    </ol>
    <h4>分类</h4>
    <ol class="list-unstyled">
        @foreach($sorts as $sort)
            <li><a href="/sort/{{$sort->id}}">{{$sort->name}}</a></li>
        @endforeach
    </ol>
    <h4>链接</h4>
    <ol class="list-unstyled">
        @foreach($links as $link)
            <li><a href="{{$link->url}}" target="_blank">{{$link->name}}</a></li>
        @endforeach
    </ol>
    <h4><a href="/login">管理</a></h4>
</div>