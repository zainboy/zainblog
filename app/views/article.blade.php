@extends('layouts.front')
@section('title', $article->title)
@section('content')
    <div class="posts">
        <div class="post">
            <h3>{{$article->title}}</h3>
            <h5>分类：{{$article->sort->name}}， 发布于 {{str_limit($article->created_at,10,'')}}，浏览({{$article->views}})，评论({{count($comments)}})</h5>
            <hr>
            <div class="content">
                {!! $article->content !!}
            </div>
        </div>

        <a name="comments"></a>
        <div class="comments">
            <div class="comment-header"><strong>评论：</strong></div>
            <div class="comment-body">
                @foreach($comments as $comment)
                    <div class="comment" id="comment_{{$comment->id}}">
                        <div class="row">
                            <div class="pull-left">
                                <img src="@if(intval($comment->admin) === 1) /img/user.png @else /img/avatar_s.png @endif" alt="">
                            </div>
                            <div class="pull-left">
                                <h5><strong>{{$comment->nickname}}</strong><a href="javascript:void(0)" onclick="showReply({{$comment->id}})">回复</a></h5>
                                <h5>{{$comment->created_at}}</h5>
                                <p>{{$comment->comment}}</p>
                            </div>
                        </div>
                        @if(isset($comment->children))
                            {{comments_children($comment->children)}}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="reply-wrap">
            <div class="reply">
                <form class="form-horizontal" action="/comment/{{$segment[2]}}" method="post">
                    <div class="form-group">
                        <label class="col-sm-1 control-label">昵称</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" maxlength="12" name="nickname" @if(isset($_COOKIE['foream_blog_guest_nickname'])) value="{{$_COOKIE['foream_blog_guest_nickname']}}" @endif placeholder="昵称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">邮箱</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" maxlength="30" name="mail" @if(isset($_COOKIE['foream_blog_guest_mail'])) value="{{$_COOKIE['foream_blog_guest_mail']}}" @endif placeholder="邮箱">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">内容</label>
                        <div class="col-sm-6">
                            <textarea class="form-control" name="comment" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-6">
                            <input type="hidden" name="pid" class="comment-pid" value="0">
                            <button type="button" class="post-comment btn btn-primary">发表评论</button>
                            <button type="button" class="reset btn btn-default">取消</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            $('.cate-{{$article->sort_id}}').addClass('active');
        });
        function showReply(commentId) {
            $('.reply').hide();
            if($('#comment_'+commentId+' .reply').length) {
                $('#comment_'+commentId+' .reply').remove();
            }
            $('#comment_'+commentId).append($('.reply-wrap').html());
            $('#comment-pid').val(commentId);
            $('#comment_'+commentId+' .reply').show();
        }

        $(document).on('click','.reset',function(){
            $('.reply').hide();
            $('.reply-wrap .reply').show();
        });

        $(document).on('click','.post-comment',function(){
            var t = $(this).parents('.reply');
            var nickname = $("input[name='nickname']",t).val();
            var mail = $("input[name='mail']",t).val();
            var comment = $("textarea[name='comment']",t).val();
            var pid = $("input[name='pid']",t).val();
            if(!nickname) {
                $("input[name='nickname']",t).focus();
                return false;
            }
            if(!mail) {
                $("input[name='mail']",t).focus();
                return false;
            }
            if(! /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(mail)) {
                $("input[name='mail']",t).focus();
                return false;
            }
            if(!comment) {
                $("textarea[name='comment']",t).focus();
                return false;
            }
            $.ajax({
                type: 'post',
                dataType:'JSON',
                url: '/comment/{{$article->id}}',
                data: {nickname:nickname,mail:mail,comment:comment,pid:pid,csrf_token:CSRFTOKEN},
                headers: {'CSRF-TOKEN': CSRFTOKEN},
                success: function (res) {
                    if (res) {
                        var status = parseInt(res.status);
                        if(status === 1) {
                            location.reload();
                        } else if(status === -1) {
                            alert('昵称已存在');
                            $("input[name='nickname']",t).focus();
                        } else if(status === -2) {
                            alert('邮箱已存在');
                            $("input[name='mail']",t).focus();
                        } else {
                            alert('评论失败');
                        }
                    }
                }
            });
            return false;
        });
    </script>
@endsection