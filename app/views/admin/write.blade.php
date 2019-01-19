@extends('layouts.admin')
@section('title', '撰写文章')
@section('content')

<form action="/admin/write" method="post" enctype="multipart/form-data">
    <!--文章内容-->
        <div class="item_name">
            <b>撰写文章</b><span id="msg_2"></span>
        </div>
        <div id="msg"></div>
        <div id="post" class="form-group">
            <div class="title">
                <input type="text" name="title" id="title" value="" required class="form-control" placeholder="文章标题" />
            </div>
            <div>
                <script type="text/plain" id="content" required name="content" style="width:100%; height:300px;"></script>
            </div>
        </div>
        <div class=line></div>
        <div class="form-group">
            <select name="sort_id" style="width:180px" id="sort_id" class="form-control">
                <option value="0">选择分类...</option>
                @foreach($sortList as $sort)
                    <option value="{{$sort->id}}">{{$sort->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="checkbox" value="y" name="allow_remark" id="allow_remark" checked="checked" />
            <label for="allow_remark">允许评论</label>
        </div>
        <div id="post_button">
            <input type="submit" value="发布文章" onclick="return checkForm();" class="btn btn-primary" />
        </div>
        <textarea name="" id="content_old" ></textarea>

</form>
<script>
    var ue = UE.getEditor('content');
    var content_old = $('#content_old').val();
    ue.ready(function(){
        if(content_old) {
            ue.setContent(content_old);
        }
    });
    function checkForm() {
        var content = ue.getContentTxt();
        if(!content.length) {
            ue.focus();
            return false;
        }
        return true;
    }
</script>
@endsection