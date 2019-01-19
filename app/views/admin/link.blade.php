@extends('layouts.admin')
@section('title', '友链管理')
@section('content')
    <div class="item_name">
        <b>友链管理</b><span id="msg"></span>
    </div>
    <table class="table table-striped table-bordered table-hover dataTable no-footer">
        <thead>
        <tr>
            <th width="75"><b>序号</b></th>
            <th><b>名称</b></th>
            <th><b>状态</b></th>
            <th><b>描述</b></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($linkList as $link)
            <tr>
                <td>
                    <input class="form-control em-small" name="link_id[]" lid="{{$link->id}}" value="{{$link->taxis}}">
                </td>
                <td class="linkname">
                    <a href="{{$link->url}}" target="_blank">{{$link->name}}</a>
                </td>
                <td><a href="javascript:void(0)" onclick="toggleShow('{{$link->id}}','{{$link->hide}}')">@if($link->hide == 'n')显示 @else 隐藏 @endif</a></td>
                <td>{{$link->description}}</td>
                <td>
                    <a href="/admin/link/{{$link->id}}">编辑</a>
                    <a href="javascript:deleteLink('{{$link->id}}')" class="care">删除</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="list_footer">
        <a href="javascript:void(0)" id="changeOrder" class="btn btn-primary">修改顺序</a>
        <a href="javascript:$('#link_add_form').toggleClass('hide');" class="btn btn-success">添加链接+</a>
    </div>
    <p>&nbsp;</p>
    <form class="form-horizontal col-sm-9 col-md-7 col-lg-5 hide" id="link_add_form" role="form">
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
            <label for="name" class="col-sm-3 control-label">地址</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="url" name="url" required placeholder="链接地址">
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-3 control-label">描述</label>
            <div class="col-sm-9">
                <textarea name="description" id="description" type="text" rows="3" class="form-control" placeholder="链接描述"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="button" id="add_link" class="btn btn-primary" >添加链接</button><span id="add_msg"></span>
            </div>
        </div>

    </form>
    <script>
        function toggleShow(id,state) {
            var state = state === 'y' ? 'n':'y';
            $.ajax({
                type: 'post',
                dataType:'JSON',
                url: '/admin/linkHide',
                data: 'id='+id+'&hide='+state+'&csrf_token='+CSRFTOKEN,
                headers: {'CSRF-TOKEN': CSRFTOKEN},
                success: function (res) {
                    if (res) {
                        var status = parseInt(res.status);
                        if(status === 1) {
                            location.href = '/admin/link';
                        } else {
                            alert('修改失败');
                        }
                    }
                }
            });
        }

        function deleteLink(id) {
            if(id) {
                if(confirm('确认删除该链接？')) {
                    $.ajax({
                        type: 'post',
                        dataType:'JSON',
                        url: '/admin/linkDelete',
                        data: 'id='+id+'&csrf_token='+CSRFTOKEN,
                        headers: {'CSRF-TOKEN': CSRFTOKEN},
                        success: function (res) {
                            if (res) {
                                var status = parseInt(res.status);
                                if(status === 1) {
                                    location.href = '/admin/link';
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
            var orders = $("input[name='link_id[]']");
            if(orders.length) {
                var data = [];
                orders.each(function() {
                    data.push($(this).attr('lid')+':'+$(this).val());
                });
                $.ajax({
                    type: 'post',
                    dataType:'JSON',
                    url: '/admin/linkReOrder',
                    data: {data:data,csrf_token:CSRFTOKEN},
                    headers: {'CSRF-TOKEN': CSRFTOKEN},
                    success: function (res) {
                        if (res) {
                            var status = parseInt(res.status);
                            if(status === 1) {
                                location.href = '/admin/link';
                            } else {
                                alert('修改失败');
                            }
                        }
                    }
                });
            }
        });
        $('#add_link').click(function(){
            var taxis = parseInt($('#taxis').val());
            var name = $('#name').val();
            var url = $('#url').val();
            if(isNaN(taxis)) {
                alert('序号必须为数字');
                $('#taxis').focus();
                return;
            }
            if(!name.length) {
                $('#name').focus();
                return;
            }
            if(!url.length) {
                $('#url').focus();
                return;
            }
            var data = {
                csrf_token:CSRFTOKEN,
                taxis : taxis,
                name : name,
                url : url,
                description : $('#description').val()
            };

            $.ajax({
                type:'post',
                url:'/admin/link',
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
                                alert('添加失败');
                                break;
                        }
                    }
                }
            });
        });
    </script>
@endsection