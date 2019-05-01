@if(isset($list))
    <div style="display: flex; justify-content: center;margin-top: 10px;">
        <div class="page">{{$list->appends(request()->query())->links()}}<span class="count_span">共计：{{$list->total()}} 条 数据</span>
        </div>
    </div>
@endif