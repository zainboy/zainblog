@extends('layouts.admin')
@section('title', '分类修改')
@section('content')
    <div class="item_name">
        <b>分类修改</b>
    </div>
    <form class="form-horizontal col-sm-9 col-md-7 col-lg-5" id="sort_add_form" role="form">
        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">名称</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" value="{{$sort->name}}" name="name" required placeholder="名称">
            </div>
        </div>
        <div class="form-group">
            <label for="pid" class="col-sm-3 control-label">父分类</label>
            <div class="col-sm-9">
            <select name="pid" id="pid" class="form-control">
                <option value="0">无</option>
                @foreach($sortList as $sort)
                <option @if($sort->pid == $segment[3]) selected @endif value="{{$sort->id}}">{{$sort->name}}</option>
                @endforeach
            </select>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">描述</label>
            <div class="col-sm-9">
            <textarea name="description" id="description" type="text" rows="3" class="form-control" placeholder="分类描述">{{$sort->description}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="button" id="edit_sort" class="btn btn-primary" >修改分类</button><span id="add_msg"></span>
            </div>
        </div>

    </form>
    <script>
        $('#edit_sort').click(function(){
            var name = $('#name').val();
            if(!name.length) {
                $('#name').focus();
                return;
            }
            var data = {
                name : name,
                pid : $('#pid').val(),
                description : $('#description').val(),
                csrf_token:CSRFTOKEN
            };

            $.ajax({
                type:'post',
                url:'/admin/sort/{{$segment[3]}}',
                dataType:'JSON',
                data:data,
                headers: {'CSRF-TOKEN':CSRFTOKEN},
                success:function(res) {
                    if(res) {
                        var status = parseInt(res.status);
                        switch(status) {
                            case 1:
                                location.href = '/admin/sort';
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