@extends('layouts.admin')
@section('title', '文章管理')
@section('content')
    <div class="item_name">
        <b>文章管理</b><span id="msg_2"></span>
    </div>
    <div style="margin: 0px 15px;">
        <div class="line"></div>
        <div class="filters">
            <div id="f_title" class="form-inline">
                <div style="float:left; margin-top:8px;">
                    <span id="f_t_sort">
                        <select name="by_sort" id="by_sort" class="form-control">
                            <option value="0">按分类查看</option>
                            @foreach($sortList as $sort)
                                <option value="{{$sort->id}}" @if(isset($segment[3]) && $segment[3] == $sort->id) selected @endif>{{$sort->name}}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div style="float:right;">
                    <form action="/admin/search" method="post">
                        <input type="text" name="keyword" class="form-control" placeholder="输入文章标题">
                        <input class="btn btn-default" type="submit" value="搜索">
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <form action="admin_log.php?action=operate_log" method="post" name="form_log" id="form_log">
            <input type="hidden" name="pid" value="">
            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr>
                    <th width="511" colspan="2"><b>标题</b></th>
                    <th width="50" class="tdcenter"><b>查看</b></th>
                    <th width="146"><b>分类</b></th>
                    <th width="130"><b><a href="/admin/articles?sortDate=DESC">最后更新</a></b></th>
                    <th width="49" class="tdcenter"><b>评论</b></th>
                    <th width="59" class="tdcenter"><b><a href="/admin/articles?sortView=ASC">阅读</a></b></th>
                </tr>
                </thead>
                <tbody>
                @foreach($articleList as $article)
                    <tr>
                        <td width="21"><input type="checkbox" name="post_id[]" value="{{$article->id}}" class="ids"></td>
                        <td width="490"><a href="/admin/article/{{$article->id}}">{{$article->title}}</a></td>
                        <td class="tdcenter"><a href="/article/{{$article->id}}" target="_blank" title="在新窗口查看">点击</a></td>
                        <td><a href="/admin/articles?sort_id={{$article->sort->id}}">{{$article->sort->name}}</a></td>
                        <td class="small">{{$article->updated_at}}</td>
                        <td class="tdcenter"><a href="/article/{{$article->id}}">{{$article->comments}}</a></td>
                        <td class="tdcenter">{{$article->views}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="list_footer form-inline">
                <a href="javascript:void(0);" id="select_all">全选</a>
                <a href="javascript:void(0);" id="delete_articles" class="btn btn-warning" role="button">删除</a>
                <select name="sort" id="change_sort"  class="form-control">
                    <option value="" selected="selected">移动至分类</option>
                    @foreach($sortList as $sort)
                        <option value="{{$sort->id}}">{{$sort->name}}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="page">
            @if ($articleList->hasPages())
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($articleList->onFirstPage())
                        <li class="disabled"><span>&laquo;</span></li>
                    @else
                        <li><a href="{{ $articleList->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($articleList->hasMorePages())
                        <li><a href="{{ $articleList->nextPageUrl() }}" rel="next">&raquo;</a></li>
                    @else
                        <li class="disabled"><span>&raquo;</span></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <script>
        $(function(){
            $('#by_sort').change(function(){
                var sortId = parseInt($(this).val());
                window.location = sortId ? '/admin/articles/' + sortId : '/admin/articles';
            });

            $('#change_sort').change(function(){
                var sortId = parseInt($(this).val());
                if(sortId) {
                    var selected = $('.ids:checked').length;
                    if(!selected) {
                        alert('请选择要移动分类的文章');
                        return false;
                    }
                    var ids = [];
                    $('.ids:checked').each(function(i,o){
                        ids.push($(o).val());
                    });
                    if(confirm('确定要将所选文章移动至分类 '+$('#change_sort option:selected').text()+' ？')) {
                        $.ajax({
                            type: 'post',
                            dataType:'JSON',
                            url: '/admin/changeSort/'+sortId,
                            data: {ids:ids,csrf_token:CSRFTOKEN},
                            headers: {'CSRF-TOKEN': CSRFTOKEN},
                            success: function (res) {
                                if (res) {
                                    var status = parseInt(res.status);
                                    if(status === 1) {
                                        location.reload();
                                    } else {
                                        alert('移动失败');
                                    }
                                }
                            }
                        });
                    }
                }
            });

            $('#delete_articles').click(function(){
                var selected = $('.ids:checked').length;
                if(!selected) {
                    alert('请选择要删除的文章');
                    return false;
                }
                var ids = [];
                $('.ids:checked').each(function(i,o){
                    ids.push($(o).val());
                });
                if(confirm('确定要删除所选文章？')) {
                    $.ajax({
                        type: 'post',
                        dataType:'JSON',
                        url: '/admin/articleDelete',
                        data: {ids:ids,csrf_token:CSRFTOKEN},
                        headers: {'CSRF-TOKEN': CSRFTOKEN},
                        success: function (res) {
                            if (res) {
                                var status = parseInt(res.status);
                                if(status === 1) {
                                    location.reload();
                                } else {
                                    alert('删除失败');
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection