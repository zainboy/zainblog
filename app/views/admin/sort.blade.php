@extends('layouts.admin')
@section('title', '分类管理')
@section('content')
    <div class="item_name">
        <b>分类管理</b><span id="msg"></span>
    </div>
    <table class="table table-striped table-bordered table-hover dataTable no-footer">
        <thead>
        <tr>
            <th width="75"><b>序号</b></th>
            <th><b>名称</b></th>
            <th><b>描述</b></th>
            <th class="tdcenter"><b>文章</b></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($sortList as $sort)
        <tr>
            <td>
                <input class="form-control em-small" name="sort_id[]" sid="{{$sort->id}}" value="{{$sort->taxis}}">
            </td>
            <td class="sortname">
                <a href="/sort/{{$sort->id}}" target="_blank">{{$sort->name}}</a>
            </td>
            <td>{{$sort->description}}</td>
            <td>0</td>
            <td>
                <a href="/admin/sort/{{$sort->id}}">编辑</a>
                <a href="javascript:deleteSort('{{$sort->id}}')" class="care">删除</a>
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
    <div class="list_footer">
        <a href="javascript:void(0)" id="changeOrder" class="btn btn-primary">修改顺序</a>
        <a href="javascript:$('#sort_add_form').toggleClass('hide');" class="btn btn-success">添加分类+</a>
    </div>
    <p>&nbsp;</p>
    <form class="form-horizontal col-sm-9 col-md-7 col-lg-5 hide" id="sort_add_form" role="form">
        <div class="form-group">
            <label for="taxis" class="col-sm-3 control-label">序号</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="taxis" required id="taxis" placeholder="序号">
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">名称</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="name" required placeholder="名称">
            </div>
        </div>
        <div class="form-group">
            <label for="pid" class="col-sm-3 control-label">父分类</label>
            <div class="col-sm-9">
            <select name="pid" id="pid" class="form-control">
                <option value="0">无</option>
                @foreach($sortList as $sort)
                <option value="{{$sort->id}}">{{$sort->name}}</option>
                @endforeach
            </select>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">描述</label>
            <div class="col-sm-9">
            <textarea name="description" id="description" type="text" rows="3" class="form-control" placeholder="分类描述"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="button" id="add_sort" class="btn btn-primary" >添加新分类</button><span id="add_msg"></span>
            </div>
        </div>

    </form>
    <script>
        function deleteSort(id) {
            if(id) {
                if(confirm('确认删除该分类？')) {
                    $.ajax({
                        type: 'post',
                        dataType:'JSON',
                        url: '/admin/sortDelete',
                        data: 'id='+id+'&csrf_token='+CSRFTOKEN,
                        headers: {'CSRF-TOKEN': CSRFTOKEN},
                        success: function (res) {
                            if (res) {
                                var status = parseInt(res.status);
                                if(status === 1) {
                                    location.href = '/admin/sort';
                                } else {
                                    alert('删除失败');
                                }
                            }
                        }
                    });
                }
            }
        }
        $('#changeOrder').click(function(){
            var orders = $("input[name='sort_id[]']");
            if(orders.length) {
                var data = [];
                orders.each(function() {
                    data.push($(this).attr('sid')+':'+$(this).val());
                });
                $.ajax({
                    type: 'post',
                    dataType:'JSON',
                    url: '/admin/sortReOrder',
                    data: {data:data,csrf_token:CSRFTOKEN},
                    headers: {'CSRF-TOKEN': CSRFTOKEN},
                    success: function (res) {
                        if (res) {
                            var status = parseInt(res.status);
                            if(status === 1) {
                                location.href = '/admin/sort';
                            } else {
                                alert('修改失败');
                            }
                        }
                    }
                });
            }

        });
        $('#add_sort').click(function(){
            var taxis = parseInt($('#taxis').val());
            var name = $('#name').val();
            if(isNaN(taxis)) {
                alert('序号必须为数字');
                $('#taxis').focus();
                return;
            }
            if(!name.length) {
                $('#name').focus();
                return;
            }
            var data = {
                taxis : taxis,
                name : name,
                pid : $('#pid').val(),
                description : $('#description').val(),
                csrf_token:CSRFTOKEN
            };

            $.ajax({
                type:'post',
                url:'/admin/sort',
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
                                alert('添加失败');
                                break;
                        }
                    }
                }
            });
        });
    </script>
@endsection