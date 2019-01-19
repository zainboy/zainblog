@extends('layouts.admin')
@section('title', '评论管理')
@section('content')
    <div class="item_name">
        <b>评论管理</b><span id="msg_2"></span>
    </div>
    <form method="post" name="form_com" id="form_com">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <thead>
            <tr>
                <th width="369" colspan="2"><b>内容</b></th>
                <th width="300"><b>评论者</b></th>
                <th width="250"><b>时间</b></th>
                <th width="250"><b>文章</b></th>
            </tr>
            </thead>
            <tbody>
            @foreach($commentList as $comment)
            <tr>
                <td><input type="checkbox" value="{{$comment->id}}" name="comment_id[]" class="ids"></td>
                <td>{{$comment->comment}}</td>
                <td>{{$comment->nickname}}</td>
                <td>{{$comment->created_at}}</td>
                <td><a href="/articles/{{$comment->article_id}}" target="_blank" title="查看该文章">{{$comment->article->title}}</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div class="list_footer">
            <a href="javascript:void(0);" id="select_all">全选</a> 选中项：
            <a href="javascript:deleteComment();" class="care">删除</a>
            {{--<a href="javascript:hideComment();">隐藏</a>--}}
            {{--<a href="javascript:commentact('pub');">审核</a>--}}
            <input name="operate" id="operate" value="" type="hidden">
        </div>

        <div class="page">
            @if ($commentList->hasPages())
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($commentList->onFirstPage())
                        <li class="disabled"><span>&laquo;</span></li>
                    @else
                        <li><a href="{{ $commentList->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($commentList->hasMorePages())
                        <li><a href="{{ $commentList->nextPageUrl() }}" rel="next">&raquo;</a></li>
                    @else
                        <li class="disabled"><span>&raquo;</span></li>
                    @endif
                </ul>
            @endif
        </div>
    </form>
    <script>
        function deleteComment() {
            var comments = $("input[name='comment_id[]']:checked");
            if(comments.length) {
                var data = [];
                comments.each(function() {
                    data.push($(this).val());
                });
                $.ajax({
                    type: 'post',
                    dataType:'JSON',
                    url: '/admin/commentDelete',
                    data: {data:data,csrf_token:CSRFTOKEN},
                    headers: {'CSRF-TOKEN': CSRFTOKEN},
                    success: function (res) {
                        if (res) {
                            var status = parseInt(res.status);
                            if(status === 1) {
                                location.href = '/admin/comment';
                            } else {
                                alert('删除失败');
                            }
                        }
                    }
                });
            }
        }
        function hideComment() {
            
        }

        $('#select_all').click(function(){
            if($(this).text() === '全选') {
                $(this).text('全不选');
                $('.ids').prop('checked', true);
            } else {
                $(this).text('全选');
                $('.ids').prop('checked', false);
            }

        });
    </script>
@endsection