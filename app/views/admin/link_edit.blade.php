@extends('layouts.admin')
@section('title', '链接修改')
@section('content')
    <div class="item_name">
        <b>链接修改</b>
    </div>
    <form class="form-horizontal col-sm-9 col-md-7 col-lg-5" id="link_add_form" role="form">
        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">名称</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" value="{{$link->name}}" name="name" required placeholder="名称">
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">地址</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="url" value="{{$link->url}}" name="url" required placeholder="链接地址">
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">描述</label>
            <div class="col-sm-9">
            <textarea name="description" id="description" type="text" rows="3" class="form-control" placeholder="链接描述">{{$link->description}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="button" id="edit_link" class="btn btn-primary" >修改链接</button><span id="add_msg"></span>
            </div>
        </div>

    </form>
    <script>
        $('#edit_link').click(function(){
            var name = $('#name').val();
            var url = $('#url').val();
            if(!name.length) {
                $('#name').focus();
                return;
            }
            if(!url.length) {
                $('#url').focus();
                return;
            }
            var data = {
                name : name,
                url : url,
                description : $('#description').val(),
                csrf_token:CSRFTOKEN
            };

            $.ajax({
                type:'post',
                url:'/admin/link/{{$segment[3]}}',
                dataType:'JSON',
                data:data,
                headers: {'CSRF-TOKEN':CSRFTOKEN},
                success:function(res) {
                    if(res) {
                        var status = parseInt(res.status);
                        switch(status) {
                            case 1:
                                location.href = '/admin/link';
                                break;
                            case -1:
                                alert(res.tips);
                                break;
                            default:
                                alert('修改失败');
                                break;
                        }
                    }
                }
            });
        });
    </script>
@endsection